<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SmartEvent.id</title>
    @vite('resources/css/app.css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-center gap-8 md:gap-20 p-6">
        
        <!-- LEFT SIDE: Branding -->
        <div class="w-full md:w-1/2 flex flex-col items-center text-center space-y-6">
            <img src="{{ asset('img/logo-company 1.png') }}" alt="Smart Event ID" class="h-16 md:h-24 object-contain">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/sign-up-8694031-6983270.png" alt="Register Illustration" class="w-3/4 max-w-sm">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Yuk Gabung Komunitas Event</h2>
                <p class="text-gray-500 mt-2 text-sm">Daftar sekarang dan mulai petualangan event serumu bersama kami.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Register Form -->
        <div class="w-full md:w-5/12 bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h1>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Nama -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none transition" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passReg" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-10 outline-none transition" required>
                        <button type="button" onclick="toggleRegPass('passReg', 'iconReg')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class='bx bx-show text-xl' id="iconReg"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passConfirm" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-10 outline-none transition" required>
                        <button type="button" onclick="toggleRegPass('passConfirm', 'iconConfirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class='bx bx-show text-xl' id="iconConfirm"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-[#4838CC] hover:bg-[#5847dd] text-white font-bold rounded-lg transition duration-200 shadow-md mt-4">
                    Daftar Sekarang
                </button>


                <div class="flex justify-center mt-4 text-sm">
                    <p class="text-gray-500 text-sm mt-1">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-[#4838CC] font-semibold hover:underline">Masuk</a>
                    </p>
                </div>

            </form>
        </div>

    </div>

    <script>
        function toggleRegPass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bx-show', 'bx-hide');
            } else {
                input.type = 'password';
                icon.classList.replace('bx-hide', 'bx-show');
            }
        }
    </script>
</body>
</html>