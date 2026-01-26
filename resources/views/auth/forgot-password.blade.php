<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Smart Event ID</title>
    @vite('resources/css/app.css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-b from-[#2F0864] to-[#5F0FCA] flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img src="{{ asset('img/logo-company-1.png') }}" alt="Smart Event ID" class="h-32">
            </div>

            <!-- Form Card -->
            <div class="space-y-6">
                <!-- Title -->
                <h1 class="text-white text-2xl font-semibold text-center mb-8">LUPA PASSWORD</h1>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="bg-green-500 text-white px-6 py-4 rounded-full text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <input 
                            type="email" 
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email Anda" 
                            class="w-full px-6 py-4 bg-gray-200 text-gray-700 placeholder-gray-500 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200 @error('email') ring-2 ring-red-500 @enderror"
                            required
                        >
                        @error('email')
                            <p class="text-red-300 text-sm mt-2 ml-6">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class='bx bx-envelope text-xl'></i>
                        Kirim Link Reset
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('showLogin') }}" class="text-white text-sm hover:underline">
                            <i class='bx bx-arrow-back'></i> Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>