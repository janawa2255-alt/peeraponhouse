@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="space-y-4">

        <h1 class="text-2xl font-semibold text-white">
            แก้ไขข้อมูลผู้เช่าใหม่
        </h1>
        <p class="text-sm text-gray-400">
            กรอกข้อมูลผู้เช่าที่ต้องการแก้ไขข้อมูลผู้เช่าในระบบ
        </p>

        <form action="{{ route('backend.tenants.update' , $tenant->tenant_id ) }}" method="POST" enctype="multipart/form-data"
              class="bg-neutral-900/80 border border-orange-500/20 rounded-2xl shadow-lg shadow-black/40 p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-4 gap-4">
            {{-- ชื่อผู้เช่า --}}
                <div>
                    <label class="text-sm text-gray-200">ชื่อผู้เช่า *</label>
                    <input type="text" name="name"
                        value="{{ old('name', $tenant->name) }}"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        required>
                </div>

                {{-- เบอร์โทร --}}
                <div>
                    <label class="text-sm text-gray-200">เบอร์โทร * (10 หลัก)</label>
                    <input type="text" name="phone"
                        value="{{ old('phone', $tenant->phone) }}"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        maxlength="10"
                        pattern="[0-9]{10}"
                        placeholder="0812345678"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required>
                </div>

                {{-- อีเมล --}}
                <div>
                    <label class="text-sm text-gray-200">อีเมล *</label>
                    <input type="text" name="email"
                        value="{{ old('email', $tenant->email) }}"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        required>
                </div>

                {{-- บัตรประชาชน --}}
                <div>
                    <label class="text-sm text-gray-200">บัตรประชาชน * (13 หลัก)</label>
                    <input type="text" name="id_card"
                        value="{{ old('id_card' , $tenant->id_card ) }}"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        maxlength="13"
                        pattern="[0-9]{13}"
                        placeholder="1234567890123"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required>
                </div>
            </div>
             <div class="grid grid-cols-2 gap-4">
                {{-- ชื่อผู้ใช้ --}}
                <div>
                    <label class="text-sm text-gray-200">ชื่อผู้ใช้ *</label>
                    <input type="text" name="username"
                        value="{{ old('username', $tenant->username ) }}"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        required>
                </div>

                {{-- รหัสผ่าน --}}
                <div>
                    <label class="text-sm text-gray-200">รหัสผ่าน *</label>
                    <input type="password" name="password"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200">
                </div>
            </div>
            {{-- ที่อยู่ --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-200">ที่อยู่ *</label>
                    <textarea name="address" rows="3"
                        class="w-full px-3 py-2 rounded-lg bg-neutral-900/60 border border-gray-600 text-gray-200"
                        required>{{ old('address' , $tenant->address ) }}</textarea>
                </div>
                <div>

    <input id="avatar_path_input" 
           type="file" 
           name="avatar_path" 
           accept="image/*"
           class="hidden">

    <!-- ปุ่มอัปโหลด -->
    <label for="avatar_path_input"
           class="inline-block px-4 py-2 mt-2 bg-orange-500 hover:bg-orange-600 
                  text-gray-200 rounded-lg cursor-pointer border border-gray-600">
        เลือกรูปภาพ
    </label>

    <!-- พื้นที่แสดงตัวอย่าง -->
    <div id="avatar_path_preview" class="mt-3"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatar_path_input');
    const preview = document.getElementById('avatar_path_preview');

    input.addEventListener('change', function (e) {
        preview.innerHTML = '';
        const file = e.target.files[0];

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (ev) {

                // สร้าง container รูป
                const wrapper = document.createElement('div');
                wrapper.className = "relative inline-block";

                // รูปภาพ
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.className = "max-h-32 rounded-lg border border-gray-600";

                // ปุ่มลบ
                const btn = document.createElement('button');
                btn.innerHTML = "×";
                btn.className =
                    "absolute top-0 right-0 -mt-2 -mr-2 bg-red-600 hover:bg-red-500 text-white " +
                    "w-6 h-6 rounded-full flex items-center justify-center shadow";

                btn.addEventListener('click', function () {
                    input.value = '';     // เคลียร์ input
                    preview.innerHTML = ''; // เคลียร์ตัวแสดงรูป
                });

                wrapper.appendChild(img);
                wrapper.appendChild(btn);
                preview.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        }
    });
});
</script>

            </div>

            <div class="flex justify-between">

                <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                               bg-gradient-to-r from-orange-500 to-orange-600 text-white
                               hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                    บันทึกข้อมูล
                </button>
                <a href="{{ route('backend.tenants.index') }}">
                    <button type="button"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg
                                   bg-neutral-900/60 border border-gray-600 text-gray-200
                                   hover:bg-neutral-900/80 shadow-md shadow-black/40
                                   focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                        ยกเลิก
                    </button>
                </a>
            </div>
        </form>
    </div>      
</div>
@endsection