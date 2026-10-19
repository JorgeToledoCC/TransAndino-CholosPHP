<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión · Trans Andino</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        vino: {
                            50: '#fdf3f5', 100: '#fbe3e7', 200: '#f6c8d0', 300: '#ec9fb0',
                            400: '#dd6a86', 500: '#c93f61', 600: '#ab2547', 700: '#8c1c39',
                            800: '#741931', 900: '#5e162a', 950: '#350b17',
                        },
                        dorado: {
                            300: '#e8d29a', 400: '#d9b868', 500: '#c99f3f', 600: '#ab8129', 700: '#876420',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                    },
                },
            },
        };
    </script>
    <style>
        @keyframes sacudir {
            0%, 100% { transform: translateX(0) }
            20% { transform: translateX(-9px) }
            40% { transform: translateX(9px) }
            60% { transform: translateX(-6px) }
            80% { transform: translateX(6px) }
        }

        .login-sacudida { animation: sacudir .4s ease }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-vino-950 via-vino-900 to-vino-800 p-4 font-sans text-stone-800 antialiased">
    <main class="w-full max-w-md">
        {{-- Marca --}}
        <header class="mb-8 flex flex-col items-center text-center">
            <span class="grid h-16 w-16 place-items-center rounded-full bg-dorado-500/15 ring-1 ring-dorado-400/40">
                <svg class="h-8 w-8 text-dorado-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 17V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10"/>
                    <path d="M4 11h16"/>
                    <path d="M7 19v-2M17 19v-2"/>
                    <circle cx="8.5" cy="14" r="1"/>
                    <circle cx="15.5" cy="14" r="1"/>
                </svg>
            </span>
            <h1 class="mt-4 font-display text-2xl font-bold text-white">Trans Andino</h1>
            <p class="mt-1 text-[10px] font-medium uppercase tracking-[0.35em] text-dorado-300">Transporte interdepartamental</p>
        </header>

        {{-- Tarjeta --}}
        <div id="login-tarjeta" class="overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-vino-900/10">
            <div class="h-1 bg-gradient-to-r from-dorado-700 via-dorado-400 to-dorado-700"></div>

            <div class="px-8 py-9">
                <div class="mb-8 text-center">
                    <h2 class="font-display text-xl font-bold text-vino-900">Iniciar sesión</h2>
                    <p class="mt-1 text-sm text-stone-500">Accede al sistema de venta de pasajes.</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div id="login-errores" class="mb-6 rounded-xl border border-vino-200 bg-vino-50 px-4 py-3 text-center text-sm text-vino-800">
                        <ul id="login-errores-lista" class="space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div id="login-errores" class="mb-6 hidden rounded-xl border border-vino-200 bg-vino-50 px-4 py-3 text-center text-sm text-vino-800">
                        <ul id="login-errores-lista" class="space-y-0.5"></ul>
                    </div>
                @endif

                <form id="form-login" action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-stone-700">Usuario</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               autofocus autocomplete="username" placeholder="Tu usuario"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-stone-700">Contraseña</label>
                        <input type="password" name="password" id="password"
                               autocomplete="current-password" placeholder="••••••••"
                               class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2.5 text-sm text-stone-800 shadow-sm transition focus:border-vino-500 focus:outline-none focus:ring-2 focus:ring-vino-500/25"
                               required>
                    </div>

                    <label class="flex items-center justify-center gap-2 text-sm text-stone-600">
                        <input type="checkbox" name="remember" id="remember"
                               class="h-4 w-4 rounded border-stone-300 text-vino-700 focus:ring-vino-500">
                        Recordarme en este equipo
                    </label>

                    <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-vino-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-vino-800 focus:outline-none focus:ring-2 focus:ring-vino-500 focus:ring-offset-2">
                        Entrar
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-vino-200">
            &copy; {{ date('Y') }} Trans Andino. Todos los derechos reservados.
        </p>
    </main>

    <script>
        (function () {
            const rutaSonido = @json(asset('audio/error.mp3'));
            const formulario = document.getElementById('form-login');
            const tarjeta = document.getElementById('login-tarjeta');
            const cajaErrores = document.getElementById('login-errores');
            const listaErrores = document.getElementById('login-errores-lista');

            function reproducirError() {
                const audio = new Audio(rutaSonido);
                audio.volume = 0.6;
                audio.play().catch(function () {});
            }

            function sacudir() {
                tarjeta.classList.remove('login-sacudida');
                void tarjeta.offsetWidth;
                tarjeta.classList.add('login-sacudida');
            }

            function mostrarErrores(errores) {
                const mensajes = [];
                Object.keys(errores || {}).forEach(function (campo) {
                    (errores[campo] || []).forEach(function (mensaje) { mensajes.push(mensaje); });
                });
                if (mensajes.length === 0) {
                    mensajes.push('Usuario o contraseña incorrectos. Verifica tus datos.');
                }

                listaErrores.innerHTML = '';
                mensajes.forEach(function (mensaje) {
                    const li = document.createElement('li');
                    li.textContent = mensaje;
                    listaErrores.appendChild(li);
                });
                cajaErrores.classList.remove('hidden');
            }

            // Respaldo sin JS: si el servidor ya devolvio errores, suena al cargar.
            if (cajaErrores && !cajaErrores.classList.contains('hidden')) {
                reproducirError();
                sacudir();
            }

            if (! formulario) {
                return;
            }

            formulario.addEventListener('submit', function (evento) {
                if (! window.fetch) {
                    return;
                }

                evento.preventDefault();

                const datos = new FormData(formulario);

                fetch(formulario.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': datos.get('_token'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: datos,
                    credentials: 'same-origin',
                })
                .then(function (respuesta) {
                    if (respuesta.redirected || respuesta.ok) {
                        window.location = respuesta.url || @json(route('departures.index'));
                        return null;
                    }
                    return respuesta.json();
                })
                .then(function (data) {
                    if (! data) {
                        return;
                    }
                    mostrarErrores(data.errors);
                    reproducirError();
                    sacudir();
                })
                .catch(function () {
                    formulario.submit();
                });
            });
        })();
    </script>
</body>
</html>
