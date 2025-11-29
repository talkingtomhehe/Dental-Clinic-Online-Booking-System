<?php
// app/Views/patient/book-appointment.php
ob_start();
?>

<div class="max-w-7xl mx-auto p-6" x-data="bookingCalendar()" x-init="resetState(); init()">

    <!-- Header -->
    <div class="mb-10">
        <h2 class="text-4xl font-bold text-gray-900">Book Appointment</h2>
        <p class="text-gray-600 mt-2">Select a date and available time slot</p>
    </div>

    <div class="grid lg:grid-cols-[500px_1fr] gap-8">

        <!-- Calendar -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-bold" x-text="currentMonthName"></h3>
                <div class="flex gap-3">
                    <button @click="prevMonth()" class="p-3 hover:bg-gray-100 rounded-xl transition">
                        <i class="ti ti-chevron-left text-xl"></i>
                    </button>
                    <button @click="nextMonth()" class="p-3 hover:bg-gray-100 rounded-xl transition">
                        <i class="ti ti-chevron-right text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="space-y-4">
                <div class="grid grid-cols-8 gap-3 text-sm font-semibold text-gray-600">
                    <div></div>
                    <div class="text-center">Sun</div><div class="text-center">Mon</div><div class="text-center">Tue</div>
                    <div class="text-center">Wed</div><div class="text-center">Thu</div><div class="text-center">Fri</div><div class="text-center">Sat</div>
                </div>

                <template x-for="(week, wIdx) in weeks" :key="wIdx">
                    <div class="grid grid-cols-8 gap-3">
                        <div class="text-xs text-gray-500 flex items-center justify-center font-medium">
                            <span x-text="getWeekNumber(week[0].date)"></span>
                        </div>
                        <template x-for="day in week" :key="day.day">
                            <button @click="selectDate(day)" :disabled="!day.isCurrentMonth"
                                :class="[
                                    'aspect-square rounded-xl text-sm font-medium transition-all flex items-center justify-center relative',
                                    !day.isCurrentMonth ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-gray-100 hover:scale-110',
                                    day.isSelected ? 'bg-[#06b6d4] text-white font-bold shadow-xl ring-4 ring-[#06b6d4]/30' : '',
                                    day.isToday ? 'ring-2 ring-orange-500 ring-offset-2' : ''
                                ]">
                                <span x-text="day.day"></span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Time Slots + Booking Button -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 flex flex-col">
            <div class="mb-8">
                <h3 class="text-2xl font-bold mb-4">
                    Available Slots 
                    <span x-show="selectedDate" class="text-[#06b6d4]">
                        - <span x-text="selectedDate"></span> <span x-text="currentMonthName.split(' ')[0]"></span>
                    </span>
                    <span x-show="!selectedDate" class="text-gray-500 italic">— Select a date first</span>
                </h3>

                <!-- Department Tabs -->
                <div class="flex gap-8 border-b-2 border-gray-200 pb-4">
                    <template x-for="dept in departments" :key="dept">
                        <button @click="selectedDepartment = dept; selectedSlot = null"
                            :class="selectedDepartment === dept ? 'border-b-4 border-[#06b6d4] text-[#06b6d4]' : 'text-gray-500 hover:text-gray-700'"
                            class="px-6 py-3 font-bold text-lg transition">
                            <span x-text="dept"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Slots List -->
            <div class="flex-1 overflow-y-auto space-y-5 pr-2 mb-8">
                <div x-show="!selectedDate" class="text-center py-20 text-gray-500">
                    <i class="ti ti-calendar-off text-8xl mb-6 text-gray-300"></i>
                    <p class="text-xl font-medium">Please select a date to view available slots</p>
                </div>

                <template x-show="selectedDate" x-for="slot in filteredSlots" :key="slot.time + slot.doctor">
                    <button @click="selectSlot(slot)"
                        :class="[
                            'w-full p-6 rounded-2xl border-2 text-left transition-all relative overflow-hidden group',
                            selectedSlot && selectedSlot.time === slot.time && selectedSlot.doctor === slot.doctor
                                ? 'border-[#06b6d4] bg-gradient-to-r from-[#06b6d4]/10 to-[#0891b2]/10 shadow-2xl ring-4 ring-[#06b6d4]/30'
                                : slot.available 
                                    ? 'border-gray-300 hover:border-[#06b6d4] hover:shadow-2xl cursor-pointer bg-white hover:bg-[#06b6d4]/5'
                                    : 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed'
                        ]"
                        :disabled="!slot.available">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-5 mb-3">
                                    <span class="text-3xl font-bold text-[#06b6d4]" x-text="slot.time"></span>
                                    <span :class="slot.available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                          class="px-5 py-2 rounded-full text-sm font-bold">
                                        <span x-text="slot.available ? 'Available' : 'Booked'"></span>
                                    </span>
                                </div>
                                <div class="font-bold text-xl text-gray-900" x-text="slot.doctor"></div>
                                <div class="text-gray-600" x-text="slot.room"></div>
                            </div>
                            <div x-show="selectedSlot && selectedSlot.time === slot.time && selectedSlot.doctor === slot.doctor"
                                 class="w-16 h-16 bg-[#06b6d4] rounded-full flex items-center justify-center shadow-xl">
                                <i class="ti ti-check text-white text-3xl"></i>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <!-- NÚT BOOKING -->
            <div x-show="selectedSlot" x-transition class="border-t-2 border-gray-200 pt-8 mt-auto">
                <button @click="showConfirm = true"
                    class="w-full bg-gradient-to-r from-[#06b6d4] to-[#0891b2] text-white font-bold text-2xl py-6 rounded-2xl 
                           hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center gap-4 shadow-2xl">
                    <i class="ti ti-calendar-check text-4xl"></i>
                    Proceed to Booking
                    <i class="ti ti-arrow-right text-4xl"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- POPUP DÙNG x-teleport → HOÀN HẢO -->
<template x-teleport="body">
    <div x-show="showConfirm" x-transition class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6"
         @click.self="showConfirm = false">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10" @click.stop>
            <div class="text-center mb-10">
                <div class="w-28 h-28 bg-[#06b6d4]/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ti ti-calendar-check text-[#06b6d4] text-6xl"></i>
                </div>
                <h3 class="text-4xl font-bold text-gray-900">Confirm Appointment</h3>
                <p class="text-gray-600 mt-4 text-lg">Please review your booking details</p>
            </div>

            <div class="bg-gradient-to-br from-[#06b6d4]/5 to-[#0891b2]/5 rounded-2xl p-8 mb-10 space-y-6 border border-[#06b6d4]/20">
                <div class="flex items-center gap-5">
                    <i class="ti ti-user text-[#06b6d4] text-3xl"></i>
                    <div><div class="text-gray-600 font-medium">Doctor</div><div class="text-2xl font-bold" x-text="selectedSlot?.doctor"></div></div>
                </div>
                <div class="flex items-center gap-5">
                    <i class="ti ti-building-hospital text-[#06b6d4] text-3xl"></i>
                    <div><div class="text-gray-600 font-medium">Department</div><div class="text-2xl font-bold" x-text="selectedSlot?.department"></div></div>
                </div>
                <div class="flex items-center gap-5">
                    <i class="ti ti-map-pin text-[#06b6d4] text-3xl"></i>
                    <div><div class="text-gray-600 font-medium">Room</div><div class="text-2xl font-bold" x-text="selectedSlot?.room"></div></div>
                </div>
                <div class="flex items-center gap-5">
                    <i class="ti ti-clock text-[#06b6d4] text-3xl"></i>
                    <div><div class="text-gray-600 font-medium">Time & Date</div><div class="text-2xl font-bold" x-text="selectedSlot?.time + ' - ' + formatSelectedDate()"></div></div>
                </div>
            </div>

            <div class="flex gap-6">
                <button @click="showConfirm = false" class="flex-1 py-5 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 font-bold text-xl transition">
                    Cancel
                </button>
                <a href="appointments.php" class="flex-1">
                    <button class="w-full py-5 bg-gradient-to-r from-[#06b6d4] to-[#0891b2] text-white rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl transition">
                        Confirm Booking
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            { time: "11:40", doctor: "Dr. Trang Thanh Nghia", department: "Cardiology", room: "Room A1-102", available: true },
            { time: "11:50", doctor: "Dr. Trang Thanh Nghia", department: "Cardiology", room: "Room A1-102", available: true },
        ],

        resetState() {
            this.selectedDate = null
            this.selectedDayObj = null
            this.selectedSlot = null
            this.showConfirm = false
        },

        init() { this.generateCalendar() },

        get currentMonthName() {
            return this.currentDate.toLocaleString('default', { month: 'long', year: 'numeric' })
        },

        get weeks() {
            const year = this.currentDate.getFullYear()
            const month = this.currentDate.getMonth()
            const firstDay = new Date(year, month, 1).getDay()
            const daysInMonth = new Date(year, month + 1, 0).getDate()
            const daysInPrev = new Date(year, month, 0).getDate()
            const days = []

            for (let i = firstDay - 1; i >= 0; i--) 
                days.push({ day: daysInPrev - i, isCurrentMonth: false, date: new Date(year, month - 1, daysInPrev - i) })
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(year, month, i)
                days.push({ 
                    day: i, isCurrentMonth: true, date, 
                    isToday: date.toDateString() === new Date().toDateString(),
                    isSelected: this.selectedDayObj && date.toDateString() === this.selectedDayObj.date.toDateString()
                })
            }
            while (days.length < 42) {
                const nextDay = days.length - daysInPrev + 1
                days.push({ day: nextDay, isCurrentMonth: false, date: new Date(year, month + 1, nextDay) })
            }
            const weeks = []
            for (let i = 0; i < days.length; i += 7) weeks.push(days.slice(i, i + 7))
            return weeks
        },

        get filteredSlots() {
            return this.timeSlots.filter(s => s.department === this.selectedDepartment)
        },

        prevMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1) },
        nextMonth() { this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1) },

        selectDate(day) {
            if (!day.isCurrentMonth) return
            this.selectedDayObj = day
            this.selectedDate = day.day
            this.selectedSlot = null
        },

        selectSlot(slot) {
            if (!slot.available) return
            this.selectedSlot = slot
        },

        getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()))
            const dayNum = d.getUTCDay() || 7
            d.setUTCDate(d.getUTCDate() + 4 - dayNum)
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1))
            return Math.ceil((((d.getTime() - yearStart.getTime()) / 86400000) + 1) / 7)
        },

        formatSelectedDate() {
            return this.selectedDayObj ? this.selectedDayObj.date.toLocaleDateString('en-GB') : ''
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/patient-layout.php';
?>