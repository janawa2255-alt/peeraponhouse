@extends('layouts.tenant')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">
            โปรไฟล์ส่วนตัว
        </h1>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-600/20 border border-green-600/40 text-green-200 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Form Card --}}
    <div class="bg-neutral-900/80 border border-neutral-700 rounded-xl overflow-hidden shadow-lg">
        <div class="bg-neutral-800 px-6 py-3 border-b border-neutral-700">
            <h2 class="text-white font-medium">แก้ไขข้อมูลส่วนตัว</h2>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Left Column - Avatar --}}
                <div class="flex flex-col items-center">
                    <div class="mb-4">
                        @if($tenant->avatar_path)
                            <img src="{{ asset($tenant->avatar_path) }}" 
                                 alt="Avatar" 
                                 id="avatar-preview"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-neutral-700">
                        @else
                            <div id="avatar-preview" class="w-32 h-32 rounded-full bg-neutral-700 flex items-center justify-center text-4xl text-white border-4 border-neutral-600">
                                {{ mb_strtoupper(mb_substr($tenant->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <label for="avatar" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded cursor-pointer transition-colors">
                        <i class="fas fa-upload mr-1"></i> เลือกรูปใหม่
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                    <p class="text-gray-400 text-xs mt-2 text-center" id="file-name">อัปโหลดรูปโปรไฟล์ของคุณ</p>
                </div>

                {{-- Middle Column - Basic Info --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">ชื่อ-สกุล</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $tenant->name) }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors"
                               required>
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">เบอร์โทร</label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ old('phone', $tenant->phone) }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                        @error('phone')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">เลขบัตรประชาชน</label>
                        <input type="text" 
                               name="id_card" 
                               value="{{ old('id_card', $tenant->id_card) }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                        @error('id_card')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-gray-300 text-sm mb-1">ที่อยู่</label>
                        <textarea name="address" 
                                  rows="4"
                                  class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded-lg text-white focus:outline-none focus:border-orange-500">{{ old('address', $tenant->address) }}</textarea>
                        @error('address')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div>
                        <label class="block text-gray-300 text-sm mb-1">เลขบัญชีธนาคาร</label>
                        <input type="text" 
                               name="bank_account" 
                               value="{{ old('bank_account', $tenant->bank_account ?? '') }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                    </div> --}}
                </div>

                {{-- Right Column - Account Info --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">อีเมล</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $tenant->email) }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors"
                               required>
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">ยูสเซอร์เนม</label>
                        <input type="text" 
                               name="username" 
                               value="{{ old('username', $tenant->username) }}"
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors"
                               required>
                        @error('username')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Password Change Section --}}
            <div class="mt-6 pt-6 border-t border-neutral-700">
                <h3 class="text-white font-medium mb-4">แก้ไขรหัสผ่าน</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">รหัสเดิม</label>
                        <input type="password" 
                               name="current_password" 
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                        @error('current_password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">รหัสใหม่</label>
                        <input type="password" 
                               name="new_password" 
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                        @error('new_password')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">ยืนยันรหัสใหม่</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               class="w-full px-3 py-2 bg-neutral-800 border border-neutral-600 rounded text-white focus:outline-none focus:border-orange-500 transition-colors">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center gap-3">
                <button type="button" 
                        onclick="window.history.back()"
                        class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white rounded transition-colors">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview avatar when file is selected
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameDisplay = document.getElementById('file-name');
    
    if (file) {
        // แสดงชื่อไฟล์
        fileNameDisplay.textContent = '📎 ' + file.name;
        fileNameDisplay.classList.remove('text-gray-400');
        fileNameDisplay.classList.add('text-orange-400');
        
        // แสดง preview รูปภาพ
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = '';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-32 h-32 rounded-full object-cover border-4 border-orange-500';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
