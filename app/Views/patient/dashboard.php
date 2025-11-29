<?php
// app/Views/patient/dashboard.php
ob_start();
?>

<div class="space-y-8">

    <!-- Welcome -->
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Welcome, Nguyen Van A!</h2>
        <p class="text-gray-600 mt-2">Here's your overview dashboard</p>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="p-6" id="quick-actions">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Lịch hẹn sắp tới -->
                <a href="appointments.php" class="block bg-gray-50 rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">Upcoming appointment</span>
                        <i class="ti ti-calendar-event text-gray-500 text-lg"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">10:00 AM</div>
                    <p class="text-sm text-gray-600 mt-1">30/11/2025 - BS. Sarah Johnson</p>
                </a>

                <!-- Chẩn đoán hiện tại -->
                <a href="records.php" class="block bg-gray-50 rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">Current Diagnosis</span>
                        <i class="ti ti-activity text-gray-500 text-lg"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">J45.9</div>
                    <p class="text-sm text-gray-600 mt-1">Pneumonia caused by respiratory syncytial virus (RSV)</p>
                </a>

                <!-- Phục hồi sau -->
                <a href="records.php" class="block bg-gray-50 rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">Recovery prediction</span>
                        <i class="ti ti-heart text-gray-500 text-lg"></i>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">5 days</div>
                    <p class="text-sm text-gray-600 mt-1">Completely recovered</p>
                </a>

                <!-- AI Assistant -->
                <a href="chatbot.php" class="block bg-gray-50 rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-700">AI Chatbot</span>
                        <i class="ti ti-robot text-gray-500 text-lg"></i>
                    </div>
                    <div class="text-3xl font-bold text-primary">24/7</div>
                    <p class="text-sm text-gray-600 mt-1">Alway available</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition" onclick="toggleSection('appointments')">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Appointments</h3>
                <p class="text-sm text-gray-600"></p>
            </div>
            <i class="ti ti-chevron-down text-xl text-gray-500 transition-transform" id="appointments-icon"></i>
        </div>
        <div class="p-6 space-y-5" id="appointments">
            <!-- Appointment 1 -->
            <div class="flex items-center justify-between p-5 border rounded-xl hover:shadow-sm transition">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
                        <i class="ti ti-calendar-event text-primary text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-lg">General check-up</p>
                        <p class="text-gray-600">BS. Sarah Johnson - Cardiology</p>
                        <p class="text-sm text-gray-500">tomorow, 10:00 AM</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button class="px-6 py-2.5 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">Cancel</button>
                </div>
            </div>

            <!-- Appointment 2 -->
            <div class="flex items-center justify-between p-5 border rounded-xl hover:shadow-sm transition">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="ti ti-calendar-event text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-lg">Initial check-up</p>
                        <p class="text-gray-600">BS. Michael Chen - Cardiology</p>
                        <p class="text-sm text-gray-500">20/12/2025, 2:30 PM</p>
                    </div>
                </div>
                <button class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Feedback</button>
            </div>

            <a href="appointments.php" class="block w-full mt-4">
                <button class="w-full py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition font-medium">
                    View all appointments →
                </button>
            </a>
        </div>
    </div>

    <!-- Medications + Recovery Progress -->
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Active Medications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition" onclick="toggleSection('medications')">
                <div class="flex items-center gap-3">
                    <i class="ti ti-pill text-primary text-xl"></i>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Current prescription</h3>
                    </div>
                </div>
                <i class="ti ti-chevron-down text-xl text-gray-500 transition-transform" id="medications-icon"></i>
            </div>
            <div class="p-6 space-y-4" id="medications">
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div>
                        <p class="font-semibold">Amoxicillin 500mg</p>
                        <p class="text-sm text-gray-600">3 times/day - 5 days left</p>
                    </div>
                    <i class="ti ti-clock text-gray-500"></i>
                </div>
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div>
                        <p class="font-semibold">Vitamin D3 1000 IU</p>
                        <p class="text-sm text-gray-600">1 times/day - Longtime</p>
                    </div>
                    <i class="ti ti-clock text-gray-500"></i>
                </div>
            </div>
        </div>

        <!-- Recovery Progress -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition" onclick="toggleSection('recovery')">
                <div class="flex items-center gap-3">
                    <i class="ti ti-trending-up text-primary text-xl"></i>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Recovery progress</h3>
                        <p class="text-sm text-gray-600">Predict by AI</p>
                    </div>
                </div>
                <i class="ti ti-chevron-down text-xl text-gray-500 transition-transform" id="recovery-icon"></i>
            </div>
            <div class="p-6" id="recovery">
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Overall Recovery</span>
                            <span class="text-gray-600 font-medium">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: 75%"></div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Based on your current treatment, you're expected to fully <strong>recover in approximately 1-2 days.</strong>.
                    </p>
                    <button class="w-full py-3 border border-gray-300 rounded-xl hover:bg-gray-50 transition font-medium">
                        Details
                    </button>
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

    // Mở mặc định tất cả section
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[id$="-icon"]').forEach(icon => {
            icon.classList.remove('rotate-180');
        });
    });
</script>

<?php
$content = ob_get_clean();
include '../layouts/patient-layout.php'; // hoặc include '../patient/layout.php'
?>