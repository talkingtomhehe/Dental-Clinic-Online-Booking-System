<?php
// app/Views/patient/profile.php
ob_start();
?>

<div class="space-y-8">

    <div>
        <h2 class="text-4xl font-bold text-gray-900">Profile Settings</h2>
        <p class="text-gray-600 mt-2">Manage your personal and insurance information</p>
    </div>

    <!-- PERSONAL INFORMATION -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <i class="ti ti-user text-primary text-2xl"></i>
                    Personal Information
                </h3>
                <div class="flex gap-3">
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <i class="ti ti-edit text-sm"></i> Edit
                    </button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                        <i class="ti ti-refresh text-sm"></i> Clear
                    </button>
                    <button class="px-5 py-2 bg-primary text-white rounded-lg hover:bg-[#00689e] flex items-center gap-2 shadow-md">
                        <i class="ti ti-device-floppy text-sm"></i> Save
                    </button>
                    <button class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                        <i class="ti ti-x text-sm"></i> Cancel
                    </button>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-6" x-data="profileForm()">
            <form class="space-y-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none" 
                           placeholder="John Doe" value="Nguyễn Văn A">
                </div>

                <!-- Date of Birth + Age + Sex -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Date of Birth -->
                    <!-- Date of Birth (Personal) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <div class="relative">
                            <button type="button" @click="openDob = !openDob" 
                                    class="w-full flex items-center justify-between px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left transition">
                                <span :class="dob ? 'text-gray-900 font-medium' : 'text-gray-500'">
                                    <span x-text="dob ? formatDate(dob) : 'dd/mm/yyyy'"></span>
                                </span>
                                <i class="ti ti-calendar-event text-gray-500"></i>
                            </button>

                            <!-- Calendar Popup -->
                            <div x-show="openDob" @click.away="openDob = false" 
                                class="absolute z-50 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 p-4">
                                <input type="date" @change="setDob($event.target.value); openDob = false" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Age (auto-calculated) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                        <input type="text" x-model="age" disabled 
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                               placeholder="Auto calculated">
                    </div>

                    <!-- Sex -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="Male" selected>Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Phone & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" 
                               placeholder="+84 901 234 567" value="+84 901 234 567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" 
                               placeholder="patient@example.com" value="patient@example.com">
                    </div>
                </div>

                <!-- National ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID / Passport</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary" 
                           placeholder="123456789" value="001202012345">
                </div>

                <hr class="my-8 border-gray-200">

                <!-- RELATIVE'S INFORMATION -->
                <h3 class="text-xl font-bold flex items-center gap-3 -mb-2">
                    <i class="ti ti-users text-primary text-2xl"></i>
                    Relative's Information
                </h3>

                <!-- Relative Name -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Relative's Name</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg" 
                           placeholder="e.g. Mother, Father" value="Nguyễn Thị B">
                </div>

                <!-- Relationship -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <option>Mother</option>
                        <option>Father</option>
                        <option>Spouse</option>
                        <option>Child</option>
                        <option>Other</option>
                    </select>
                </div>

                <!-- Relative DOB + Age + Sex -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <div class="relative">
                            <button type="button" @click="openReDob = !openReDob" 
                                    class="w-full flex items-center justify-between px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left transition">
                                <span :class="reDob ? 'text-gray-900 font-medium' : 'text-gray-500'">
                                    <span x-text="reDob ? formatDate(reDob) : 'dd/mm/yyyy'"></span>
                                </span>
                                <i class="ti ti-calendar-event text-gray-500"></i>
                            </button>

                            <div x-show="openReDob" @click.away="openReDob = false" 
                                class="absolute z-50 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 p-4">
                                <input type="date" @change="setReDob($event.target.value); openReDob = false" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                        <input type="text" x-model="reAge" disabled class="w-full px-4 py-3 bg-gray-100 rounded-lg">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option>Female</option>
                            <option selected>Male</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Relative Phone & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="+84 912 345 678">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="relative@example.com">
                    </div>
                </div>

                <!-- Relative National ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID / Passport</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg" value="001198076543">
                </div>
            </form>
        </div>
    </div>

    </div>

    <!-- INSURANCE INFORMATION -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold flex items-center gap-3">
                        <i class="ti ti-credit-card text-primary text-2xl"></i>
                        Insurance Information
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Your health insurance details</p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance ID</label>
                    <input type="text" disabled value="VN123456789" class="w-full px-4 py-3 bg-gray-100 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Provider</label>
                    <input type="text" disabled value="Vietnam Social Security" class="w-full px-4 py-3 bg-gray-100 rounded-lg">
                </div>
            </div>
            <div class="max-w-md">
                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                <input type="text" disabled value="31/12/2026" class="w-full px-4 py-3 bg-gray-100 rounded-lg">
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600 bg-amber-50 p-4 rounded-lg border border-amber-200">
                <i class="ti ti-alert-circle text-amber-600 text-2xl flex-shrink-0"></i>
                <p class="leading-relaxed">To update insurance information, please contact the hospital administration.</p>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js cho calendar + tính tuổi -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function profileForm() {
    return {
        dob: '',
        reDob: '',
        age: '',
        reAge: '',
        openDob: false,
        openReDob: false,

        formatDate(dateStr) {
            if (!dateStr) return 'dd/mm/yyyy';
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-GB');
        },

        setDob(dateStr) {
            this.dob = dateStr;
            this.age = this.calculateAge(dateStr);
        },

        setReDob(dateStr) {
            this.reDob = dateStr;
            this.reAge = this.calculateAge(dateStr);
        },

        calculateAge(dateStr) {
            if (!dateStr) return '';
            const today = new Date();
            const birthDate = new Date(dateStr);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
            return age;
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/patient-layout.php'; // hoặc '../patient/layout.php'
?>