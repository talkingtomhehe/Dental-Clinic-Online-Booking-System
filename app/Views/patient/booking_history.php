<?php
// app/Views/patient/appointments.php
ob_start();
// session_start();

// Giả sử bạn đã có PatientID từ session hoặc xác thực người dùng
$data = [
    "task"  => "getAppointments"
];
$ch = curl_init('http://localhost/Dental-Clinic-Online-Booking-System/app/Controllers/AppointmentController.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$respond = curl_exec($ch);
if ($respond === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die("CURL Error: " . $error);
} 
curl_close($ch);
$appointments = json_decode($respond, true); 
?>

<div class="space-y-8">

    <!-- Header + Book Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-bold text-gray-900">Appointments</h2>
            <p class="text-gray-600 mt-2">Manage your hospital visits</p>
        </div>
        <a href="book-appointment.php">
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
                                <span class="<?= $appt['status'] === 'Scheduled' ? 'text-orange-500' : 
                                ($appt['status'] === 'Cancelled' ? 'text-red-500' : 'text-green-600')?> font-bold">
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
                        <?php if ($appt['status'] === 'Scheduled'): ?>
                            <a href="book-appointment.php?reschedule=<?= $appt['id'] ?>">
                                <button class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                    Reschedule
                                </button>
                            </a>
                            <button onclick="cancelAppointment(<?= $appt['id']?>,<?= $appt['schedule_id']?>)" 
                                    class="px-6 py-2.5 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
                                Cancel
                            </button>
                        <?php else: ?>
                            <a href="feedback.php?appt=<?= $appt['id'] ?>">
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
include '../layouts/patient-layout.php'; // hoặc '../patient/layout.php'
?>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function cancelAppointment(id,schedule_id) {
    if (!confirm("Cancel appointment " + id + "?")) return;
    fetch("http://localhost/Dental-Clinic-Online-Booking-System/app/Controllers/AppointmentController.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: JSON.stringify({ 
                    task: "CancelAppointment",
                    Id: id,
                    ScheduleID: schedule_id
                })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload(); // refresh page
            alert(data.message);
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(err => alert("Network error: " + err));
}
</script>