<?php ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <!-- Header + Edit / Save Button -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-10">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">System Configuration</h2>
                <p class="text-gray-600 mt-2">Manage system settings and parameters</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Nút Edit - chỉ hiện khi đang ở chế độ chỉ đọc -->
                <button x-show="!isEditing" @click="isEditing = true"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Configuration
                </button>

                <!-- Nút Save - chỉ hiện khi đang chỉnh sửa -->
                <button x-show="isEditing" @click="saveAll"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save All Changes
                </button>
            </div>
        </div>
    </div>

    <div x-data="systemConfig()" class="space-y-10">

        <!-- API Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-blue-100 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">API Configuration</h3>
                    <p class="text-gray-600">Configure API keys and endpoints</p>
                </div>
            </div>

            <div class="space-y-6 max-w-2xl">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">API Key</label>
                    <input type="password" x-model="config.apiKey" :disabled="!isEditing"
                           :class="isEditing ? 'bg-white border-gray-300 focus:ring-4 focus:ring-[#06b6d4]/20 focus:border-[#06b6d4]' : 'bg-gray-50 text-gray-500 cursor-not-allowed'"
                           class="w-full px-5 py-4 border rounded-xl transition text-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Server</label>
                    <input type="text" x-model="config.emailServer" :disabled="!isEditing"
                           :class="isEditing ? 'bg-white border-gray-300 focus:ring-4 focus:ring-[#06b6d4]/20 focus:border-[#06b6d4]' : 'bg-gray-50 text-gray-500 cursor-not-allowed'"
                           class="w-full px-5 py-4 border rounded-xl transition text-lg">
                </div>
            </div>
        </div>

        <!-- AI Model Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-purple-100 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">AI Model Configuration</h3>
                    <p class="text-gray-600">Configure AI model and parameters</p>
                </div>
            </div>

            <div class="space-y-6 max-w-2xl">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">AI Model Version</label>
                    <input type="text" x-model="config.aiModel" :disabled="!isEditing"
                           :class="isEditing ? 'bg-white border-gray-300 focus:ring-4 focus:ring-[#06b6d4]/20 focus:border-[#06b6d4]' : 'bg-gray-50 text-gray-500 cursor-not-allowed'"
                           class="w-full px-5 py-4 border rounded-xl transition text-lg">
                </div>
            </div>
        </div>

        <!-- System Limits -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-orange-100 p-4 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">System Limits</h3>
                    <p class="text-gray-600">Configure system limits and timeouts</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-2xl">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Max Concurrent Users</label>
                    <input type="number" x-model.number="config.maxUsers" :disabled="!isEditing"
                           :class="isEditing ? 'bg-white border-gray-300 focus:ring-4 focus:ring-[#06b6d4]/20 focus:border-[#06b6d4]' : 'bg-gray-50 text-gray-500 cursor-not-allowed'"
                           class="w-full px-5 py-4 border rounded-xl transition text-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Session Timeout (minutes)</label>
                    <input type="number" x-model.number="config.sessionTimeout" :disabled="!isEditing"
                           :class="isEditing ? 'bg-white border-gray-300 focus:ring-4 focus:ring-[#06b6d4]/20 focus:border-[#06b6d4]' : 'bg-gray-50 text-gray-500 cursor-not-allowed'"
                           class="w-full px-5 py-4 border rounded-xl transition text-lg">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function systemConfig() {
    return {
        isEditing: false,
        config: {
            apiKey: "****-****-****-****",
            emailServer: "smtp.hospital.com",
            aiModel: "gpt-4-turbo",
            maxUsers: 500,
            sessionTimeout: 30
        },
        original: null,

        init() {
            this.original = JSON.parse(JSON.stringify(this.config))
        },

        saveAll() {
            // Gửi về server ở đây nếu cần
            alert('Tất cả cấu hình đã được lưu thành công!')
            this.isEditing = false
            this.original = JSON.parse(JSON.stringify(this.config))
        }
    }
}
</script>

<?php
$content = ob_get_clean();
include '../layouts/reception-layout.php';
?>