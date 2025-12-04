<?php
//"http://localhost/Dental-Clinic-Online-Booking-System/config/database.php";
require_once __DIR__ . "/../../config/database.php";
class AppointmentModel {

    private static function connect() {
        $db = new Database();
        return $db->connect();
    }
    public static function getAvailableSlots($date) {
        $conn = self::connect();
        // $timeSlots= [
        //     ["id"=> 1, "time"=> "11:00", "doctor"=> "Dr. Trang Thanh Nghia", "department"=> "Cardiology", "room"=> "Room A1-102", "available"=> true ],
        //     ["id"=> 2,"time"=> "11:30", "doctor"=> "Dr. Nguyen Duc Dung", "department"=> "Orthopedics", "room"=> "Room B1-102", "available"=> true ],
        //     ["id"=> 3,"time"=> "11:40", "doctor"=> "Dr. Trang Thanh Nghia", "department"=> "Cardiology", "room"=> "Room A1-102", "available"=> false],
        //     ["id"=> 4,"time"=> "11:50", "doctor"=> "Dr. Trang Thanh Nghia", "department"=> "Cardiology", "room"=> "Room A1-102", "available"=> true ]
        // ];
        $sql = "SELECT 
            Schedule.ScheduleID AS id,
            DATE_FORMAT(Schedule.Start_time, '%H:%i') AS time,
            users.Name AS doctor,
            employee.speciality AS department,
            CASE 
                WHEN schedule.Available_Slot > 0 THEN TRUE
                ELSE FALSE
            END AS available
        FROM Schedule
        JOIN employee ON Schedule.EmployeeID = employee.EmployeeID
        JOIN users ON employee.UserID = users.UserID
        WHERE Schedule.Date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $date); 
        $stmt->execute();
        $result = $stmt->get_result();
        $timeSlots = [];
        while ($row = $result->fetch_assoc()) {
            $timeSlots[] = $row;
        }
        return $timeSlots;
    }
    public static function CreateAppointment($patient_id,$schedule_id,$date,$start_time) {
// DELETE FROM appointment WHERE appointment.AppntID = 9;
// UPDATE schedule SET Available_Slot = Available_Slot + 1 WHERE ScheduleID = 2
        $conn = self::connect();
        $sql = "INSERT INTO appointment (PatientID, ScheduleID, Date, Start_time, End_time, Status) 
                VALUES (?, ?, ?, ?, ?, 'Scheduled')";
        $stmt = $conn->prepare($sql);
        $end_time = date("H:i", strtotime($start_time . " +1 hour"));
        $stmt->bind_param("iisss", $patient_id, $schedule_id, $date, $start_time, $end_time); 
        if (!$stmt->execute()) {
            return ["success" => false, "error" => $stmt->error];
        }
        $stmt = $conn->prepare("UPDATE schedule SET Available_Slot = Available_Slot - 1 WHERE ScheduleID = ?");
        $stmt->bind_param("i", $schedule_id);
        if (!$stmt->execute()) {
            return ["success" => false, "error" => $stmt->error];
        }
        return ["success" => true, "message" => "Appointment created successfully"];
    }
    public static function CancelAppointment($appointment_id, $schedule_id) {
        // UPDATE appointment SET Status = 'Scheduled' WHERE AppntID = 7;
        // UPDATE schedule SET Available_Slot = Available_Slot - 1 WHERE ScheduleID = 7
        $conn = self::connect();
        $stmt = $conn->prepare("UPDATE appointment SET Status = 'Cancelled' WHERE AppntID = ?");
        $stmt->bind_param("i", $appointment_id);
        if (!$stmt->execute()) {
            return ["success" => false, "error" => $stmt->error];
        } 
        $stmt = $conn->prepare("UPDATE schedule SET Available_Slot = Available_Slot + 1 WHERE ScheduleID = ?");
        $stmt->bind_param("i", $schedule_id);
        if (!$stmt->execute()) {
            return ["success" => false, "error" => $stmt->error];
        } 
        return ["success" => true, "message" => "Appointment cancelled successfully"];
    }
    public static function getCurrentAppontment($patient_id) {
        $conn = self::connect();
        // $appointments = [
        //     ['id' => '1','title' => 'Follow Up','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '10/10/25','time' => '14:30','status' => 'Upcoming'],
        //     ['id' => '2','title' => 'General Checkup','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '2/10/25','time' => '10:00','status' => 'Cancelled'],
        //     ['id' => '3','title' => 'General Checkup','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '1/10/25','time' => '10:00','status' => 'Done'],
        //     ['id' => '4','title' => 'General Checkup','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '27/9/25','time' => '10:00','status' => 'Done'],
        //     ['id' => '5','title' => 'General Checkup','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '21/9/25','time' => '10:00','status' => 'Done'],
        //     ['id' => '6','title' => 'General Checkup','doctor' => 'Dr. Trang Thanh Nghia','department' => 'Cardiology','date' => '1/9/25','time' => '10:00','status' => 'Done']
        // ];
        $sql = "SELECT 
            appointment.AppntID AS id,
            schedule.ScheduleID AS schedule_id,
            dental_service.Name AS title,
            users.Name AS doctor,
            employee.Speciality AS department,
            appointment.Date AS date,
            appointment.Start_time AS time,
            appointment.Status AS status
        FROM appointment
        JOIN schedule ON appointment.ScheduleID = schedule.ScheduleID
        JOIN users ON schedule.EmployeeID = users.UserID
        JOIN employee ON users.UserID = employee.EmployeeID
        JOIN performs ON schedule.ScheduleID = performs.AppntID
        JOIN dental_service ON performs.ServiceID = dental_service.ServiceID
        WHERE appointment.PatientID = ?
        ORDER BY appointment.Date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);  // i = integer
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }
}
?>