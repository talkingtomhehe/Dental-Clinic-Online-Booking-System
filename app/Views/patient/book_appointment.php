<?php
// app/Views/patient/book-appointment.php
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
            <div @click.prevent="canBook && openConfirmModal()" 
                :class="{ 'pointer-events-none': !canBook, 'cursor-not-allowed': !canBook }">
                <button 
                    :disabled="!canBook"
                    :class="canBook 
                        ? 'bg-[#06b6d4] hover:bg-[#0891b2] shadow-xl transform hover:scale-105 cursor-pointer' 
                        : 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-md'"
                    class="w-full py-3 px-6 text-white font-bold text-2xl rounded-2xl transition-all duration-300 flex items-center justify-center gap-3">
                    
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Confirm Booking</span>
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


            <div x-show="selectedDate" class="space-y-4 flex-1 overflow-y-auto max-h-96">
                <template x-for="slot in filteredSlots" :key="slot.time">
                    <div class="p-5 border-2 rounded-xl transition-all hover:border-[#06b6d4] hover:shadow-md cursor-pointer relative"
                        :class="slot.available ? 
                                (selectedSlot?.time === slot.time ? 'border-[#06b6d4] bg-cyan-50' : 'border-gray-200') : 
                                'border-gray-300 bg-gray-50 opacity-75 cursor-not-allowed'">

                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="text-2xl font-bold text-[#06b6d4]" x-text="slot.time"></span>

                                    <!-- Hiển thị trạng thái slot -->
                                    <span class="px-3 py-1 rounded-full text-xs font-medium"
                                        :class="slot.bookedSlots === 0 ? 'bg-green-100 text-green-700' :
                                                slot.bookedSlots === 1 ? 'bg-yellow-100 text-yellow-700' :
                                                'bg-red-100 text-red-700'">
                                        <span x-text="slot.bookedSlots === 0 ? '2 Slots Available' :
                                                    slot.bookedSlots === 1 ? '1 Slot Left' :
                                                    'Fully Booked'"></span>
                                    </span>
                                </div>
                                <div class="font-semibold text-gray-800" x-text="slot.doctor"></div>
                                <div class="text-sm text-gray-500" x-text="slot.room"></div>
                            </div>

                            <!-- Icon check khi chọn -->
                            <i x-show="selectedSlot?.time === slot.time" 
                            class="ti ti-check text-2xl text-[#06b6d4]"></i>
                        </div>

                        <!-- Nút book (chỉ hiện khi còn slot) -->
                        <div x-show="slot.available && selectedSlot?.time !== slot.time" 
                            class="mt-4 text-right">
                            <button @click="selectedSlot = slot" 
                                    class="text-sm font-medium text-[#06b6d4] hover:text-[#0891b2]">
                                Select this time →
                            </button>
                        </div>
                    </div>
                </template>
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

                <div class="flex gap-4 mt-8">
                    <button @click="showConfirmModal = false" 
                            class="flex-1 py-4 border-2 border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <a href="appointments.php" class="flex-1">
                        <button class="w-full py-4 bg-[#06b6d4] hover:bg-[#0891b2] text-white rounded-xl font-bold shadow-lg transition transform hover:scale-105">
                            Yes, Confirm Booking
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>


<script>
function bookingCalendar() {
    return {
        currentDate: new Date(2025, 9, 1),
        selectedDate: null,
        selectedDayObj: null,
        selectedSlot: null,
        showConfirm: false,
        selectedDepartment: 'Cardiology',


        departments: ["Cardiology", "Orthopedics", "Dermatology", "Ophthalmology"],

        timeSlots: [
            {
                time: "11:00",
                doctor: "Dr. Trang Thanh Nghia",
                department: "Cardiology",
                room: "Room A1-102",
                maxSlots: 2,
                bookedSlots: 0   // 0 = còn 2 chỗ, 1 = còn 1 chỗ, 2 = hết chỗ
            },
            {
                time: "12:00",
                doctor: "Dr. Trang Thanh Nghia",
                department: "Cardiology",
                room: "Room A1-102",
                maxSlots: 2,
                bookedSlots: 1   // ví dụ đã có 1 người đặt
            },
            {
                time: "14:00",
                doctor: "Dr. Nguyen Duc Dung",
                department: "Orthopedics",
                room: "Room B1-102",
                maxSlots: 2,
                bookedSlots: 2   // đã full
            },
            {
                time: "15:00",
                doctor: "Dr. Trang Thanh Nghia",
                department: "Cardiology",
                room: "Room A1-102",
                maxSlots: 2,
                bookedSlots: 0
            },
        ],

        init() {
            this.generateCalendar()
        },

        get currentMonthName() {
            return this.currentDate.toLocaleString('en-US', { month: 'long', year: 'numeric' })
        },

        get calendarDays() {
            const year = this.currentDate.getFullYear()
            const month = this.currentDate.getMonth()
            const firstDay = new Date(year, month, 1).getDay()
            const daysInMonth = new Date(year, month + 1, 0).getDate()
            const days = []

            // Previous month
            for (let i = firstDay - 1; i >= 0; i--) {
                const d = new Date(year, month - 1, new Date(year, month, 0).getDate() - i)
                days.push({ day: d.getDate(), isCurrentMonth: false, date: d })
            }
            // Current month
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(year, month, i)
                days.push({
                    day: i,
                    isCurrentMonth: true,
                    date: date,
                    isToday: date.toDateString() === new Date().toDateString(),
                    isSelected: this.selectedDayObj && date.toDateString() === this.selectedDayObj.date.toDateString()
                })
            }
            // Next month
            while (days.length < 42) {
                const next = new Date(year, month + 1, days.length - daysInMonth - firstDay + 1)
                days.push({ day: next.getDate(), isCurrentMonth: false, date: next })
            }
            return days
        },

        get filteredSlots() {
            return this.timeSlots
                .filter(s => s.department === this.selectedDepartment)
                .map(slot => ({
                    ...slot,
                    available: slot.bookedSlots < slot.maxSlots
                }))
        },

        prevMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1); this.generateCalendar() },
        nextMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1); this.generateCalendar() },

        generateCalendar() {
            // Force re-render
            this.$nextTick(() => {})
        },

        selectDate(day) {
            if (!day.isCurrentMonth) return
            this.selectedDayObj = day
            this.selectedDate = day.day
        },

        bookSlot(slot) {
            if (!slot.available || !this.selectedDate) return
            this.selectedSlot = slot
            this.showConfirm = false
        },

        formatDate(date) {
            return date ? date.toLocaleDateString('en-GB') : ''
        },

        get canBook() {
            return this.selectedDate && this.selectedSlot && this.selectedSlot.bookedSlots < this.selectedSlot.maxSlots
        },

        showConfirmModal: false,

        openConfirmModal() {
            this.showConfirmModal = true
        },
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/patient-layout.php';
?>