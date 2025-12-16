{{-- resources/views/auth/admin-login.blade.php --}}
@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-neutral-950 px-4">
    {{-- Background Effects --}}
    <div class="fixed inset-0 z-[-1] pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(59,130,246,0.15),transparent_50%)]"></div>
        <div class="absolute bottom-0 right-0 w-full h-1/2 bg-[radial-gradient(circle_at_100%_100%,rgba(59,130,246,0.1),transparent_50%)]"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo / Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white font-bold text-2xl shadow-lg shadow-blue-900/40 mb-4">
                PH
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">
                เข้าสู่ระบบแอดมิน
            </h1>
            <p class="text-sm text-gray-400">
                ระบบจัดการห้องเช่า พีระพลเฮาส์
            </p>
        </div>

        {{-- Login Card --}}
        <div class="bg-neutral-900/80 border border-blue-500/20 rounded-2xl shadow-2xl shadow-black/40 overflow-hidden backdrop-blur-xl">
            <div class="px-8 py-6">
                <form method="POST" action="{{ route('backend.login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="rounded-lg bg-green-900/20 border border-green-500/50 px-4 py-3">
                            <p class="text-sm text-green-400">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </p>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if ($errors->has('owner_login'))
                        <div class="rounded-lg bg-red-900/20 border border-red-500/50 px-4 py-3">
                            <p class="text-sm text-red-400">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $errors->first('owner_login') }}
                            </p>
                        </div>
                    @endif

                    {{-- Username Field --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-200">
                            ชื่อผู้ใช้
                        </label>
                        <input type="text" 
                               name="username" 
                               value="{{ old('username') }}"
                               class="w-full rounded-lg bg-neutral-800/60 border border-neutral-700 text-white px-4 py-3 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      placeholder-gray-500 transition-all"
                               placeholder="กรอกชื่อผู้ใช้" 
                               required 
                               autofocus>
                        @error('username')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-200">
                            รหัสผ่าน
                        </label>
                        <input type="password" 
                               name="password"
                               class="w-full rounded-lg bg-neutral-800/60 border border-neutral-700 text-white px-4 py-3 
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      placeholder-gray-500 transition-all"
                               placeholder="กรอกรหัสผ่าน" 
                               required>
                        @error('password')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Text --}}
                    <p class="text-xs text-gray-400 bg-neutral-800/40 rounded-lg px-3 py-2 border border-neutral-700/50">
                        <i class="fas fa-info-circle mr-1"></i>
                        หากลืมรหัสผ่าน กรุณาติดต่อผู้พัฒนาระบบ
                    </p>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold
                                   rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                   hover:from-blue-400 hover:to-blue-500 
                                   shadow-lg shadow-blue-900/40
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-neutral-900
                                   transition-all active:scale-[0.98]">
                        <i class="fas fa-sign-in-alt"></i>
                        เข้าสู่ระบบ
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-4 bg-neutral-800/40 border-t border-neutral-700/50 text-center">
                <a href="{{ route('login') }}" 
                   class="text-sm text-gray-400 hover:text-orange-400 transition-colors">
                   <i class="fas fa-user mr-1"></i>
                    เข้าสู่ระบบสำหรับผู้เช่า
                </a>
            </div>
        </div>

        {{-- Copyright --}}
        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; {{ date('Y') }} Peerapon House. All rights reserved.
        </p>
    </div>
</div>
@endsection
