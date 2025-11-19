{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .login-slider-wrapper {
        width: 200%;
        display: flex;
        transition: transform 0.35s ease;
    }
    .login-slider-wrapper.show-owner {
        transform: translateX(-50%);
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-neutral-900 px-4">
    <div class="w-full max-w-xl bg-neutral-800/80 border border-neutral-700 rounded-2xl shadow-xl overflow-hidden">

        {{-- แถบบนชื่อระบบ --}}
        <div class="px-8 py-5 bg-neutral-900 border-b border-neutral-700 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-white">
                    พีระพลเฮาส์
                </h1>
                <p class="text-xs text-neutral-400">
                    ระบบจัดการห้องเช่า – เข้าสู่ระบบ
                </p>
            </div>
        </div>

        {{-- ตัวสลับโหมด ผู้เช่า / เจ้าของ --}}
        <div class="px-8 pt-6">
            <div class="relative bg-neutral-900 rounded-full p-1 border border-neutral-700">
                <div id="role-indicator"
                     class="absolute top-1 left-1 w-1/2 h-8 rounded-full bg-orange-500/90 transition-all duration-300">
                </div>

                <div class="relative grid grid-cols-2 text-xs font-medium text-center">
                    <button type="button"
                            id="btn-tenant"
                            class="py-2 z-10 text-white">
                        ผู้เช่า
                    </button>
                    <button type="button"
                            id="btn-owner"
                            class="py-2 z-10 text-neutral-300">
                        เจ้าของ / แอดมิน
                    </button>
                </div>
            </div>
        </div>

        {{-- พื้นที่สไลด์ฟอร์ม --}}
        <div class="px-8 pb-8 pt-6 overflow-hidden">
            <div id="login-slider" class="login-slider-wrapper">

                {{-- ฟอร์มผู้เช่า --}}
                <div class="w-1/2 pr-4">
                    <h2 class="text-white font-semibold mb-4">
                        เข้าสู่ระบบสำหรับผู้เช่า
                    </h2>

                    <form method="POST" action="{{ route('login.tenant') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1">
                            <label class="block text-sm text-neutral-300">
                                อีเมล / ชื่อผู้ใช้
                            </label>
                            <input type="text" name="username"
                                   class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-sm text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                   placeholder="กรอกอีเมลหรือชื่อผู้ใช้ของคุณ">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm text-neutral-300">
                                รหัสผ่าน
                            </label>
                            <input type="password" name="password"
                                   class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-sm text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                   placeholder="กรอกรหัสผ่าน">
                        </div>

                        <p class="text-xs text-neutral-400">
                            หากลืมรหัสผ่าน กรุณาติดต่อเจ้าของหอ / แอดมิน เพื่อรีเซ็ตรหัสผ่านให้
                        </p>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium
                                       rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 text-white
                                       hover:from-orange-400 hover:to-orange-500 shadow-md shadow-orange-900/40
                                       focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                            เข้าสู่ระบบผู้เช่า
                        </button>
                    </form>
                </div>

                {{-- ฟอร์มเจ้าของ --}}
                <div class="w-1/2 pl-4">
                    <h2 class="text-white font-semibold mb-4">
                        เข้าสู่ระบบสำหรับเจ้าของ / แอดมิน
                    </h2>

                    <form method="POST" action="{{ route('login.owner') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1">
                            <label class="block text-sm text-neutral-300">
                                อีเมล / ชื่อผู้ใช้
                            </label>
                            <input type="text" name="username"
                                   class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-sm text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                   placeholder="กรอกอีเมลหรือชื่อผู้ใช้ของคุณ">
                        </div>

                        <div class="space-y-1">
                            <label class="block text-sm text-neutral-300">
                                รหัสผ่าน
                            </label>
                            <input type="password" name="password"
                                   class="w-full rounded-lg bg-neutral-900 border border-neutral-700 text-sm text-white px-3 py-2 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                   placeholder="กรอกรหัสผ่าน">
                        </div>

                        <p class="text-xs text-neutral-400">
                            หากลืมรหัสผ่าน กรุณาติดต่อผู้พัฒนาระบบหรือเจ้าของหอเพื่อเปลี่ยนรหัสผ่าน
                        </p>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium
                                       rounded-lg bg-neutral-700 text-white
                                       hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 focus:ring-offset-neutral-900">
                            เข้าสู่ระบบเจ้าของ / แอดมิน
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- สคริปต์สลับฟอร์มแบบสไลด์ --}}
<script>
    const slider = document.getElementById('login-slider');
    const btnTenant = document.getElementById('btn-tenant');
    const btnOwner  = document.getElementById('btn-owner');
    const indicator = document.getElementById('role-indicator');

    btnTenant.addEventListener('click', () => {
        slider.classList.remove('show-owner');
        btnTenant.classList.add('text-white');
        btnTenant.classList.remove('text-neutral-300');
        btnOwner.classList.add('text-neutral-300');
        btnOwner.classList.remove('text-white');
        indicator.style.transform = 'translateX(0)';
    });

    btnOwner.addEventListener('click', () => {
        slider.classList.add('show-owner');
        btnOwner.classList.add('text-white');
        btnOwner.classList.remove('text-neutral-300');
        btnTenant.classList.add('text-neutral-300');
        btnTenant.classList.remove('text-white');
        indicator.style.transform = 'translateX(100%)';
    });
</script>
@endsection
