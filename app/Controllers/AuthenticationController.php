<?php
// app/Controllers/AuthenticationController.php

require_once __DIR__ . '/../Models/UserModel.php';

class AuthenticationController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Handle login request
     */
    public function login() {
        // Only allow POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate input
        if (!isset($input['username']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username and password are required']);
            return;
        }

        $username = trim($input['username']);
        $password = $input['password'];
        $role = isset($input['role']) ? $input['role'] : null;

        // Authenticate user
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Check if role matches (if provided)
            if ($role) {
                $expectedRole = ($role === 'patient') ? 'Patient' : 
                               (($role === 'reception') ? 'Receptionist' : null);
                
                if ($expectedRole && $user['Role'] !== $expectedRole) {
                    http_response_code(401);
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Invalid credentials for selected role'
                    ]);
                    return;
                }
            }

            // Get additional user details based on role
            $userDetails = null;
            if ($user['Role'] === 'Patient') {
                $userDetails = $this->userModel->getPatientByUserId($user['UserID']);
            } elseif ($user['Role'] === 'Receptionist' || $user['Role'] === 'Doctor') {
                $userDetails = $this->userModel->getEmployeeByUserId($user['UserID']);
            }

            // Start session and store user data
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['name'] = $user['Name'];
            $_SESSION['role'] = $user['Role'];
            
            // Store additional details
            if ($userDetails) {
                if ($user['Role'] === 'Patient') {
                    $_SESSION['patient_id'] = $userDetails['PatientID'];
                    $_SESSION['email'] = $userDetails['Email'];
                } else {
                    $_SESSION['employee_id'] = $userDetails['EmployeeID'];
                }
            }

            // Determine redirect URL based on role
            $redirectUrl = '';
            switch ($user['Role']) {
                case 'Patient':
                    $redirectUrl = BASE_URL . '/public/patient/dashboard';
                    break;
                case 'Receptionist':
                    $redirectUrl = BASE_URL . '/public/receptionist/dashboard';
                    break;
                case 'Doctor':
                    $redirectUrl = BASE_URL . '/public/doctor/dashboard';
                    break;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['UserID'],
                    'username' => $user['Username'],
                    'name' => $user['Name'],
                    'role' => $user['Role']
                ],
                'redirect' => $redirectUrl
            ]);

        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    }

    /**
     * Handle logout request
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Store role before destroying session (for redirect)
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

        // Destroy session
        session_unset();
        session_destroy();

        // Clear session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        echo json_encode([
            'success' => true,
            'message' => 'Logout successful',
            'redirect' => BASE_URL . '/public'
        ]);
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    /**
     * Get current user session data
     */
    public function getCurrentUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->isAuthenticated()) {
            return null;
        }

        return [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'name' => $_SESSION['name'],
            'role' => $_SESSION['role'],
            'patient_id' => $_SESSION['patient_id'] ?? null,
            'employee_id' => $_SESSION['employee_id'] ?? null
        ];
    }

    /**
     * Require authentication - redirect to login if not authenticated
     */
    public static function requireAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
    }

    /**
     * Require specific role
     */
    public static function requireRole($allowedRoles) {
        self::requireAuth();

        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        if (!in_array($_SESSION['role'], $allowedRoles)) {
            http_response_code(403);
            die('Access denied. Insufficient permissions.');
        }
    }
}
