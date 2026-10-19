<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Trans Andino')</title>
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
                            50: '#fdf3f5',
                            100: '#fbe3e7',
                            200: '#f6c8d0',
                            300: '#ec9fb0',
                            400: '#dd6a86',
                            500: '#c93f61',
                            600: '#ab2547',
                            700: '#8c1c39',
                            800: '#741931',
                            900: '#5e162a',
                            950: '#350b17',
                        },
                        dorado: {
                            300: '#e8d29a',
                            400: '#d9b868',
                            500: '#c99f3f',
                            600: '#ab8129',
                            700: '#876420',
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
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-stone-100 font-sans text-stone-800 antialiased">
    @php
        $navLinks = [
            ['route' => 'departures.index', 'label' => 'Salidas', 'match' => 'departures.*'],
            ['route' => 'routes.index', 'label' => 'Rutas', 'match' => 'routes.*'],
            ['route' => 'buses.index', 'label' => 'Buses', 'match' => 'buses.*'],
        ];

        if (auth()->check() && auth()->user()->is_admin) {
            $navLinks[] = ['route' => 'users.create', 'label' => 'Usuarios', 'match' => 'users.*'];
            $navLinks[] = ['route' => 'activity.index', 'label' => 'Actividad', 'match' => 'activity.*'];
        }
    @endphp

    <header class="bg-gradient-to-r from-vino-950 via-vino-900 to-vino-800 shadow-lg">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-3 px-4 py-4 md:grid-cols-[1fr_auto_1fr]">
            <a href="{{ route('home') }}" class="flex items-center gap-3 md:justify-self-start">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-dorado-500/15 ring-1 ring-dorado-400/40">
                    <svg class="h-6 w-6 text-dorado-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 17V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10"/>
                        <path d="M4 11h16"/>
                        <path d="M7 19v-2M17 19v-2"/>
                        <circle cx="8.5" cy="14" r="1"/>
                        <circle cx="15.5" cy="14" r="1"/>
                    </svg>
                </span>
                <span class="leading-tight">
                    <span class="block font-display text-xl font-bold text-white">Trans Andino</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.3em] text-dorado-300">Transporte interdepartamental</span>
                </span>
            </a>

            <nav class="flex flex-wrap items-center justify-center gap-1 text-sm font-medium md:justify-self-center">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="rounded-full px-4 py-1.5 transition {{ request()->routeIs($link['match']) ? 'bg-white text-vino-800 shadow-sm' : 'text-vino-100 hover:bg-white/10 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex flex-wrap items-center gap-3 md:justify-self-end">
                @auth
                    <div class="flex items-center gap-2 rounded-full bg-white/10 py-1 pl-1 pr-1.5 ring-1 ring-white/15">
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}"
                             class="h-8 w-8 rounded-full object-cover ring-1 ring-white/30">
                        <span class="hidden max-w-[8rem] truncate text-sm font-medium text-white sm:block">
                            {{ auth()->user()->name }}
                        </span>
                        @if(auth()->user()->is_admin)
                            <span class="hidden rounded-full bg-dorado-500/20 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-dorado-300 sm:block">
                                Admin
                            </span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Cerrar sesión" aria-label="Cerrar sesión"
                                    class="grid h-7 w-7 place-items-center rounded-full text-vino-100 transition hover:bg-white/15 hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <path d="M10 17l5-5-5-5"/>
                                    <path d="M15 12H3"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
        <div class="h-1 bg-gradient-to-r from-dorado-700 via-dorado-400 to-dorado-700"></div>
    </header>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-8">
        @if(session('success'))
            <div class="no-print mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/><path d="m8.5 12.5 2.5 2.5 4.5-5"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="no-print mb-5 flex items-start gap-3 rounded-xl border border-vino-200 bg-vino-50 px-4 py-3 text-sm text-vino-800 shadow-sm">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-vino-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="no-print mb-5 rounded-xl border border-vino-200 bg-vino-50 px-4 py-3 text-sm text-vino-800 shadow-sm">
                <p class="mb-1 font-semibold">Revisa los siguientes errores:</p>
                <ul class="list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-vino-950 text-vino-200">
        <div class="h-1 bg-gradient-to-r from-dorado-700 via-dorado-400 to-dorado-700"></div>
        <p class="py-5 text-center text-sm">&copy; {{ date('Y') }} Trans Andino. Todos los derechos reservados.</p>
    </footer>

    {{-- Modal de confirmacion reutilizable --}}
    <div id="modal-confirmacion" class="fixed inset-0 z-50 hidden items-center justify-center bg-vino-950/60 p-4 backdrop-blur-sm">
        <div id="modal-confirmacion-panel"
             class="w-full max-w-sm scale-95 rounded-2xl bg-white p-6 text-center opacity-0 shadow-2xl ring-1 ring-vino-900/10 transition duration-150 ease-out">
            <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-rose-100 text-rose-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <path d="M12 9v4M12 17h.01"/>
                </svg>
            </div>
            <h3 class="mt-4 font-display text-lg font-bold text-vino-900">Confirmar acción</h3>
            <p id="modal-confirmacion-mensaje" class="mt-2 text-sm leading-relaxed text-stone-600"></p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" id="modal-cancelar"
                        class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-50">
                    Cancelar
                </button>
                <button type="button" id="modal-aceptar"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    Sí, continuar
                </button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('modal-confirmacion');
            if (!modal) {
                return;
            }

            const panel = document.getElementById('modal-confirmacion-panel');
            const mensaje = document.getElementById('modal-confirmacion-mensaje');
            const btnAceptar = document.getElementById('modal-aceptar');
            const btnCancelar = document.getElementById('modal-cancelar');
            let formPendiente = null;

            function abrir(texto, form) {
                mensaje.textContent = texto;
                formPendiente = form;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(function () {
                    panel.classList.remove('scale-95', 'opacity-0');
                    panel.classList.add('scale-100', 'opacity-100');
                });
            }

            function cerrar() {
                panel.classList.remove('scale-100', 'opacity-100');
                panel.classList.add('scale-95', 'opacity-0');
                setTimeout(function () {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 150);
                formPendiente = null;
            }

            document.querySelectorAll('form[data-confirm]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmado === '1') {
                        return;
                    }
                    event.preventDefault();
                    abrir(form.dataset.confirm, form);
                });
            });

            btnAceptar.addEventListener('click', function () {
                if (formPendiente) {
                    formPendiente.dataset.confirmado = '1';
                    formPendiente.submit();
                }
            });

            btnCancelar.addEventListener('click', cerrar);
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    cerrar();
                }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    cerrar();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
