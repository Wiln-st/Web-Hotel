<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — HotelKu</title>
    <meta name="description" content="Login to HotelKu hotel management dashboard." />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6C3FC5',
                        'primary-dark': '#5A2DAF',
                        'primary-light': '#EDE7F9',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Animated gradient background */
        .bg-animate {
            background: linear-gradient(-45deg, #1a1a2e, #2d1b69, #4a1f9e, #6C3FC5, #1a1a2e);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            animation: float 18s infinite ease-in-out;
        }

        .particle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -80px;
            left: -60px;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -40px;
            right: -50px;
            animation-delay: 4s;
        }

        .particle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 40%;
            left: 60%;
            animation-delay: 8s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.95);
            }
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Input focus ring */
        .input-field {
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .input-field:focus {
            border-color: #6C3FC5;
            box-shadow: 0 0 0 3px rgba(108, 63, 197, 0.15);
        }

        /* Button hover glow */
        .btn-primary {
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 63, 197, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 6px 25px rgba(108, 63, 197, 0.5);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(108, 63, 197, 0.3);
        }

        /* Show/hide password toggle */
        .toggle-pw {
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-pw:hover {
            color: #6C3FC5;
        }
    </style>
</head>

<body class="bg-animate min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- ============================================================== -->
    <!-- BACKGROUND PARTICLES -->
    <!-- ============================================================== -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <!-- ============================================================== -->
    <!-- LOGIN CARD -->
    <!-- ============================================================== -->
    <div class="glass-card w-full max-w-md mx-4 rounded-3xl shadow-2xl p-8 sm:p-10 relative z-10">

        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 rounded-2xl bg-primary mx-auto flex items-center justify-center mb-4 shadow-lg shadow-primary/30">
                <i class="fa-solid fa-hotel text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang</h1>
            <p class="text-sm text-gray-400 mt-1">Masuk ke akun HotelKu Anda</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="/login" id="loginForm" onsubmit="handleLogin(event)" class="flex flex-col gap-5">
            @csrf
            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-gray-500 mb-1.5">
                    <i class="fa-solid fa-user mr-1 text-primary/60"></i>Username
                </label>
                <input id="username" type="text" placeholder="Masukkan username" required
                    class="input-field w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none bg-gray-50/50" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-500 mb-1.5">
                    <i class="fa-solid fa-lock mr-1 text-primary/60"></i>Password
                </label>
                <div class="relative">
                    <input id="password" type="password" placeholder="Masukkan password" required
                        class="input-field w-full border border-gray-200 rounded-xl px-4 py-3 pr-11 text-sm outline-none bg-gray-50/50" />
                    <button type="button" onclick="togglePassword('password', this)"
                        class="toggle-pw absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="remember" class="w-4 h-4 accent-primary rounded" />
                    <span class="text-xs text-gray-500">Ingatkan Saya</span>
                </label>
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="btn-primary bg-primary hover:bg-primary-dark text-white font-semibold py-3.5 rounded-xl text-sm mt-1">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>Login
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-[11px] text-gray-400 font-medium">ATAU</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>


        <!-- Register Link -->
        <p class="text-center text-xs text-gray-400 mt-6">
            Belum punya akun?
            <a href="/register" class="text-primary font-semibold hover:underline">Daftar</a>
        </p>
    </div>

    <!-- ============================================================== -->
    <!-- JAVASCRIPT -->
    <!-- ============================================================== -->
    <script>
        // Toggle password visibility
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Handle login → redirect to dashboard
        function handleLogin(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            if (username && password) {
                // Simple redirect to dashboard (no backend)
                window.location.href = '/dashboard';
            }
        }
    </script>
</body>

</html>