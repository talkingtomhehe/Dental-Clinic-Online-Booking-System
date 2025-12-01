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

    <!-- Header -->
    <div>
        <h2 class="text-4xl font-bold text-gray-900">Book Appointment</h2>
        <p class="text-gray-600 mt-2">Select a date and available time slot</p>
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

        <!-- Time Slots -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-2xl font-bold mb-6">
                Available Slots <span x-show="selectedDate" class="text-[#06b6d4]" x-text="' - ' + selectedDate + ' ' + currentMonthName.split(' ')[0]"></span>
                <span x-show="!selectedDate" class="text-gray-500">— Please select a date</span>
            </h3>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                <div x-show="!selectedDate" class="text-center py-12 text-gray-500">
                    <i class="ti ti-calendar-off text-6xl text-gray-300"></i>
                    <p class="mt-4">Select a date to see available time slots</p>
                </div>

                <template x-show="selectedDate" x-for="slot in filteredSlots">
                    <button
                        @click="bookSlot(slot)"
                        :disabled="!slot.available"
                        :class="slot.available ? 'hover:border-[#06b6d4] hover:shadow-lg' : 'opacity-60 cursor-not-allowed'"
                        class="w-full p-5 border-2 rounded-xl text-left transition-all flex justify-between items-center group"
                    >
                        <div>
                            <div class="flex items-center gap-4 mb-2">
                                <span class="text-2xl font-bold text-[#06b6d4]" x-text="slot.time"></span>
                                <span :class="slot.available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" 
                                      class="px-3 py-1 rounded-full text-xs font-medium">
                                    <span x-text="slot.available ? 'Available' : 'Booked'"></span>
                                </span>
                            </div>
                            <div class="font-semibold" x-text="slot.doctor"></div>
                            <div class="text-sm text-gray-500" x-text="slot.room"></div>
                        </div>
                        <div x-show="slot.available" class="w-10 h-10 bg-[#06b6d4]/10 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <i class="ti ti-chevron-right text-[#06b6d4]"></i>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Fixed Bottom Booking Button -->
    <div x-show="selectedDate && selectedSlot" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-2xl z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Your appointment</p>
                <p class="font-semibold text-lg">
                    <span x-text="selectedSlot.time"></span> 
                    with <span x-text="selectedSlot.doctor"></span>
                    <span class="text-gray-500">on</span> 
                    <span x-text="formatDate(selectedDayObj.date)"></span>
                </p>
            </div>
            <button @click="showConfirm = true" 
                    class="px-8 py-4 bg-[#06b6d4] hover:bg-[#0891b2] text-white font-bold rounded-xl text-lg shadow-lg transition transform hover:scale-105">
                Booking Appointment
            </button>
        </div>
    </div>

    <!-- Khoảng trống để không bị che -->
    <div x-show="selectedDate && selectedSlot" class="h-32"></div>

    <!-- Confirmation Modal - CHỈ HIỆN KHI CÓ selectedSlot -->
    <template x-teleport="body">
        <div x-show="showConfirm && selectedSlot" 
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4"
            x-transition
            @click.self="showConfirm = false; selectedSlot = null">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-[#06b6d4]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-check text-4xl text-[#06b6d4]"></i>
                    </div>
                    <h3 class="text-3xl font-bold">Confirmation</h3>
                    <p class="text-gray-600 mt-2">Are you sure to book this appointment?</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 space-y-3 text-left">
                    <div>• <span x-text="selectedSlot.doctor"></span></div>
                    <div>• Department of <span x-text="selectedSlot.department"></span></div>
                    <div>• <span x-text="selectedSlot.room"></span></div>
                    <div>• At <span x-text="selectedSlot.time"></span> on <span x-text="formatDate(selectedDayObj.date)"></span></div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button @click="showConfirm = false; selectedSlot = null" 
                            class="flex-1 py-3 border rounded-xl font-semibold hover:bg-gray-50">
                        Cancel
                    </button>
                    <a href="appointments.php" class="flex-1">
                        <button class="w-full py-3 bg-[#06b6d4] text-white rounded-xl font-semibold hover:bg-[#0891b2]">
                            Confirm Booking
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
            { time: "11:00", doctor: "Dr. Trang Thanh Nghia", department: "Cardiology", room: "Room A1-102", available: true },
            { time: "11:30", doctor: "Dr. Nguyen Duc Dung", department: "Orthopedics", room: "Room B1-102", available: true },
            { time: "11:30", doctor: "Dr. Nguyen Thi Van Anh", department: "Dermatology", room: "Room JA-04", available: false },
            { time: "11:30", doctor: "Dr. Tran Tien Minh", department: "Ophthalmology", room: "Room A1-102", available: true },
            { time: "11:40", doctor: "Dr. Trang Thanh Nghia", department: "Cardiology", room: "Room A1-102", available: true },
            { time: "11:50", doctor: "Dr. Trang Thanh Nghia", department: "Cardiology", room: "Room A1-102", available: true },
        ],

        init() {
            this.generateCalendar()
        },

        get currentMonthName() {
            return this.currentDate.toLocaleString('default', { month: 'long', year: 'numeric' })
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
            return this.timeSlots.filter(s => s.department === this.selectedDepartment)
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
        }


    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/patient-layout.php';
?>