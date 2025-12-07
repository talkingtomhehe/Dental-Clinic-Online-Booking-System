<?php
// app/Views/patient/booking_history.php
require_once dirname(__DIR__, 3) . '/config/config.php';
require_once dirname(__DIR__, 2) . '/Controllers/AuthenticationController.php';
require_once dirname(__DIR__, 2) . '/Models/Appointment.php';

AuthenticationController::requireRole('Patient');

$auth = new AuthenticationController();
$currentUser = $auth->getCurrentUser();

$appointments = [];
if ($currentUser && !empty($currentUser['patient_id'])) {
    $appointmentRepository = new Appointment();
    $appointments = $appointmentRepository->getAppointmentsForPatient((int) $currentUser['patient_id']);
}

ob_start();

$statusClasses = [
    'Scheduled' => 'text-orange-500',
    'Completed' => 'text-green-600',
    'Cancelled' => 'text-red-500',
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
        <?php foreach ($appointments as $appt): 
            $appointmentDate = $appt['date'] ? (new DateTime($appt['date']))->format('d/m/Y') : '';
            $appointmentTime = $appt['time'] ?? '';
            $appointmentTitle = $appt['service'] ? htmlspecialchars($appt['service']) : 'Dental Appointment';
            $status = $appt['status'] ?? 'Scheduled';
            $statusClass = $statusClasses[$status] ?? 'text-gray-500';
        ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-[#06b6d4]/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-calendar-event text-[#06b6d4] text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold">
                                <?= $appointmentTitle ?>
                                <span class="<?= $statusClass ?> font-bold">
                                    • <?= htmlspecialchars($status) ?>
                                </span>
                            </h3>
                            <p class="text-gray-600 mt-1">
                                <?= htmlspecialchars($appt['doctor'] ?? 'Assigned Doctor') ?>
                                <?php if (!empty($appt['department'])): ?>
                                    <span class="text-gray-400">• <?= htmlspecialchars($appt['department']) ?></span>
                                <?php endif; ?>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                <?= htmlspecialchars($appointmentDate) ?> • <?= htmlspecialchars($appointmentTime) ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <?php if ($status === 'Scheduled'): ?>
                            <a href="<?= BASE_URL ?>/public/patient/book-appointment?reschedule=<?= urlencode($appt['id']) ?>">
                                <button class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                    Reschedule
                                </button>
                            </a>
                            <button type="button"
                                    data-action="cancel"
                                    data-appointment-id="<?= htmlspecialchars($appt['id']) ?>"
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-action="cancel"]').forEach(button => {
        button.addEventListener('click', async () => {
            const appointmentId = button.dataset.appointmentId;
            if (!appointmentId) {
                return;
            }

            if (!confirm('Are you sure you want to cancel this appointment?')) {
                return;
            }

            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Cancelling...';

            try {
                const response = await fetch('<?= BASE_URL ?>/public/api/appointments/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ appointment_id: parseInt(appointmentId, 10) })
                });

                if (response.status === 401) {
                    window.location.href = '<?= BASE_URL ?>/public/auth/login';
                    return;
                }

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to cancel appointment.');
                }

                alert(data.message || 'Appointment cancelled successfully.');
                window.location.reload();
            } catch (error) {
                alert(error.message || 'Unable to cancel appointment. Please try again.');
                button.disabled = false;
                button.textContent = originalText;
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/patient-layout.php';
?>
