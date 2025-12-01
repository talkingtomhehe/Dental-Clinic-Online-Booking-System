<?php
// app/Views/patient/dashboard.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bệnh nhân - TechCare</title>

    <!-- Tabler Icons (thay cho Lucide) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- Google Fonts: Manrope + Satoshi -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Satoshi:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
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
        h1, h2, h3, h4, .font-bold { font-family: 'Manrope', sans-serif; }
        .collapsible-content { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar + Header (Patient Layout đơn giản) -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg h-screen fixed left-0 top-0 p-6 space-y-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary">
                    <i class="ti ti-activity text-white text-2xl"></i>
                </div>
                <span class="text-2xl font-bold">TechCare</span>
            </div>
            <nav class="space-y-3">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium">
                    <i class="ti ti-layout-dashboard"></i> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-xl">
                    <i class="ti ti-calendar-event"></i> Lịch hẹn
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-xl">
                    <i class="ti ti-file-text"></i> Hồ sơ y tế
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 rounded-xl">
                    <i class="ti ti-message-circle"></i> Chat với AI
                </a>
            </nav>
            <div class="mt-auto pt-10">
                <a href="../auth/login.php" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl">
                    <i class="ti ti-logout"></i> Đăng xuất
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="ml-64 p-8 w-full">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Welcome -->
                <div>
                    <h2 class="text-4xl font-bold text-gray-900">Chào mừng, Nguyễn Văn A!</h2>
                    <p class="text-gray-600 mt-2">Tổng quan sức khỏe của bạn</p>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6 cursor-pointer" onclick="toggleSection('quick-actions')">
                        <div>
                            <h3 class="text-xl font-bold">Hành động nhanh</h3>
                            <p class="text-sm text-gray-600">Truy cập nhanh các chức năng chính</p>
                        </div>
                        <i class="ti ti-chevron-down text-xl transition-transform" id="quick-actions-icon"></i>
                    </div>
                    <div class="collapsible-content" id="quick-actions">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Card 1 -->
                            <a href="#" class="block p-6 bg-gray-50 rounded-xl hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Lịch hẹn sắp tới</h4>
                                    <i class="ti ti-calendar-event text-gray-500"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">10:00, 30/11</p>
                                <p class="text-sm text-gray-600">BS. Sarah Johnson</p>
                            </a>
                            <!-- Card 2 -->
                            <a href="#" class="block p-6 bg-gray-50 rounded-xl hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Chẩn đoán hiện tại</h4>
                                    <i class="ti ti-activity text-gray-500"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">J45.9</p>
                                <p class="text-sm text-gray-600">Hen phế quản</p>
                            </a>
                            <!-- Card 3 -->
                            <a href="#" class="block p-6 bg-gray-50 rounded-xl hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Phục hồi sau</h4>
                                    <i class="ti ti-heart text-gray-500"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">5 ngày</p>
                                <p class="text-sm text-gray-600">Dự kiến hồi phục hoàn toàn</p>
                            </a>
                            <!-- Card 4 -->
                            <a href="#" class="block p-6 bg-gray-50 rounded-xl hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Trợ lý AI</h4>
                                    <i class="ti ti-robot text-gray-500"></i>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">24/7</p>
                                <p class="text-sm text-gray-600">Luôn sẵn sàng hỗ trợ</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6 cursor-pointer" onclick="toggleSection('appointments')">
                        <div>
                            <h3 class="text-xl font-bold">Lịch hẹn sắp tới</h3>
                            <p class="text-sm text-gray-600">Các buổi khám đã lên lịch</p>
                        </div>
                        <i class="ti ti-chevron-down text-xl transition-transform" id="appointments-icon"></i>
                    </div>
                    <div class="collapsible-content space-y-4" id="appointments">
                        <div class="flex items-center justify-between p-5 border rounded-xl">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-calendar-event text-primary text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold">Khám định kỳ</p>
                                    <p class="text-sm text-gray-600">BS. Sarah Johnson - Tim mạch</p>
                                    <p class="text-sm text-gray-600">Ngày mai, 10:00 sáng</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button class="px-5 py-2 border rounded-lg hover:bg-gray-50">Đổi lịch</button>
                                <button class="px-5 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medications + Recovery -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Medications -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-bold mb-2">Thuốc đang dùng</h3>
                        <p class="text-sm text-gray-600 mb-6">Đơn thuốc hiện tại</p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 border rounded-lg">
                                <div>
                                    <p class="font-medium">Amoxicillin 500mg</p>
                                    <p class="text-sm text-gray-600">3 lần/ngày - còn 5 ngày</p>
                                </div>
                                <i class="ti ti-clock text-gray-500"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Recovery Progress -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xl font-bold mb-2">Tiến độ phục hồi</h3>
                        <p class="text-sm text-gray-600 mb-6">Dự đoán AI</p>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">Tổng thể</span>
                                    <span class="text-gray-600">75%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary h-3 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Dự kiến hồi phục hoàn toàn trong 5-7 ngày nữa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSection(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>