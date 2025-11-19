@csrf

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">
                เลขห้อง <span class="text-red-400">*</span>
            </label>
            <input type="text" name="room_no"
                   value="{{ old('room_no', $room->room_no ?? '') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                          focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-400 placeholder-gray-500"
                   placeholder="เช่น 101, A203">
            @error('room_no')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-200 mb-1">
                ค่าเช่าพื้นฐาน (บาท) <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <input type="number" step="0.01" name="base_rent"
                       value="{{ old('base_rent', $room->base_rent ?? '') }}"
                       class="w-full px-3 py-2 pr-10 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                              focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-400 placeholder-gray-500"
                       placeholder="เช่น 3500">
                <span class="absolute inset-y-0 right-3 flex items-center text-xs text-gray-400">บาท</span>
            </div>
            @error('base_rent')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-200 mb-1">
            หมายเหตุ
        </label>
        <textarea name="note" rows="3"
                  class="w-full px-3 py-2 rounded-lg border border-gray-600 bg-neutral-900/80 text-gray-100
                         focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-400 placeholder-gray-500"
                  placeholder="เช่น ห้องมุม, มีเครื่องทำน้ำอุ่น ฯลฯ">{{ old('note', $room->note ?? '') }}</textarea>
        @error('note')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-2 pt-2">
        <a href="{{ route('backend.rooms.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-500
                  text-gray-200 hover:bg-gray-800 transition">
            ยกเลิก
        </a>
        <button type="submit"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                       bg-gradient-to-r from-orange-500 to-orange-600 text-white
                       hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                       focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
            บันทึกข้อมูล
        </button>
    </div>
</div>
