<?php ob_start(); ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Patient Management</h2>
        <div class="flex gap-3">
            <button class="px-5 py-3 bg-[#06b6d4] hover:bg-[#0891b2] text-white font-semibold rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Patient
            </button>
            <button class="px-5 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Clear
            </button>
            <button class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                Save
            </button>
            <button class="px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6" x-data="patientManager()" x-init="init()">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-[820px]">
                
                <!-- BỎ TOÀN BỘ KHOẢNG TRẮNG DƯỚI DÒNG CUỐI -->
                <div class="flex-1 overflow-auto custom-scrollbar min-h-0">
                    <table class="w-full min-w-[1100px] table-fixed">
                        <!-- TIÊU ĐỀ BẢNG -->
                        <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-16">No.</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">National ID</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-56">Academic Title</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-24">Speciality</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-24">Sex</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-32">DOB</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap w-40">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                            </tr>
                        </thead>

                        <!-- Ô TÌM KIẾM -->
                        <thead class="bg-gray-100 sticky top-[61px] z-10 border-b border-gray-300">
                            <tr>
                                <th class="px-2 py-3"><label placeholder="Search" readonly class="w-full px-3 py-1.5 text-xs bg-transparent" ></label>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.nationalId" @input="filterPatients" placeholder="Search ID..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.name" @input="filterPatients" placeholder="Name..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.academic_title" @input="filterPatients" placeholder="Academic Title..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.speciality" @input="filterPatients" placeholder="Speciality..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <select x-model="filters.sex" @change="filterPatients"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                        <option value="">All</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.dob" @input="filterPatients" placeholder="dd/mm/yyyy"
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.phone" @input="filterPatients" placeholder="Phone..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                                <th class="px-2 py-3">
                                    <input type="text" x-model="filters.email" @input="filterPatients" placeholder="Email..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#06b6d4]">
                                </th>
                            </tr>
                        </thead>

                        <!-- DỮ LIỆU - KHÔNG CÒN KHOẢNG TRẮNG DƯỚI DÒNG CUỐI -->
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(patient, index) in paginatedPatients" :key="patient.id">
                                <tr @click="selectPatient(patient)"
                                    :class="selectedPatient?.id === patient.id ? 'bg-cyan-100 border-l-4 border-[#06b6d4]' : 'hover:bg-gray-50 cursor-pointer'"
                                    class="transition h-16"> <!-- Đảm bảo mỗi dòng có chiều cao cố định -->
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="(currentPage - 1) * pageSize + index + 1"></td>
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" x-text="patient.nationalId"></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap" x-text="patient.name"></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap w-56" x-text="patient.academic_title"></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap" x-text="patient.speciality"></td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap" x-text="patient.sex || '-'"></td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap" x-text="patient.dob || '-'"></td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap" x-text="patient.phone || '-'"></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap truncate max-w-[220px]" :title="patient.email" x-text="patient.email || '-'"></td>
                                </tr>
                            </template>

                            <!-- Khi không có dữ liệu -->
                            <tr x-show="paginatedPatients.length === 0" class="h-full">
                                <td colspan="8" class="text-center py-32 text-gray-500 text-lg">No patients found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PHÂN TRANG -->
                <div class="flex-none px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-sm">
                    <div class="text-gray-700">
                        Show <span x-text="(currentPage - 1) * pageSize + 1"></span> - 
                        <span x-text="Math.min(currentPage * pageSize, filteredPatients.length)"></span> 
                        of total <span x-text="filteredPatients.length"></span> patient
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            Previous
                        </button>
                        <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
                            <button @click="currentPage = page"
                                :class="currentPage === page ? 'bg-[#06b6d4] text-white' : 'bg-white hover:bg-gray-100'"
                                class="w-10 h-10 rounded-lg border border-gray-300 font-medium transition">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Thanh cuộn duy nhất – đẹp, mượt, màu xanh */
            .custom-scrollbar::-webkit-scrollbar {
                width: 12px;
                height: 12px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #06b6d4;
                border-radius: 10px;
                border: 2px solid white;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #0891b2;
            }

            /* Đảm bảo tiêu đề và ô search dính khi cuộn */
            .sticky { position: sticky; background-clip: padding-box; }
        </style>

        <!-- Patient Detail Form -->
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#06b6d4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Account Information
                    </h3>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 bg-[#06b6d4] hover:bg-[#0891b2] text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Enable
                        </button>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User ID</label>
                            <input type="text" x-model="selectedPatient.id" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                                <option>Patient</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" x-model="selectedPatient.name" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl">
                    </div>

                    <!-- Form chi tiết - SỬA ĐÚNG 100% -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Title</label>
                            <input type="text" x-model="selectedPatient.academic_title" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Speciality</label>
                            <input type="text" x-model="selectedPatient.speciality" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                            <input type="text" x-model="selectedPatient.sex" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="text" x-model="selectedPatient.dob" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" x-model="selectedPatient.phone" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="text" x-model="selectedPatient.email" disabled class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function patientManager() {
    return {
        patients: [
            { id: 'OP123456789', nationalId: 'OP123456789', name: 'Nguyen Van An', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '22/12/1977', phone: '0123456789', email: 'patient@example.com' },
            { id: 'OP123456790', nationalId: 'OP123456790', name: 'Tran Thi Be', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '15/03/1985', phone: '0987654321', email: 'be.tran@gmail.com' },
            { id: 'OP123456791', nationalId: 'OP123456791', name: 'Le Van Cuong', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '10/08/1992', phone: '0909090909', email: 'cuong.le@outlook.com' },
            { id: 'OP123456792', nationalId: 'OP123456792', name: 'Pham Thi Dung', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '05/11/1988', phone: '0912345678', email: 'dung.pham@yahoo.com' },
            { id: 'OP123456793', nationalId: 'OP123456793', name: 'Hoang Van Em', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '30/06/1990', phone: '0933333333', email: 'em.hoang@gmail.com' },
            { id: 'OP123456794', nationalId: 'OP123456789', name: 'Nguyen Van An', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '22/12/1977', phone: '0123456789', email: 'patient@example.com' },
            { id: 'OP123456795', nationalId: 'OP123456790', name: 'Tran Thi Be', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '15/03/1985', phone: '0987654321', email: 'be.tran@gmail.com' },
            { id: 'OP123456796', nationalId: 'OP123456791', name: 'Le Van Cuong', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '10/08/1992', phone: '0909090909', email: 'cuong.le@outlook.com' },
            { id: 'OP123456797', nationalId: 'OP123456792', name: 'Pham Thi Dung', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '05/11/1988', phone: '0912345678', email: 'dung.pham@yahoo.com' },
            { id: 'OP123456798', nationalId: 'OP123456793', name: 'Hoang Van Em', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '30/06/1990', phone: '0933333333', email: 'em.hoang@gmail.com' },
            { id: 'OP123456799', nationalId: 'OP123456789', name: 'Nguyen Van An', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '22/12/1977', phone: '0123456789', email: 'patient@example.com' },
            { id: 'OP123456800', nationalId: 'OP123456790', name: 'Tran Thi Be', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '15/03/1985', phone: '0987654321', email: 'be.tran@gmail.com' },
            { id: 'OP123456801', nationalId: 'OP123456791', name: 'Le Van Cuong', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '10/08/1992', phone: '0909090909', email: 'cuong.le@outlook.com' },
            { id: 'OP123456802', nationalId: 'OP123456792', name: 'Pham Thi Dung', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '05/11/1988', phone: '0912345678', email: 'dung.pham@yahoo.com' },
            { id: 'OP123456803', nationalId: 'OP123456793', name: 'Hoang Van Em', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '30/06/1990', phone: '0933333333', email: 'em.hoang@gmail.com' },
            { id: 'OP123456804', nationalId: 'OP123456789', name: 'Nguyen Van An', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '22/12/1977', phone: '0123456789', email: 'patient@example.com' },
            { id: 'OP123456805', nationalId: 'OP123456790', name: 'Tran Thi Be', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '15/03/1985', phone: '0987654321', email: 'be.tran@gmail.com' },
            { id: 'OP123456806', nationalId: 'OP123456791', name: 'Le Van Cuong', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '10/08/1992', phone: '0909090909', email: 'cuong.le@outlook.com' },
            { id: 'OP123456807', nationalId: 'OP123456792', name: 'Pham Thi Dung', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Female', dob: '05/11/1988', phone: '0912345678', email: 'dung.pham@yahoo.com' },
            { id: 'OP123456808', nationalId: 'OP123456793', name: 'Hoang Van Em', academic_title: 'Specialist Level I Doctor', speciality: 'doctor', sex: 'Male', dob: '30/06/1990', phone: '0933333333', email: 'em.hoang@gmail.com' },
        ],
        filteredPatients: [],
        paginatedPatients: [],
        selectedPatient: null,
        filters: {
            nationalId: '',
            name: '',
            academic_title: '',
            speciality: '',
            sex: '',
            dob: '',
            phone: '',
            email: ''
        },

        currentPage: 1,
        pageSize: 10,
        totalPages: 1,

        selectPatient(patient) {
            this.selectedPatient = { ...patient };
        },

        filterPatients() {
            this.filteredPatients = this.patients.filter(patient => {
                return (
                    patient.nationalId.toLowerCase().includes(this.filters.nationalId.toLowerCase()) &&
                    patient.name.toLowerCase().includes(this.filters.name.toLowerCase()) &&
                    patient.academic_title.toLowerCase().includes(this.filters.academic_title.toLowerCase()) &&
                    patient.speciality.toLowerCase().includes(this.filters.speciality.toLowerCase()) &&
                    (this.filters.sex === '' || patient.sex === this.filters.sex) &&
                    patient.dob.includes(this.filters.dob) &&
                    patient.phone.includes(this.filters.phone) &&
                    patient.email.toLowerCase().includes(this.filters.email.toLowerCase())
                );
            });

            this.currentPage = 1;
            this.paginate();
        },

        paginate() {
            this.totalPages = Math.ceil(this.filteredPatients.length / this.pageSize) || 1;

            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;

            this.paginatedPatients = this.filteredPatients.slice(start, end);
        },

        // Nút phân trang
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.paginate();
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.paginate();
            }
        },

        // Lấy index hiển thị
        getIndex(patient) {
            return this.filteredPatients.indexOf(patient);
        },

        // Load lần đầu
        init() {
            this.filteredPatients = [...this.patients];
            this.paginate();

            if (this.patients.length > 0) {
                this.selectPatient(this.patients[0]);
            }
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/reception-layout.php';
?>