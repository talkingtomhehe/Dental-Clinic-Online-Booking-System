<?php
// app/Controllers/AppointmentController.php

require_once __DIR__ . '/../Models/Appointment.php';
require_once __DIR__ . '/AuthenticationController.php';

class AppointmentController {
    private $appointmentModel;

    public function __construct() {
        $this->appointmentModel = new Appointment();
    }

    /**
     * Get appointments for current user
     */
    public function getAppointments() {
        // Only allow GET requests
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Check authentication
        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();

        // TODO: Implement get appointments logic
        echo json_encode([
            'success' => true,
            'message' => 'Get appointments endpoint - to be implemented',
            'data' => []
        ]);
    }

    /**
     * Book a new appointment
     */
    public function bookAppointment() {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Check authentication
        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // TODO: Implement booking logic
        echo json_encode([
            'success' => true,
            'message' => 'Book appointment endpoint - to be implemented',
            'data' => $input
        ]);
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment() {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Check authentication
        $auth = new AuthenticationController();
        if (!$auth->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $currentUser = $auth->getCurrentUser();

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // TODO: Implement cancellation logic
        echo json_encode([
            'success' => true,
            'message' => 'Cancel appointment endpoint - to be implemented',
            'data' => $input
        ]);
    }
}
