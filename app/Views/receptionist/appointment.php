<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body { background: #f7f8fa; }
    </style>
</head>

<body>

<div class="space-y-8 max-w-7xl mx-auto p-6"
     x-data="appointmentBooking()"
     x-init="init()">

    <!-- HEADER -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
        <div>
            <h2 class="text-4xl font-bold text-gray-900">Appointment</h2>
            <p class="text-gray-600 mt-2">Manage Appointment</p>
        </div>
    </div>


    <!-- MAIN GRID -->
    <div class="grid lg:grid-cols-[500px_1fr] gap-8">

        <!-- CALENDAR -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold" x-text="currentMonthName"></h3>

                <div class="flex gap-2">
                    <button @click="prevMonth()" class="p-3 hover:bg-gray-100 rounded-lg">
                        <i class="ti ti-chevron-left"></i>
                    </button>
                    <button @click="nextMonth()" class="p-3 hover:bg-gray-100 rounded-lg">
                        <i class="ti ti-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- DAYS HEADER -->
            <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-600 mb-3">
                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div>
                <div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <!-- DAYS -->
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


        <!-- SLOTS -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">
                    Available Slots
                    <span x-show="selectedDate"
                          class="text-[#06b6d4]"
                          x-text="' - ' + selectedDate + ' ' + currentMonthName.split(' ')[0]">
                    </span>

                    <span x-show="!selectedDate" class="text-gray-500">— Please select a date</span>
                </h3>
            </div>


            <!-- NO DATE -->
            <div x-show="!selectedDate"
                 class="text-center py-20 text-gray-500 flex-1 flex items-center justify-center">
                <div>
                    <i class="ti ti-calendar-off text-6xl text-gray-300 mb-4"></i>
                    <p class="text-lg">Please select a date</p>
                </div>
            </div>


            <!-- SLOT LIST -->
            <div x-show="selectedDate"
                 class="space-y-4 flex-1 overflow-y-auto max-h-96">

                <template x-for="slot in filteredSlots" :key="slot.time">
                    <div class="p-5 border-2 rounded-xl transition-all hover:border-[#06b6d4] hover:shadow-md relative">

                        <div class="flex justify-between items-center">

                            <div>
                                <div class="text-2xl font-bold text-[#06b6d4]"
                                     x-text="slot.time"></div>

                                <!-- EMPTY -->
                                <template x-if="slot.isEmptySlot">
                                    <div class="mt-2 text-gray-500 text-sm">
                                        No appointment — Available
                                    </div>
                                </template>

                                <!-- FILLED -->
                                <template x-if="!slot.isEmptySlot">
                                    <div class="mt-2 space-y-1">
                                        <div class="font-semibold text-gray-800"
                                             x-text="slot.doctor"></div>
                                        <div class="text-sm text-gray-500"
                                             x-text="slot.room"></div>
                                        <div class="text-sm text-gray-600">
                                            Patient: Nguyen Van A
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- CHECK ICON -->
                            <i x-show="selectedSlot && selectedSlot.time === slot.time"
                               class="ti ti-check text-2xl text-[#06b6d4]"></i>
                        </div>


                        <!-- BUTTON AREA -->
                        <div class="mt-4 flex justify-end">

                            <!-- CREATE -->
                            <button
                                x-show="slot.isEmptySlot"
                                @click="selectSlot(slot); openCreateModal(slot)"
                                class="px-4 py-2 bg-[#06b6d4] text-white rounded-lg hover:bg-[#0891b2] text-sm font-semibold">
                                Create Appointment
                            </button>

                            <!-- CANCEL -->
                            <button
                                x-show="!slot.isEmptySlot"
                                @click="selectSlot(slot); openCancelModal(slot)"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-semibold">
                                Cancel
                            </button>

                        </div>

                    </div>
                </template>

            </div>
        </div>

    </div>
</div>



<script>
function appointmentBooking() {
    return {
        currentDate: new Date(2025, 9, 1),
        selectedDate: null,
        selectedDayObj: null,
        currentMonthName: "",
        selectedSlot: null,

        // SAMPLE DATA
        slots: [
            { time: "07:00", isEmptySlot: true, doctor: "", room: "" },
            { time: "08:00", isEmptySlot: true, doctor: "", room: "" },
            { time: "09:00", isEmptySlot: false, doctor: "Dr. Smith", room: "Room 1" },
            { time: "10:00", isEmptySlot: true, doctor: "", room: "" },
            { time: "11:00", isEmptySlot: false, doctor: "Dr. Anna", room: "Room 2" },
        ],

        init() {
            this.updateMonthName();
        },

        updateMonthName() {
            this.currentMonthName = this.currentDate.toLocaleString('en-US', {
                month: 'long',
                year: 'numeric'
            });
        },

        get filteredSlots() {
            return this.slots; // sau này bạn lọc theo ngày
        },

        selectSlot(slot) {
            this.selectedSlot = slot;
        },

        prevMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1);
            this.updateMonthName();
        },

        nextMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1);
            this.updateMonthName();
        },

        selectDate(day) {
            if (!day.isCurrentMonth) return;
            this.selectedDayObj = day;
            this.selectedDate = day.day;
        },

        openCreateModal(slot) {
            alert("Create Appointment at " + slot.time);
        },

        openCancelModal(slot) {
            alert("Cancel Appointment at " + slot.time);
        },

        // CALENDAR DAYS
        get calendarDays() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const days = [];

            // previous month padding
            for (let i = firstDay - 1; i >= 0; i--) {
                const d = new Date(year, month - 1, new Date(year, month, 0).getDate() - i);
                days.push({
                    day: d.getDate(),
                    date: d,
                    isCurrentMonth: false
                });
            }

            // current month days
            for (let i = 1; i <= daysInMonth; i++) {
                const d = new Date(year, month, i);
                days.push({
                    day: i,
                    date: d,
                    isCurrentMonth: true,
                    isToday: d.toDateString() === new Date().toDateString(),
                    isSelected: this.selectedDayObj && d.toDateString() === this.selectedDayObj.date.toDateString()
                });
            }

            // next month padding
            while (days.length < 42) {
                const d = new Date(year, month + 1, days.length - daysInMonth - firstDay + 1);
                days.push({
                    day: d.getDate(),
                    date: d,
                    isCurrentMonth: false
                });
            }

            return days;
        }
    }
}
</script>

</body>
</html>

<?php
$content = ob_get_clean();
include '../layouts/reception-layout.php';
?>
