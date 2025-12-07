<?php
// app/Views/patient/book_appointment.php
require_once dirname(__DIR__, 3) . '/config/config.php';
require_once dirname(__DIR__, 2) . '/Controllers/AuthenticationController.php';

AuthenticationController::requireRole('Patient');
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // QUAN TRỌNG: Reset hoàn toàn state khi load trang
        document.addEventListener('alpine:init', () => {
            window.bookingState = null;
        });
    </script>
</head>
<body>

<div class="space-y-8 max-w-7xl mx-auto p-6" x-data="bookingCalendar()" x-init="init()">

    <!-- HEADER + NÚT CONFIRM SIÊU ĐẸP, LUÔN HIỂN THỊ, TỰ DISABLE/ENABLE -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">

        <!-- Tiêu đề -->
        <div>
            <h2 class="text-4xl font-bold text-gray-900">Book Appointment</h2>
            <p class="text-gray-600 mt-2">Select a date and available time slot</p>
        </div>

        <!-- NÚT CONFIRM - LUÔN HIỂN THỊ, TỰ ĐỘNG DISABLE/ENABLE -->
        <div class="lg:min-w-[380px]">
            <div @click.prevent="canBook && !isSubmitting && openConfirmModal()" 
                :class="{ 'pointer-events-none': !canBook || isSubmitting, 'cursor-not-allowed': !canBook || isSubmitting }">
                <button 
                    :disabled="!canBook || isSubmitting"
                    :class="canBook && !isSubmitting
                        ? 'bg-[#06b6d4] hover:bg-[#0891b2] shadow-xl transform hover:scale-105 cursor-pointer' 
                        : 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-md'"
                    class="w-full py-3 px-6 text-white font-bold text-2xl rounded-2xl transition-all duration-300 flex items-center justify-center gap-3">
                    
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="isSubmitting ? 'Processing...' : 'Confirm Booking'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid lg:grid-cols-[500px_1fr] gap-8">

        <!-- Calendar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold" x-text="currentMonthName"></h3>
                <div class="flex gap-2">
                    <button @click="prevMonth()" class="p-3 hover:bg-gray-100 rounded-lg"><i class="ti ti-chevron-left"></i></button>
                    <button @click="nextMonth()" class="p-3 hover:bg-gray-100 rounded-lg"><i class="ti ti-chevron-right"></i></button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-600 mb-3">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>
            <div class="grid grid-cols-7 gap-1">
                <template x-for="day in calendarDays" :key="day.date">
                    <button
                        @click="selectDate(day)"
                        :disabled="!day.isCurrentMonth"
                        :class="{
                            'bg-[#06b6d4] text-white font-bold': day.isSelected,
                            'text-gray-400': !day.isCurrentMonth,
                            'hover:bg-gray-100': day.isCurrentMonth && !day.isSelected,
                            'ring-2 ring-[#06b6d4]': day.isToday
                        }"
                        class="aspect-square rounded-lg flex items-center justify-center text-sm transition"
                    >
                        <span x-text="day.day"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Time Slots + Nút Book ngay trên đầu -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">
                    Available Slots 
                    <span x-show="selectedDate" class="text-[#06b6d4]" x-text="' - ' + selectedDate + ' ' + currentMonthName.split(' ')[0]"></span>
                    <span x-show="!selectedDate" class="text-gray-500">— Please select a date</span>
                </h3>
            </div>

            <div x-show="!selectedDate" class="text-center py-20 text-gray-500 flex-1 flex items-center justify-center">
                <div>
                    <i class="ti ti-calendar-off text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg">Please select a date</p>
                </div>
            </div>


            <div x-show="selectedDate" class="flex-1 flex flex-col">
                <div x-show="slotsLoading" class="flex-1 flex items-center justify-center py-12 text-gray-500">
                    <div class="flex flex-col items-center gap-3">
                        <span class="h-10 w-10 border-4 border-gray-200 border-t-[#06b6d4] rounded-full animate-spin inline-block"></span>
                        <span class="text-sm">Loading available slots...</span>
                    </div>
                </div>

                <div x-show="!slotsLoading && slotsError" class="flex-1 flex items-center justify-center">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm text-center max-w-md">
                        <p x-text="slotsError"></p>
                    </div>
                </div>

                <div x-show="!slotsLoading && !slotsError && filteredSlots.length === 0" class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center space-y-2">
                        <i class="ti ti-calendar-x text-4xl text-gray-300"></i>
                        <p>No available slots on this date. Please choose another day.</p>
                    </div>
                </div>

                <div x-show="!slotsLoading && !slotsError && filteredSlots.length > 0" class="space-y-4 overflow-y-auto max-h-96">
                    <template x-for="slot in filteredSlots" :key="slot.schedule_id">
                        <div class="p-5 border-2 rounded-xl transition-all hover:border-[#06b6d4] hover:shadow-md cursor-pointer relative"
                            :class="slot.available ? 
                                    (selectedSlot?.schedule_id === slot.schedule_id ? 'border-[#06b6d4] bg-cyan-50' : 'border-gray-200') : 
                                    'border-gray-300 bg-gray-50 opacity-75 cursor-not-allowed'">

                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-2">
                                        <span class="text-2xl font-bold text-[#06b6d4]" x-text="slot.time"></span>

                                        <span class="px-3 py-1 rounded-full text-xs font-medium"
                                            :class="slot.availableSlots <= 0 ? 'bg-red-100 text-red-700' :
                                                    (slot.availableSlots === 1 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')">
                                            <span x-text="slot.availableSlots <= 0
                                                ? 'Fully Booked'
                                                : (slot.availableSlots === 1
                                                    ? '1 Slot Left'
                                                    : slot.availableSlots + ' Slots Available')"></span>
                                        </span>
                                    </div>
                                    <div class="font-semibold text-gray-800" x-text="slot.doctor"></div>
                                    <div class="text-sm text-gray-500" x-text="slot.room"></div>
                                </div>

                                <i x-show="selectedSlot?.schedule_id === slot.schedule_id" 
                                   class="ti ti-check text-2xl text-[#06b6d4]"></i>
                            </div>

                            <div x-show="slot.available && selectedSlot?.schedule_id !== slot.schedule_id" 
                                class="mt-4 text-right">
                                <button type="button" @click="bookSlot(slot)" 
                                        class="text-sm font-medium text-[#06b6d4] hover:text-[#0891b2]">
                                    Select this time →
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>


    <!-- CONFIRMATION MODAL - HIỆN KHI BẤM CONFIRM BOOKING -->
    <template x-teleport="body">
        <div x-show="showConfirmModal" 
            x-transition
            @keydown.escape.window="showConfirmModal = false"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            
            <div @click.stop class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 animate-in fade-in zoom-in duration-200">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-[#06b6d4]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-calendar-check text-4xl text-[#06b6d4]"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900">Confirm Appointment</h3>
                    <p class="text-gray-600 mt-2">Please review your appointment details</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 space-y-4 text-left">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Doctor:</span>
                        <span class="font-semibold" x-text="selectedSlot.doctor"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-semibold" x-text="selectedSlot.time + ' on ' + formatDate(selectedDayObj.date)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Room:</span>
                        <span class="font-semibold" x-text="selectedSlot.room"></span>
                    </div>
                </div>

                <div x-show="submitError" class="mt-4 text-sm text-red-600 text-center" x-text="submitError"></div>

                <div class="flex gap-4 mt-8">
                    <button @click="showConfirmModal = false" 
                            class="flex-1 py-4 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="button"
                            @click="submitSlot"
                            :disabled="isSubmitting"
                            class="flex-1 py-4 bg-[#06b6d4] hover:bg-[#0891b2] disabled:opacity-60 disabled:cursor-not-allowed text-white rounded-xl font-bold shadow-lg transition transform hover:scale-105">
                        <span x-text="isSubmitting ? 'Booking...' : 'Yes, Confirm Booking'"></span>
                </div>
            </div>
        </div>
    </template>
</div>


<script>
function bookingCalendar() {
    return {
        currentDate: new Date(),
        selectedDate: null,
        selectedDayObj: null,
        selectedSlot: null,
        timeSlots: [],
        slotsLoading: false,
        slotsError: '',
        showConfirmModal: false,
        isSubmitting: false,
        submitError: '',

        init() {
            this.generateCalendar();
        },

        get currentMonthName() {
            return this.currentDate.toLocaleString('en-US', { month: 'long', year: 'numeric' });
        },

        get calendarDays() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            const days = [];

            for (let i = firstDay - 1; i >= 0; i--) {
                const d = new Date(year, month - 1, new Date(year, month, 0).getDate() - i);
                days.push({ day: d.getDate(), isCurrentMonth: false, date: d });
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(year, month, i);
                days.push({
                    day: i,
                    isCurrentMonth: true,
                    date,
                    isToday: date.toDateString() === today.toDateString(),
                    isSelected: this.selectedDayObj && date.toDateString() === this.selectedDayObj.date.toDateString()
                });
            }

            while (days.length < 42) {
                const next = new Date(year, month + 1, days.length - daysInMonth - firstDay + 1);
                days.push({ day: next.getDate(), isCurrentMonth: false, date: next });
            }

            return days;
        },

        get filteredSlots() {
            return this.timeSlots;
        },

        prevMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
            this.generateCalendar();
        },

        nextMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
            this.generateCalendar();
        },

        generateCalendar() {
            this.$nextTick(() => {});
        },

        async fetchSlotsForDate(dateObj) {
            const formattedDate = dateObj.toLocaleDateString('en-CA');
            this.slotsLoading = true;
            this.slotsError = '';
            this.timeSlots = [];

            try {
                const response = await fetch('<?= BASE_URL ?>/public/api/appointments/slots?date=' + encodeURIComponent(formattedDate));

                if (response.status === 401) {
                    window.location.href = '<?= BASE_URL ?>/public/auth/login';
                    return;
                }

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to load available slots.');
                }

                this.timeSlots = Array.isArray(data.data) ? data.data : [];
            } catch (error) {
                console.error('Fetch slots error:', error);
                this.slotsError = error.message || 'Unable to load available slots. Please try again later.';
            } finally {
                this.slotsLoading = false;
            }
        },

        selectDate(day) {
            if (!day.isCurrentMonth) return;

            this.selectedDayObj = day;
            this.selectedDate = day.day;
            this.selectedSlot = null;
            this.fetchSlotsForDate(day.date);
        },

        bookSlot(slot) {
            if (!slot.available) return;
            this.selectedSlot = slot;
        },

        async submitSlot() {
            if (!this.canBook || this.isSubmitting) return;

            this.isSubmitting = true;
            this.submitError = '';

            try {
                const response = await fetch('<?= BASE_URL ?>/public/api/appointments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        schedule_id: this.selectedSlot.schedule_id,
                    }),
                });

                if (response.status === 401) {
                    window.location.href = '<?= BASE_URL ?>/public/auth/login';
                    return;
                }

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to book appointment.');
                }

                this.showConfirmModal = false;
                alert(data.message || 'Appointment booked successfully.');
                window.location.href = '<?= BASE_URL ?>/public/patient/booking-history';
            } catch (error) {
                console.error('Book appointment error:', error);
                this.submitError = error.message || 'Unable to book appointment. Please try again.';
            } finally {
                this.isSubmitting = false;
            }
        },

        formatDate(date) {
            return date ? date.toLocaleDateString('en-GB') : '';
        },

        get canBook() {
            return Boolean(this.selectedSlot && this.selectedSlot.available);
        },

        openConfirmModal() {
            if (!this.canBook) return;
            this.submitError = '';
            this.showConfirmModal = true;
        },
    }
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/patient-layout.php';
?>