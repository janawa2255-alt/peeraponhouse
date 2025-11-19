@csrf

<div class="space-y-4">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">
                ชื่อ - สกุล <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" maxlength="255"
                   value="{{ old('name', $employee->name ?? '') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500"
                   placeholder="เช่น นางสาว พิมพ์ชนก ใจดี">
            @error('name')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">เบอร์โทร</label>
            <input type="text" name="phone" maxlength="10"
                   value="{{ old('phone', $employee->phone ?? '') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500"
                   placeholder="เช่น 0891234567">
            @error('phone')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">อีเมล <span class="text-red-400">*</span></label>
            <input type="email" name="email" maxlength="255"
                   value="{{ old('email', $employee->email ?? '') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500"
                   placeholder="staff@example.com">
            @error('email')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">ชื่อผู้ใช้ (Username) <span class="text-red-400">*</span></label>
            <input type="text" name="username" maxlength="255"
                   value="{{ old('username', $employee->username ?? '') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500"
                   placeholder="เช่น peerapon.admin">
            @error('username')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-200 mb-1">
            รหัสผ่าน
            @if (!isset($employee))
                <span class="text-red-400">*</span>
            @else
                <span class="text-gray-400 text-xs">(เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน)</span>
            @endif
        </label>
        <input type="password" name="password" maxlength="255"
               class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                      focus:outline-none focus:ring-2 focus:ring-orange-500 placeholder-gray-500"
               placeholder="{{ isset($employee) ? 'ปล่อยว่างหากไม่เปลี่ยน' : 'อย่างน้อย 6 ตัวอักษร' }}">
        @error('password')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>


    <div>
        <label class="block text-sm font-medium text-gray-200 mb-1">สถานะผู้ใช้งาน <span class="text-red-400">*</span></label>

        @php
            $statusValue = old('status', $employee->status ?? 0);  // แก้ให้ถูก
        @endphp

        <select name="status"
                class="w-full px-3 py-2 rounded-lg bg-neutral-900/80 border border-orange-500/20 text-gray-200 focus:ring-orange-500">
            <option value="0" {{ $statusValue == 0 ? 'selected' : '' }}>ใช้งานอยู่</option>
            <option value="1" {{ $statusValue == 1 ? 'selected' : '' }}>ออกจากงาน</option>
        </select>

        @error('status')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>


    <div class="flex justify-end gap-3">
        <a href="{{ route('employees.index') }}"
           class="px-4 py-2 bg-gray-700 text-gray-200 rounded-lg hover:bg-gray-600 transition">
            ยกเลิก
        </a>

        <button type="submit"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-500 shadow-md transition">
            บันทึกข้อมูล
        </button>
    </div>

</div>
