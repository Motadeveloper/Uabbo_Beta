<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Uabbo') }} | A Primeira Rede Social do Habbo Hotel </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm position-relative">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Uabbo') }} <span class="animated-beta">Beta</span>
                </a>

                <!-- Botão de sanduíche animado clean -->
                <button class="navbar-toggler custom-toggler-avatar collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    @auth
        <img 
            src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ Auth::user()->name }}&size=s&head_direction=3&direction=2&headonly=1&img_format=png&gesture=std&action=std" 
            alt="Avatar do usuário" 
            class="user-avatar">
    @else
        <img 
            src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user=4queijos&size=s&head_direction=3&direction=2&headonly=1&img_format=png&gesture=std&action=std" 
            alt="Avatar padrão" 
            class="user-avatar">
    @endauth
</button>



                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('sorteios.index') }}">{{ __('Sorteios') }}</a>
                            </li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Criar Conta') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link sorteios-link" href="{{ route('sorteios.index') }}">{{ __('Sorteios') }}</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="http://www.habbo.com.br/habbo-imaging/avatarimage?&user={{ Auth::user()->name }}&size=s&head_direction=3&direction=2&headonly=1&img_format=png&gesture=std&action=std" 
                                         alt="Avatar" 
                                         style="width: 32px; height: 32px; border-radius: 50%; margin-right: 8px;">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item w-100 text-center btn btn-outline-primary" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Sair') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            <div class="navbar-line"></div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Custom CSS -->
    <style>
        /* Animação no texto "Beta" */
        .custom-toggler-avatar {
        background-color: #f0f0f0; /* Cinza claro */
        border: none;
        border-radius: 50%;
        padding: 5px;
        width: 40px; 
        height: 40px; 
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .custom-toggler-avatar .user-avatar {
        width: 27px;
        height: 30px;
        border-radius: 50%;
    }

    .custom-toggler-avatar:focus {
    outline: none; /* Remove o outline do estado de foco */
    box-shadow: none; /* Remove sombras ao focar */
}

.custom-toggler-avatar:focus {
    outline: none; /* Remove o foco visual padrão */
}

.custom-toggler-avatar.active {
    animation: color-cycle 1.5s infinite; /* Animação contínua */
}

@keyframes color-cycle {
    0% {
        background: linear-gradient(to bottom, #ff007f 50%, #ff80b0 50%); /* Rosa */
    }
    33% {
        background: linear-gradient(to bottom, #8000ff 50%, #b080ff 50%); /* Roxo */
    }
    66% {
        background: linear-gradient(to bottom, #ff0000 50%, #ff8080 50%); /* Vermelho */
    }
    100% {
        background: linear-gradient(to bottom, #ff007f 50%, #ff80b0 50%); /* Retorna ao início */
    }
}
        .animated-beta {
            background: linear-gradient(90deg, pink, violet, pink, violet, pink, violet);
            background-size: 400% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: animate-beta 7s linear infinite;
        }

        @keyframes animate-beta {
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

        /* Linha animada no bottom */
        .navbar-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3.8px;
            background: linear-gradient(90deg, pink, violet, pink, violet, pink, violet);
            background-size: 400% 100%;
            animation: animate-line 7s linear infinite;
        }

        @keyframes animate-line {
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

        /* Botão sanduíche clean */
        .custom-toggler {
            background: none;
            outline: none;
            border: none;
            padding: 0;
        }

        .custom-toggler .toggler-icon {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 20px;
            width: 26px;
        }

        .custom-toggler .toggler-icon span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: #333;
            border-radius: 1px;
            transition: all 0.3s ease;
        }

        /* Transformações ao abrir */
        .custom-toggler.collapsed .toggler-icon span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .custom-toggler.collapsed .toggler-icon span:nth-child(2) {
            opacity: 0;
        }

        .custom-toggler.collapsed .toggler-icon span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* Desabilitar seleção de mouse/toque no botão */
        .custom-toggler, .custom-toggler .toggler-icon {
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        /* Menu mobile alinhado à esquerda */
        @media (max-width: 768px) {
            .navbar-nav {
                text-align: left;
            }
            .navbar-nav .nav-item {
                width: 100%; /* Ocupa 100% da largura */
            }
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const toggler = document.querySelector('.custom-toggler-avatar');
    
    toggler.addEventListener('click', function () {
        toggler.classList.toggle('active'); // Ativa ou desativa a classe
    });
});

    </script>
</body>
</html>
