<?php

require_once __DIR__ . '/../../config/database.php';

class Appointment
{
    /** @var PDO */
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn instanceof PDO) {
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    /**
     * Get available schedule slots for a specific date.
     */
    public function getAvailableSlots(string $date): array
    {
        $query = "
            SELECT
                s.ScheduleID AS schedule_id,
                DATE_FORMAT(s.Start_time, '%H:%i') AS start_time,
                DATE_FORMAT(s.End_time, '%H:%i') AS end_time,
                u.Name AS doctor,
                e.Speciality AS department,
                s.Available_slot AS available_slots,
                s.Capacity AS capacity
            FROM schedule s
            INNER JOIN employee e ON s.EmployeeID = e.EmployeeID
            INNER JOIN users u ON e.UserID = u.UserID
            WHERE s.Date = :date
            ORDER BY s.Start_time ASC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['date' => $date]);
        $rows = $stmt->fetchAll();

        return array_map(function (array $row): array {
            $capacity = (int) ($row['capacity'] ?? 0);
            $available = max((int) ($row['available_slots'] ?? 0), 0);

            return [
                'schedule_id' => (int) $row['schedule_id'],
                'time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'doctor' => $row['doctor'],
                'department' => $row['department'],
                'room' => $row['department'] ? $row['department'] . ' Suite' : 'Dental Clinic',
                'availableSlots' => $available,
                'capacity' => $capacity,
                'bookedSlots' => max($capacity - $available, 0),
                'available' => $available > 0,
            ];
        }, $rows);
    }

    /**
     * Retrieve all appointments for the given patient.
     */
    public function getAppointmentsForPatient(int $patientId): array
    {
        $query = "
            SELECT
                a.AppntID AS id,
                a.ScheduleID AS schedule_id,
                a.Date AS date,
                DATE_FORMAT(a.Start_time, '%H:%i') AS time,
                a.Status AS status,
                u.Name AS doctor,
                e.Speciality AS department,
                MAX(ds.Name) AS service_name
            FROM appointment a
            INNER JOIN schedule s ON a.ScheduleID = s.ScheduleID
            INNER JOIN employee e ON s.EmployeeID = e.EmployeeID
            INNER JOIN users u ON e.UserID = u.UserID
            LEFT JOIN performs p ON a.AppntID = p.AppntID
            LEFT JOIN dental_service ds ON p.ServiceID = ds.ServiceID
            WHERE a.PatientID = :patientId
            GROUP BY a.AppntID, a.ScheduleID, a.Date, a.Start_time, a.Status, u.Name, e.Speciality
            ORDER BY a.Date DESC, a.Start_time DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['patientId' => $patientId]);
        $rows = $stmt->fetchAll();

        return array_map(function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'schedule_id' => (int) $row['schedule_id'],
                'doctor' => $row['doctor'],
                'department' => $row['department'],
                'date' => $row['date'],
                'time' => $row['time'],
                'status' => $row['status'],
                'service' => $row['service_name'],
            ];
        }, $rows);
    }

    /**
     * Create a new appointment for the given patient and schedule.
     */
    public function createAppointment(int $patientId, int $scheduleId): array
    {
        try {
            $this->conn->beginTransaction();

            $scheduleStmt = $this->conn->prepare('
                SELECT ScheduleID, Date, Start_time, End_time, Available_slot
                FROM schedule
                WHERE ScheduleID = :scheduleId
                FOR UPDATE
            ');
            $scheduleStmt->execute(['scheduleId' => $scheduleId]);
            $schedule = $scheduleStmt->fetch();

            if (!$schedule) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Selected schedule does not exist.'];
            }

            if ((int) $schedule['Available_slot'] <= 0) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'This slot is fully booked. Please choose another time.'];
            }

            $slotDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $schedule['Date'] . ' ' . $schedule['Start_time']);
            if ($slotDateTime && $slotDateTime < new DateTime()) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Cannot book appointments in the past.'];
            }

            $overlapStmt = $this->conn->prepare('
                SELECT COUNT(*)
                FROM appointment
                WHERE PatientID = :patientId
                  AND Status = \'Scheduled\'
                  AND Date = :date
                  AND Start_time = :startTime
            ');
            $overlapStmt->execute([
                'patientId' => $patientId,
                'date' => $schedule['Date'],
                'startTime' => $schedule['Start_time'],
            ]);

            if ((int) $overlapStmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'You already have another appointment at this time.'];
            }

                        $duplicateStmt = $this->conn->prepare('
                                SELECT COUNT(*)
                                FROM appointment
                                WHERE PatientID = :patientId
                                    AND ScheduleID = :scheduleId
                                    AND Status = \'Scheduled\'
                        ');
            $duplicateStmt->execute([
                'patientId' => $patientId,
                'scheduleId' => $scheduleId,
            ]);

            if ((int) $duplicateStmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'You already have a booking for this slot.'];
            }

            $insertStmt = $this->conn->prepare('
                INSERT INTO appointment (PatientID, ScheduleID, Date, Start_time, End_time, Status)
                VALUES (:patientId, :scheduleId, :date, :startTime, :endTime, \'Scheduled\')
            ');
            $insertStmt->execute([
                'patientId' => $patientId,
                'scheduleId' => $scheduleId,
                'date' => $schedule['Date'],
                'startTime' => $schedule['Start_time'],
                'endTime' => $schedule['End_time'],
            ]);

            $updateStmt = $this->conn->prepare('
                UPDATE schedule
                SET Available_slot = Available_slot - 1
                WHERE ScheduleID = :scheduleId
            ');
            $updateStmt->execute(['scheduleId' => $scheduleId]);

            $appointmentId = (int) $this->conn->lastInsertId();

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Appointment booked successfully.',
                'appointment_id' => $appointmentId,
            ];
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return ['success' => false, 'message' => 'Unable to book appointment. ' . $e->getMessage()];
        }
    }

    /**
     * Cancel a scheduled appointment that belongs to the patient.
     */
    public function cancelAppointment(int $patientId, int $appointmentId): array
    {
        try {
            $this->conn->beginTransaction();

            $appointmentStmt = $this->conn->prepare('
                SELECT AppntID, ScheduleID, Status
                FROM appointment
                WHERE AppntID = :appointmentId
                  AND PatientID = :patientId
                FOR UPDATE
            ');
            $appointmentStmt->execute([
                'appointmentId' => $appointmentId,
                'patientId' => $patientId,
            ]);
            $appointment = $appointmentStmt->fetch();

            if (!$appointment) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Appointment not found.'];
            }

            if ($appointment['Status'] !== 'Scheduled') {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Only scheduled appointments can be cancelled.'];
            }

            $cancelStmt = $this->conn->prepare('
                UPDATE appointment
                SET Status = \'Cancelled\'
                WHERE AppntID = :appointmentId
            ');
            $cancelStmt->execute(['appointmentId' => $appointmentId]);

            $updateSlotStmt = $this->conn->prepare('
                UPDATE schedule
                SET Available_slot = Available_slot + 1
                WHERE ScheduleID = :scheduleId
            ');
            $updateSlotStmt->execute(['scheduleId' => $appointment['ScheduleID']]);

            $this->conn->commit();

            return ['success' => true, 'message' => 'Appointment cancelled successfully.'];
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return ['success' => false, 'message' => 'Unable to cancel appointment. ' . $e->getMessage()];
        }
    }
}