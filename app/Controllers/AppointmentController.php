<?php
// app/Controllers/AppointmentController.php

require_once __DIR__ . '/../Models/Appointment.php';
require_once __DIR__ . '/AuthenticationController.php';

class AppointmentController
{
    /** @var Appointment */
    private $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new Appointment();
    }

    /**
     * Return the authenticated patient's appointments.
     */
    public function getAppointments(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();
        if (($currentUser['role'] ?? null) !== 'Patient' || empty($currentUser['patient_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Patient account required.']);
            return;
        }

        $appointments = $this->appointmentModel->getAppointmentsForPatient((int) $currentUser['patient_id']);

        echo json_encode([
            'success' => true,
            'data' => $appointments,
        ]);
    }

    /**
     * Return available slots for a selected date.
     */
    public function getAvailableSlots(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();
        if (($currentUser['role'] ?? null) !== 'Patient' || empty($currentUser['patient_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Patient account required.']);
            return;
        }

        $date = $_GET['date'] ?? null;
        if (!$date) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameter: date']);
            return;
        }

        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateTime || $dateTime->format('Y-m-d') !== $date) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD.']);
            return;
        }

        $slots = $this->appointmentModel->getAvailableSlots($date);

        echo json_encode([
            'success' => true,
            'data' => $slots,
        ]);
    }

    /**
     * Book a new appointment for the authenticated patient.
     */
    public function bookAppointment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();
        if (($currentUser['role'] ?? null) !== 'Patient' || empty($currentUser['patient_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Patient account required.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input) || empty($input['schedule_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required field: schedule_id']);
            return;
        }

        $scheduleId = (int) $input['schedule_id'];
        if ($scheduleId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid schedule identifier.']);
            return;
        }

        $result = $this->appointmentModel->createAppointment((int) $currentUser['patient_id'], $scheduleId);

        if (!$result['success']) {
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => $result['message'],
            'appointment_id' => $result['appointment_id'] ?? null,
        ]);
    }

    /**
     * Cancel a scheduled appointment for the authenticated patient.
     */
    public function cancelAppointment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();
        if (($currentUser['role'] ?? null) !== 'Patient' || empty($currentUser['patient_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied. Patient account required.']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input) || empty($input['appointment_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required field: appointment_id']);
            return;
        }

        $appointmentId = (int) $input['appointment_id'];
        if ($appointmentId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid appointment identifier.']);
            return;
        }

        $result = $this->appointmentModel->cancelAppointment((int) $currentUser['patient_id'], $appointmentId);

        if (!$result['success']) {
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => $result['message'],
        ]);
    }
}
