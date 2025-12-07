<?php
// public/index.php - Router and Landing Page

require_once '../config/config.php';

// Simple routing
$request = $_SERVER['REQUEST_URI'];
$base_path = '/dental/public';

// Remove base path and query string
$route = str_replace($base_path, '', parse_url($request, PHP_URL_PATH));

// Handle API routes
if (strpos($route, '/api/') === 0) {
    header('Content-Type: application/json');
    
    switch ($route) {
        case '/api/login':
            require_once '../app/Controllers/AuthenticationController.php';
            $controller = new AuthenticationController();
            $controller->login();
            exit;
            
        case '/api/logout':
            require_once '../app/Controllers/AuthenticationController.php';
            $controller = new AuthenticationController();
            $controller->logout();
            exit;
            
        case '/api/appointments':
            require_once '../app/Controllers/AppointmentController.php';
            $controller = new AppointmentController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->bookAppointment();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller->getAppointments();
            }
            exit;

        case '/api/appointments/slots':
            require_once '../app/Controllers/AppointmentController.php';
            $controller = new AppointmentController();
            $controller->getAvailableSlots();
            exit;
            
        case '/api/appointments/cancel':
            require_once '../app/Controllers/AppointmentController.php';
            $controller = new AppointmentController();
            $controller->cancelAppointment();
            exit;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            exit;
    }
}

$normalizedRoute = rtrim($route, '/');
if ($normalizedRoute === '') {
    $normalizedRoute = '/';
}

$viewRoutes = [
    '/auth/login' => '../app/Views/auth/login.php',
    '/login' => '../app/Views/auth/login.php',
    '/auth/register' => '../app/Views/auth/register.php',
    '/register' => '../app/Views/auth/register.php',
    '/patient' => '../app/Views/patient/dashboard.php',
    '/patient/dashboard' => '../app/Views/patient/dashboard.php',
    '/patient/book-appointment' => '../app/Views/patient/book_appointment.php',
    '/patient/book_appointment' => '../app/Views/patient/book_appointment.php',
    '/patient/booking-history' => '../app/Views/patient/booking_history.php',
    '/patient/booking_history' => '../app/Views/patient/booking_history.php',
    '/patient/profile' => '../app/Views/patient/profile.php',
];

if (isset($viewRoutes[$normalizedRoute])) {
    require_once $viewRoutes[$normalizedRoute];
    exit;
}

$publicBaseUrl = BASE_URL . '/public';
// Landing page below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechCare - Smart Hospital Management System</title>

    <!-- Dùng Tabler Icons (miễn phí, đẹp, nhẹ) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        :root {
            --primary: #0086C4;    /* MÀU CHÍNH MỚI */
            --primary-light: #e6f4ff;
            --primary-bg: #f0f8ff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        a { text-decoration: none; }

        header {
            background: white;
            padding: 15px 50px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }
        .logo i {
            font-size: 36px;
            margin-right: 8px;
        }
        .nav-right a {
            margin-left: 30px;
            color: #333;
            font-weight: 500;
        }
        .btn-register, .btn-primary {
            background: var(--primary);
            color: white !important;
            padding: 11px 28px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-register:hover, .btn-primary:hover {
            background: #00689e;
            transform: translateY(-2px);
        }

        .hero {
            text-align: center;
            padding: 180px 20px 120px;
            background: linear-gradient(to bottom, #ffffff, var(--primary-bg));
        }
        .hero h1 { font-size: 58px; margin-bottom: 20px; }
        .hero h1 span { color: var(--primary); }
        .hero p { max-width: 720px; margin: 0 auto 40px; font-size: 18px; color: #555; }

        .features, .roles { padding: 100px 50px; text-align: center; }
        .features { background: white; }
        .roles { background: #f9fbfd; }
        .features h2, .roles h2 { font-size: 48px; margin-bottom: 20px; }
        .features > p, .roles > p { max-width: 800px; margin: 0 auto 60px; color: #666; }

        .feature-grid, .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1300px;
            margin: 0 auto;
        }
        .feature-card, .role-card {
            background: white;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,134,196,0.1);
            transition: transform 0.3s;
        }
        .feature-card:hover, .role-card:hover {
            transform: translateY(-10px);
        }
        .feature-card .icon, .role-card .icon {
            font-size: 52px;
            margin-bottom: 24px;
            color: var(--primary);
        }
        .feature-card h3, .role-card h3 {
            font-size: 24px;
            margin-bottom: 16px;
        }
        .role-card ul {
            list-style: none;
            text-align: left;
        }
        .role-card ul li {
            margin-bottom: 14px;
            padding-left: 4px;
            color: #444;
        }
        .role-card ul li:before {
            content: "✓";
            color: var(--primary);
            font-weight: bold;
            margin-right: 10px;
        }

        footer {
            background: #0f172a;
            color: #cbd5e1;
            padding: 90px 50px 40px;
        }
        .footer-content {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
        }
        .footer-logo {
            font-size: 30px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
        }
        .footer-logo i { font-size: 38px; margin-right: 10px; }
        .footer-links h4 { color: white; margin-bottom: 25px; font-size: 18px; }
        .footer-links a { display: block; color: #94a3b8; margin-bottom: 12px; transition: color 0.3s; }
        .footer-links a:hover { color: var(--primary); }
        .footer-bottom {
            margin-top: 70px;
            padding-top: 30px;
            border-top: 1px solid #1e293b;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 42px; }
            .features h2, .roles h2 { font-size: 36px; }
            header { padding: 15px 20px; }
            .footer-content { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo"><i class="ti ti-wave-saw-tool"></i> TechCare</div>
        <div class="nav-right">
            <a href="<?= $publicBaseUrl ?>/auth/login">Sign In</a>
            <a href="<?= $publicBaseUrl ?>/auth/register" class="btn-register">Register</a>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero">
        <h1>Transform Healthcare<br>with <span>Smart</span><br>Hospital Management</h1>
        <p>Streamline patient flow, optimize appointments, and enhance care quality with intelligent automation.</p>
        <a href="<?= $publicBaseUrl ?>/auth/register" class="btn-primary">Register for free →</a>
    </section>

    <!-- Features -->
    <section class="features">
        <h2>Comprehensive<br>Hospital Management</h2>
        <p>Powerful features designed to streamline healthcare delivery and improve patient outcomes.</p>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="icon"><i class="ti ti-calendar-event"></i></div>
                <h3>Smart Appointment Scheduling</h3>
                <p>AI-powered scheduling optimizes appointment slots and reduces wait times based on historical data.</p>
            </div>
            <div class="feature-card">
                <div class="icon"><i class="ti ti-file-text"></i></div>
                <h3>Electronic Medical Records</h3>
                <p>Comprehensive EMR system with ICD-10, vital signs tracking, and real-time access.</p>
            </div>
            <div class="feature-card">
                <div class="icon"><i class="ti ti-brain"></i></div>
                <h3>AI Clinical Decision Support</h3>
                <p>Intelligent recommendations for diagnosis, prescription, and department transfers.</p>
            </div>
            <div class="feature-card">
                <div class="icon"><i class="ti ti-activity"></i></div>
                <h3>Real-Time Patient Flow</h3>
                <p>Track patient journey from check-in to discharge with live monitoring and analytics.</p>
            </div>
        </div>
    </section>

    <!-- Roles -->
    <section class="roles">
        <h2>Built for Every Healthcare Role</h2>
        <p>Tailored experiences for patients, doctors, and nurses with role-specific features.</p>

        <div class="role-grid">
            <div class="role-card">
                <div class="icon"><i class="ti ti-user"></i></div>
                <h3>For Patients</h3>
                <ul>
                    <li>Book and manage appointments online</li>
                    <li>Update health information before visits</li>
                    <li>Access medical records and prescriptions</li>
                    <li>AI chatbot for health questions</li>
                    <li>Medication reminders and tracking</li>
                    <li>Recovery progress monitoring</li>
                </ul>
            </div>
            <div class="role-card">
                <div class="icon"><i class="ti ti-stethoscope"></i></div>
                <h3>For Doctors</h3>
                <ul>
                    <li>Complete EMR access and management</li>
                    <li>AI-powered diagnosis suggestions</li>
                    <li>Prescription assistance with safety checks</li>
                    <li>Patient dashboard with vital signs</li>
                    <li>Department transfer recommendations</li>
                    <li>Follow-up appointment scheduling</li>
                </ul>
            </div>
            <div class="role-card">
                <div class="icon"><i class="ti ti-nurse"></i></div>
                <h3>For Nurses</h3>
                <ul>
                    <li>Patient registration and verification</li>
                    <li>Clinic room assignment with AI</li>
                    <li>Health information data entry</li>
                    <li>Appointment management</li>
                    <li>Patient flow monitoring</li>
                    <li>Insurance verification</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div>
                <div class="footer-logo"><i class="ti ti-heart-pulse"></i> TechCare</div>
                <p class="footer-about">
                    AI-powered smart hospital management system transforming healthcare delivery in Vietnam and beyond.
                </p>
            </div>
            <div class="footer-links">
                <h4>Platform</h4>
                <a href="#">Features</a>
                <a href="#">How It Works</a>
                <a href="#">AI Features</a>
                <a href="#">For Users</a>
            </div>
            <div class="footer-links">
                <h4>Resources</h4>
                <a href="#">Documentation</a>
                <a href="#">API Reference</a>
                <a href="#">Help Center</a>
                <a href="#">Community</a>
            </div>
            <div class="footer-links">
                <h4>Company</h4>
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Careers</a>
                <a href="#">Partners</a>
            </div>
        </div>
        <div class="footer-bottom">
            © 2025 TechCare. All rights reserved. 
            <a href="#">Privacy Policy</a> • <a href="#">Terms of Service</a> • <a href="#">HIPAA Compliance</a>
        </div>
    </footer>


</body>
</html>