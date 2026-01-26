<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Smart Event ID</title>
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
                <h1 class="text-white text-2xl font-semibold text-center mb-8">RESET PASSWORD</h1>

                <!-- Form -->
                <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Email Display -->
                    <div>
                        <input 
                            type="email" 
                            value="{{ $email }}"
                            class="w-full px-6 py-4 bg-gray-300 text-gray-700 rounded-full focus:outline-none"
                            disabled
                        >
                    </div>

                    <!-- New Password Input -->
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password"
                            id="passwordInput"
                            placeholder="Password Baru" 
                            class="w-full px-6 py-4 pr-12 bg-gray-200 text-gray-700 placeholder-gray-500 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200 @error('password') ring-2 ring-red-500 @enderror"
                            required
                        >
                        <button 
                            type="button"
                            onclick="togglePassword('passwordInput', 'toggleIcon1')"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800"
                        >
                            <i class='bx bx-show text-xl' id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-sm -mt-4 ml-6">{{ $message }}</p>
                    @enderror

                    <!-- Confirm Password Input -->
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation"
                            id="passwordConfirmInput"
                            placeholder="Konfirmasi Password Baru" 
                            class="w-full px-6 py-4 pr-12 bg-gray-200 text-gray-700 placeholder-gray-500 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200"
                            required
                        >
                        <button 
                            type="button"
                            onclick="togglePassword('passwordConfirmInput', 'toggleIcon2')"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-800"
                        >
                            <i class='bx bx-show text-xl' id="toggleIcon2"></i>
                        </button>
                    </div>

                    <!-- Error Message -->
                    @error('email')
                        <div class="bg-red-500 text-white px-6 py-3 rounded-full text-center text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class='bx bx-lock-alt text-xl'></i>
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bx-show');
            toggleIcon.classList.add('bx-hide');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bx-hide');
            toggleIcon.classList.add('bx-show');
        }
    }
    </script>
</body>
</html>