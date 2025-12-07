<?php
require_once dirname(__DIR__, 3) . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - TechCare</title>

    <!-- Tabler Icons (thay cho Lucide) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- Google Fonts: Manrope + Satoshi (giống Shadcn) -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Satoshi:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Satoshi', sans-serif; }
        h1, h2, h3, .font-bold { font-family: 'Manrope', sans-serif; }
        .bg-primary { background-color: #0086C4; }
        .text-primary { color: #0086C4; }
        .hover\:text-primary:hover { color: #0086C4; }
        .border-input { border-color: #e2e8f0; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex items-center justify-center gap-3 mb-8">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary">
                <i class="ti ti-activity text-white text-2xl"></i>
            </div>
            <span class="text-3xl font-bold text-gray-900">TechCare</span>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome!</h2>
                <p class="text-gray-600 mb-6">Sign in to your TechCare account</p>

                <!-- Tabs Role -->
                <form onsubmit="handleLogin(event)" class="space-y-6">
                    <div class="grid grid-cols-2 gap-2 bg-gray-100 p-1 rounded-lg">
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="role" value="patient" checked class="hidden peer">
                            <div class="flex items-center gap-2 px-4 py-3 rounded-md transition-all peer-checked:bg-white peer-checked:shadow-md w-full justify-center">
                                <i class="ti ti-user text-lg"></i>
                                <span class="text-sm font-medium">Patient</span>
                            </div>
                        </label>
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="role" value="reception" class="hidden peer">
                            <div class="flex items-center gap-2 px-4 py-3 rounded-md transition-all peer-checked:bg-white peer-checked:shadow-md w-full justify-center">
                                <i class="ti ti-stethoscope text-lg"></i>
                                <span class="text-sm font-medium">Reception</span>
                            </div>
                        </label>
                    </div>

                    <!-- Username -->
                    <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
            <input type="text" name="username" required placeholder="Enter username"
                class="w-full px-4 py-3 border border-input rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password" required placeholder="Enter password"
                    class="w-full px-4 py-3 pr-12 border border-input rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition">
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i class="ti ti-eye text-xl" id="eye-open"></i>
                    <i class="ti ti-eye-off text-xl hidden" id="eye-closed"></i>
                </button>
            </div>
        </div>

        <div class="mt-3 text-left">
            <a href="#" class="text-sm font-medium text-[#0086C4] hover:text-[#00689e] underline-offset-4 hover:underline transition">
                Forgot password?
            </a>
        </div>

        <!-- Nút Đăng nhập -->
        <button type="submit" class="w-full bg-[#0086C4] text-white font-semibold py-4 rounded-lg hover:bg-[#00689e] transition transform hover:-translate-y-0.5 shadow-lg">
            Sign in
        </button>

                <!-- Register Link -->
                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-600">Don't have account?</span>
                    <a href="<?= BASE_URL ?>/public/auth/register" class="font-medium text-primary hover:underline">Register as Patient</a>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="<?= BASE_URL ?>/public" class="text-sm text-gray-600 hover:text-gray-900 flex items-center justify-center gap-1">
                <i class="ti ti-arrow-left"></i>
                Back to main page
            </a>
        </div>
    </div>

    <script>
        async function handleLogin(event) {
            event.preventDefault();

            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const username = form.querySelector('input[name="username"]').value;
            const password = form.querySelector('input[name="password"]').value;
            const role = form.querySelector('input[name="role"]:checked').value;

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing in...';

            try {
                const response = await fetch('<?= BASE_URL ?>/public/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password,
                        role: role
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    alert('Login successful! Redirecting...');
                    // Redirect to dashboard
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    alert(data.message || 'Login failed. Please check your credentials.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Sign in';
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('An error occurred during login. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Sign in';
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>