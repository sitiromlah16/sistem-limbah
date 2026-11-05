<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <title>Login - Sistem Pengelolaan Limbah DTY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light;
            font-family: 'Inter', sans-serif;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1220;
            background-image:
                radial-gradient(ellipse at 15% 20%, rgba(14, 165, 233, .25), transparent 40%),
                radial-gradient(ellipse at 85% 80%, rgba(99, 102, 241, .25), transparent 40%),
                linear-gradient(135deg, rgba(8, 25, 48, .84), rgba(15, 23, 42, .75) 50%, rgba(15, 23, 42, .88)),
                url("{{ asset('images/IMG-20260617-WA0007.jpg') }}");
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }

        .login-card {
            border: 1px solid rgba(255, 255, 255, .7);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, .35), 0 10px 25px -5px rgba(15, 23, 42, .1);
            animation: card-appear .5s cubic-bezier(.16, 1, .3, 1) both;
        }

        .field-input {
            background-color: #f8fafc;
            transition: all .2s ease;
        }

        .field-input:focus {
            border-color: #0284c7;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, .12);
        }

        .demo-btn {
            transition: all .2s ease;
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -2px rgba(15, 23, 42, .08);
        }

        @keyframes card-appear {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="login-card w-full max-w-[440px] rounded-3xl p-6 sm:p-9 text-slate-900">
        <!-- Logo & Title -->
        <div class="text-center mb-6">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-sky-600 to-indigo-600 text-white shadow-lg shadow-sky-600/25 mb-3">
                <i class="fa-solid fa-recycle text-xl"></i>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Sistem Limbah dan Reject</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Politeknik Enjinering Indorama · <strong>DTY</strong> (<em>Draw Textured Yarn</em>)</p>
        </div>

        @if($errors->any())
            <div class="mb-5 flex items-center gap-2.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-3 text-xs text-rose-800" role="alert">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm flex-shrink-0" aria-hidden="true"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" id="loginForm" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1.5 block text-xs font-semibold text-slate-700">Email</label>
                <div class="relative">
                    <i class="fa-regular fa-envelope pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com"
                        class="field-input h-11 w-full rounded-xl border border-slate-200 pl-10 pr-3.5 text-sm text-slate-900 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-semibold text-slate-700">Kata Sandi</label>
                <div class="relative">
                    <i class="fa-solid fa-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm" aria-hidden="true"></i>
                    <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"
                        class="field-input h-11 w-full rounded-xl border border-slate-200 pl-10 pr-11 text-sm text-slate-900 outline-none placeholder:text-slate-400">
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 flex w-10 items-center justify-center text-slate-400 hover:text-slate-700 focus:outline-none"
                        aria-label="Tampilkan kata sandi">
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-0.5">
                <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit"
                class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 text-sm font-semibold text-white shadow-md shadow-sky-600/20 transition hover:from-sky-500 hover:to-indigo-500 active:scale-[0.99]">
                <span>Masuk</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="my-5 flex items-center gap-3">
            <span class="h-px flex-1 bg-slate-200"></span>
            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Akses cepat · akun demo</span>
            <span class="h-px flex-1 bg-slate-200"></span>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <button type="button" onclick="setLogin('operator@pabrik.com', 'password123')"
                class="demo-btn rounded-xl border border-sky-100 bg-sky-50/80 p-2.5 text-left hover:border-sky-300">
                <div class="mb-1 flex h-7 w-7 items-center justify-center rounded-lg bg-sky-100 text-sky-700">
                    <i class="fa-solid fa-helmet-safety text-xs"></i>
                </div>
                <div class="text-xs font-bold text-slate-800">Operator</div>
                <div class="text-[10px] text-slate-500">Budi</div>
            </button>

            <button type="button" onclick="setLogin('admin@pabrik.com', 'password123')"
                class="demo-btn rounded-xl border border-emerald-100 bg-emerald-50/80 p-2.5 text-left hover:border-emerald-300">
                <div class="mb-1 flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                </div>
                <div class="text-xs font-bold text-slate-800">Admin</div>
                <div class="text-[10px] text-slate-500">Siti</div>
            </button>

            <button type="button" onclick="setLogin('manajer@pabrik.com', 'password123')"
                class="demo-btn rounded-xl border border-violet-100 bg-violet-50/80 p-2.5 text-left hover:border-violet-300">
                <div class="mb-1 flex h-7 w-7 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                </div>
                <div class="text-xs font-bold text-slate-800">Manajer</div>
                <div class="text-[10px] text-slate-500">Andi</div>
            </button>
        </div>
    </div>

    <script>
        function setLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('loginForm').requestSubmit();
        }

        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const isVisible = password.type === 'text';

            password.type = isVisible ? 'password' : 'text';
            this.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            this.querySelector('i').className = isVisible ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
        });
    </script>
</body>

</html>