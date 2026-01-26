<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SmartEvent.id</title>
    @vite('resources/css/app.css')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-center gap-8 md:gap-20 p-6">
        
        <!-- LEFT SIDE: Branding / Illustration -->
        <div class="w-full md:w-1/2 flex flex-col items-center text-center space-y-6">
            <img src="{{ asset('img/logo-company 1.png') }}" alt="Smart Event ID" class="h-16 md:h-24 object-contain">
            
            <!-- Ilustrasi Admin (Dashboard Concept) -->
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/admin-panel-4439227-3728454.png" alt="Admin Illustration" class="w-3/4 max-w-sm mix-blend-multiply">
            
            <div>
                <h2 class="text-xl font-bold text-gray-800">Administrator Portal</h2>
                <p class="text-gray-500 mt-2 text-sm">Kelola event, transaksi, dan pengguna dalam satu dashboard terintegrasi dengan mudah.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Login Form -->
        <div class="w-full md:w-5/12 bg-white p-8 md:p-10 rounded-2xl shadow-xl border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Login Admin</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk untuk mengakses dashboard.</p>
            </div>

            <!-- Messages -->
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('loginAdmin') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Email Administrator</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class='bx bx-envelope text-xl'></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#6C5DD3] focus:border-[#6C5DD3] block outline-none transition" 
                            placeholder="Masukkan email"
                            required autofocus>
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class='bx bx-lock-alt text-xl'></i>
                        </div>
                        <input type="password" name="password" id="passwordInput" 
                            class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#6C5DD3] focus:border-[#6C5DD3] block outline-none transition" 
                            placeholder="••••••••"
                            required>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class='bx bx-show text-xl' id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-[#6C5DD3] hover:bg-[#5b4ec2] text-white font-bold rounded-lg transition duration-200 shadow-md flex items-center justify-center gap-2 mt-2">
                    <i class='bx bx-log-in-circle text-xl'></i> Masuk Dashboard
                </button>

            </form>
            
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
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