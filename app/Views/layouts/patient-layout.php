<?php
// app/Views/patient/layout.php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../Controllers/AuthenticationController.php';

// Require authentication and patient role
AuthenticationController::requireRole('Patient');

// Get current user session data
$auth = new AuthenticationController();
$currentUser = $auth->getCurrentUser();

// Determine current route relative to public base
$basePath = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
$publicPath = $basePath . '/public';
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentRoute = '/' . ltrim(str_replace($publicPath, '', $requestPath), '/');
$currentRoute = rtrim($currentRoute, '/');
if ($currentRoute === '') {
    $currentRoute = '/';
}
if ($currentRoute === '/patient') {
    $currentRoute = '/patient/dashboard';
}
if ($currentRoute === '/patient/book_appointment') {
    $currentRoute = '/patient/book-appointment';
}
if ($currentRoute === '/patient/booking_history') {
    $currentRoute = '/patient/booking-history';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal - TechCare</title>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Satoshi:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Satoshi', 'sans-serif'],
                        heading: ['Manrope', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0086C4',
                    }
                }
            }
        }
    </script>
    <style>
        h1,h2,h3,h4,.font-bold { font-family: 'Manrope', sans-serif; }
        body { font-family: 'Satoshi', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 backdrop-blur supports-backdrop-filter:bg-white/95">
        <div class="flex items-center justify-between h-16 px-6">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary">
                    <i class="ti ti-activity text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900">TechCare</span>
            </div>

            <!-- Logout Button -->
            <button onclick="handleLogout()" class="flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                <i class="ti ti-logout"></i>
                Sign out
            </button>
        </div>
    </header>

    <div class="flex pt-16 min-h-screen">
        <!-- Sidebar (Desktop) -->
        <aside class="hidden md:block w-64 bg-gray-100 border-r border-gray-200 fixed left-0 top-16 bottom-0 overflow-y-auto">
            <nav class="p-5 space-y-2">
                <!-- Tiêu đề Portal -->
                <div class="mb-6 px-4 py-3 bg-primary/10 text-primary rounded-lg font-bold text-center border border-primary/20">
                    Patient Portal
                </div>

                <!-- Menu Items -->
                <?php
                $menu = [
                    ['name' => 'Dashboard', 'path' => '/patient/dashboard', 'icon' => 'ti ti-layout-dashboard'],
                    ['name' => 'Appointment', 'path' => '/patient/book-appointment', 'icon' => 'ti ti-calendar-event'],
                    ['name' => 'Booking History', 'path' => '/patient/booking-history', 'icon' => 'ti ti-message-circle'],
                    ['name' => 'Profile', 'path' => '/patient/profile', 'icon' => 'ti ti-user-circle'],
                ];

                foreach ($menu as $item):
                    $isActive = ($currentRoute === rtrim($item['path'], '/'));
                ?>
                    <a href="<?= BASE_URL ?>/public<?= $item['path'] ?>"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $isActive ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-200' ?>">
                        <i class="<?= $item['icon'] ?> text-lg"></i>
                        <?= $item['name'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Mobile Bottom Navigation (ẩn trên desktop) -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
            <div class="grid grid-cols-4 gap-1 p-2">
                <?php foreach (array_slice($menu, 0, 4) as $item):
                    $isActive = ($currentRoute === rtrim($item['path'], '/'));
                ?>
                    <a href="<?= BASE_URL ?>/public<?= $item['path'] ?>" class="flex flex-col items-center py-2 <?= $isActive ? 'text-primary' : 'text-gray-500' ?>">
                        <i class="<?= $item['icon'] ?> text-xl"></i>
                        <span class="text-xs mt-1"><?= explode(' ', $item['name'])[0] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 md:ml-64 pb-20 md:pb-8">
            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                <!-- NỘI DUNG TRANG CON SẼ ĐƯỢC INCLUDE TẠI ĐÂY -->
                <?= $content ?? '' ?>
                <!-- Ví dụ: <?php include 'dashboard-content.php'; ?> -->
            </div>
        </main>
    </div>

    <script>
        async function handleLogout() {
            if (!confirm('Are you sure you want to logout?')) {
                return;
            }

            try {
                const response = await fetch('<?= BASE_URL ?>/public/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('Logout failed. Please try again.');
                }
            } catch (error) {
                console.error('Logout error:', error);
                // Even if there's an error, redirect to login
                window.location.href = '<?= BASE_URL ?>/public/auth/login';
            }
        }
    </script>

</body>
</html>