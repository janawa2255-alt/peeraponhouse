@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-neutral-200">สร้างประกาศใหม่</h1>
        <a href="{{ route('backend.announcements.index') }}" 
           class="px-4 py-2 bg-neutral-600 hover:bg-neutral-700 text-white rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>กลับ
        </a>
    </div>

    <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
        <form action="{{ route('backend.announcements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                <!-- หัวข้อ -->
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-2">หัวข้อประกาศ <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-2 bg-neutral-900 border border-neutral-600 rounded-lg text-neutral-200 focus:border-orange-500 focus:outline-none"
                           required>
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- เนื้อหา -->
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-2">เนื้อหา <span class="text-red-500">*</span></label>
                    <textarea name="content" 
                              rows="6"
                              class="w-full px-4 py-2 bg-neutral-900 border border-neutral-600 rounded-lg text-neutral-200 focus:border-orange-500 focus:outline-none"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- รูปภาพ -->
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-2">รูปภาพประกอบ</label>
                    <input type="file" 
                           name="image" 
                           accept="image/*"
                           class="block w-full max-w-full px-4 py-2 bg-neutral-900 border border-neutral-600 rounded-lg text-neutral-200 text-sm focus:border-orange-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-orange-600 file:text-white hover:file:bg-orange-700">
                    <p class="text-neutral-400 text-xs mt-1">รองรับไฟล์: JPG, PNG, GIF (ขนาดไม่เกิน 2MB)</p>
                    @error('image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- สถานะ -->
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-2">สถานะ <span class="text-red-500">*</span></label>
                    <select name="status" 
                            class="w-full px-4 py-2 bg-neutral-900 border border-neutral-600 rounded-lg text-neutral-200 focus:border-orange-500 focus:outline-none"
                            required>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>เปิดใช้งาน</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>ปิดใช้งาน</option>
                    </select>
                    @error('status')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ปุ่ม -->
                <div class="flex gap-2 pt-4">
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>บันทึก
                    </button>
                    <a href="{{ route('backend.announcements.index') }}" 
                       class="px-6 py-2 bg-neutral-600 hover:bg-neutral-700 text-white rounded-lg transition-colors">
                        ยกเลิก
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
