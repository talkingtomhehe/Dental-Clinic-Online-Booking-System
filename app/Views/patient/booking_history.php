<?php
// app/Views/patient/booking_history.php
require_once dirname(__DIR__, 3) . '/config/config.php';
ob_start();

// Dữ liệu mẫu (sau này thay bằng fetch từ DB)
$appointments = [
    [
        'id' => '1',
        'title' => 'Follow Up',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '10/10/25',
        'time' => '14:30',
        'status' => 'Upcoming'
    ],
    [
        'id' => '2',
        'title' => 'General Checkup',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '2/10/25',
        'time' => '10:00',
        'status' => 'Done'
    ],
    [
        'id' => '3',
        'title' => 'General Checkup',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '1/10/25',
        'time' => '10:00',
        'status' => 'Done'
    ],
    [
        'id' => '4',
        'title' => 'General Checkup',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '27/9/25',
        'time' => '10:00',
        'status' => 'Done'
    ],
    [
        'id' => '5',
        'title' => 'General Checkup',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '21/9/25',
        'time' => '10:00',
        'status' => 'Done'
    ],
    [
        'id' => '6',
        'title' => 'General Checkup',
        'doctor' => 'Dr. Trang Thanh Nghia',
        'department' => 'Cardiology',
        'date' => '1/9/25',
        'time' => '10:00',
        'status' => 'Done'
    ]
];
?>

<div class="space-y-8">

    <!-- Header + Book Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-bold text-gray-900">Appointments</h2>
            <p class="text-gray-600 mt-2">Manage your hospital visits</p>
        </div>
        <a href="<?= BASE_URL ?>/public/patient/book-appointment">
            <button class="bg-[#06b6d4] text-white font-semibold px-8 py-2 rounded-xl text-lg shadow-lg hover:bg-[#0891b2] transition transform hover:-translate-y-0.5 flex items-center gap-3">
                <span class="text-3xl">+</span>
                Book Appointment
            </button>
        </a>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 max-w-xs">
                <i class="ti ti-calendar-event absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                <input type="text" placeholder="dd/mm/yyyy" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-[#06b6d4] outline-none">
            </div>
            <span class="text-gray-500 text-xl">—</span>
            <div class="relative flex-1 max-w-xs">
                <i class="ti ti-calendar-event absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                <input type="text" placeholder="dd/mm/yyyy" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4] focus:border-[#06b6d4] outline-none">
            </div>
            <button class="bg-[#06b6d4] text-white font-semibold px-8 py-3 rounded-lg hover:bg-[#0891b2] transition flex items-center gap-2 shadow-md">
                <i class="ti ti-search"></i>
                Search
            </button>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-5">
        <?php foreach ($appointments as $appt): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-[#06b6d4]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-calendar-event text-[#06b6d4] text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">
                                <?= htmlspecialchars($appt['title']) ?>
                                <span class="<?= $appt['status'] === 'Upcoming' ? 'text-orange-500' : 'text-green-600' ?> font-bold">
                                    • <?= $appt['status'] ?>
                                </span>
                            </h3>
                            <p class="text-gray-600 mt-1">
                                <?= htmlspecialchars($appt['doctor']) ?> - <?= htmlspecialchars($appt['department']) ?>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                <?= htmlspecialchars($appt['date']) ?> • <?= htmlspecialchars($appt['time']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <?php if ($appt['status'] === 'Upcoming'): ?>
                            <a href="<?= BASE_URL ?>/public/patient/book-appointment?reschedule=<?= urlencode($appt['id']) ?>">
                                <button class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                    Reschedule
                                </button>
                            </a>
                            <button onclick="alert('Appointment <?= $appt['id'] ?> cancelled!')" 
                                    class="px-6 py-2.5 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
                                Cancel
                            </button>
                        <?php else: ?>
                            <a href="#">
                                <button class="px-8 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                    Feedback
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Nếu không có lịch hẹn -->
    <?php if (empty($appointments)): ?>
        <div class="text-center py-16">
            <i class="ti ti-calendar-off text-6xl text-gray-300"></i>
            <p class="text-xl text-gray-600 mt-6">No appointments found</p>
            <p class="text-gray-500 mt-2">Book your first visit now!</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/patient-layout.php';
?>