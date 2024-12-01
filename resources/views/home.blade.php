@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/feed/home.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="Explore o feed com promoções de quartos, sorteios e interações exclusivas para jogadores de Habbo Hotel. Participe e compartilhe suas experiências.">
<meta name="keywords" content="Habbo, promoções de quartos, sorteios Habbo, comunidade Habbo, Habbo Hotel feed, fã site">
<meta name="author" content="Fernando Mota">
<meta property="og:title" content="Feed | Uabbo ">
<meta property="og:description" content="Entre no feed e descubra promoções, sorteios e eventos exclusivos da comunidade Habbo Hotel.">
<meta property="og:image" content="https://uabbo.com/img/image_event_settings.png">
<meta property="og:url" content="https://uabbo.com">
<meta name="twitter:card" content="https://uabbo.com/img/image_event_settings.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">




<div class="container">


    <body data-user-id="{{ Auth::id() }}">
    <h2>Feed</h2>

    @if(session('success'))
    <div class="alert-success-custom d-flex align-items-center p-3">
        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" 
             alt="Ícone de Sucesso" style="width: 44px; height: 40px; margin-right: 10px;">
        <span>{{ session('success') }}</span>
    </div>
@endif


  <!-- Caixa de criação de tópico com botão de minimizar -->
<div class="card mb-4 p-3" id="createTopicBox">
    <button class="btn-close-custom" onclick="toggleCreateTopicBox()"></button>
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">O que você está pensando?</h4>
    </div>
    <div id="createTopicContent" class="mt-3">

        <!-- Formulário de Divulgar Quarto -->





        <div id="promoteRoomForm" style="display: none; background-color: white; padding: 20px; border-radius: 10px;" class="card mb-4 p-3">
    <h5>Divulgar Quarto do Habbo</h5>
    <div class="d-flex align-items-center flex-wrap">
        <!-- Imagem do usuário à esquerda -->
        @auth
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ Auth::user()->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
                 alt="Avatar do usuário" class="rounded-circle me-3" width="70" height="120">
        @else
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
                 alt="Avatar padrão do Habbo" class="rounded-circle me-3" width="70" height="120">
        @endauth

        <div class="form-group w-100">
            <label for="roomId">ID do Quarto:</label>
            <input type="text" id="roomId" class="form-control" placeholder="Digite o ID do quarto">
            <div class="d-flex justify-content-between mt-2">
                <button type="button" class="btn btn-primary btn-sm me-2" onclick="{{ Auth::check() ? 'fetchRoomData()' : 'redirectToLogin()' }}">Buscar Quarto</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cancelPromotion()">Cancelar</button>
            </div>
        </div>
    </div>

    <div id="roomDetails" style="display: none; margin-top: 20px;">
        <h6>Detalhes do Quarto</h6>

        <!-- Layout responsivo para detalhes do quarto -->
        <div class="room-details" style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 15px;">
            <!-- Imagem do quarto -->
            <img id="roomThumbnail" src="" alt="Imagem do Quarto" 
                 style="width: 150px; height: 150px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">

            <!-- Informações do quarto -->
            <div style="flex: 1; min-width: 250px;">
                <p>
                    <img src="https://www.habborator.org/archive/icons/mini/new_17.gif" alt="Ícone Nome" style="width: 16px; height: 16px; margin-right: 5px; vertical-align: middle;">
                    <strong>Nome do Quarto:</strong> <span id="roomName">N/A</span>
                </p>
                <p>
                    <img src="https://www.habborator.org/archive/icons/mini/tab_icon_03_community.gif" alt="Ícone Descrição" style="width: 16px; height: 16px; margin-right: 5px; vertical-align: middle;">
                    <strong>Descrição:</strong> <span id="roomDescription">N/A</span>
                </p>
                <p>
                    <img src="https://www.habborator.org/archive/icons/mini/v20_6.gif" alt="Ícone Data" style="width: 16px; height: 16px; margin-right: 5px; vertical-align: middle;">
                    <strong>Data de Criação:</strong> <span id="roomCreationTime">N/A</span>
                </p>
                <p>
                    <img src="https://www.habborator.org/archive/icons/mini/new_19.gif" alt="Ícone Proprietário" style="width: 16px; height: 16px; margin-right: 5px; vertical-align: middle;">
                    <strong>Proprietário:</strong> <span id="roomOwner">N/A</span>
                </p>
                <p>
                    <img src="https://www.habborator.org/archive/icons/mini/tab_icon_03_community.gif" alt="Ícone Grupo" style="width: 16px; height: 16px; margin-right: 5px; vertical-align: middle;">
                    <strong>Grupo:</strong> <span id="roomGroup">N/A</span>
                </p>
                <div class="custom-button" style="display: inline-flex; align-items: center; background: linear-gradient(to bottom, #009929 50%, #008f1f 50%); color: white; font-family: Arial, sans-serif; font-size: 14px; padding: 10px 15px; text-decoration: none; clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%); transition: background 0.3s ease; border: none; cursor: pointer; margin-top: 10px;">
                    <img src="https://www.habborator.org/archive/icons/mini/new_07.gif" alt="Ícone Ir para o Quarto" style="margin-right: 10px; width: 16px; height: 16px;">
                    <span>Ir para o quarto</span>
                </div>
</div>
</div>


        <!-- Botão de confirmação -->
        <button type="button" class="btn btn-success mt-2" onclick="confirmRoomPromotion()">Divulgar</button>
    </div>


</div>


        <!-- Formulário de Denúncia do Habbo -->
<div id="reportHabboForm" style="display: none; background-color: #fff; border: none;" class="card mb-4 p-3">
    <h5 class="mb-3">Denunciar Habbo</h5>
    <div class="d-flex align-items-center mb-3">
        <!-- Avatar dinâmico -->
        <img id="habboAvatarImage" 
             src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=default&action=std&direction=2&head_direction=3&gesture=sml&size=l" 
             alt="Avatar do Habbo" 
             class="rounded-circle me-3" 
             width="70" 
             height="120">
             
        <!-- Campo para o nickname -->
        <div class="form-group w-100">
            <label for="habboNickname" class="form-label">Nickname do Habbo:</label>
            <input type="text" id="habboNickname" class="form-control" placeholder="Digite o nickname do Habbo"
                   style="background-color: #f5f5f5; border: none; box-shadow: none;">
        </div>
    </div>
    
    <!-- Motivo da denúncia -->
    <div class="form-group mb-3">
        <label for="reportReason" class="form-label">Motivo da Denúncia:</label>
        <textarea id="reportReason" class="form-control" rows="3" placeholder="Explique o motivo da denúncia"
                  style="background-color: #f5f5f5; border: none; box-shadow: none;"></textarea>
    </div>

    <!-- Prévia dos dados do Habbo -->
    <div id="reportPreview" style="display: none;">
        <p><strong>Nome:</strong> <span id="reportName">N/A</span></p>
        <p><strong>Missão:</strong> <span id="reportMotto">N/A</span></p>
        <p><strong>Online:</strong> <span id="reportOnlineStatus">N/A</span></p>
        <p><strong>Último Acesso:</strong> <span id="reportLastAccess">N/A</span></p>
        <p><strong>Membro Desde:</strong> <span id="reportMemberSince">N/A</span></p>
    </div>
    
    <!-- Botões de ação -->
    <div class="d-flex justify-content-start gap-2 mt-3">
        <button type="button" id="postReportButton" class="btn btn-danger btn-sm" style="display: none;" onclick="postHabboReport()">Postar Denúncia</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelReport()">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="fetchHabboData()">Buscar Informações</button>
    </div>
</div>




        <!-- Conteúdo principal -->
        <div class="d-flex">
            <!-- Imagem do usuário -->
            @auth
                <img id="userAvatar" src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ Auth::user()->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar do usuário" class="rounded-circle" width="70" height="120">
            @else
                <img id="userAvatar" src="https://www.habbo.com.br/habbo-imaging/avatarimage?user=4queijos&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar padrão do Habbo" class="rounded-circle" width="70" height="120">
            @endauth

            <form id="createTopicForm" method="POST" action="{{ route('topics.store') }}" class="w-100 ms-3">
                @csrf
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" maxlength="800" required placeholder="Digite seu tópico aqui" oninput="updateCharacterCount(this)"></textarea>
                    <small id="charCount" class="text-muted">800 caracteres restantes</small>
                </div>
                <div style="display: flex; gap: 8px;">
                    @auth
                        <button type="submit" class="btn btn-success mt-2" style="display: flex; align-items: center;" id="publishButton" disabled>
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgpq8cJIhKuB23UP9ou4kjnSDMflGC_rlsn_7t2iU2NztSi4EV4Gc_ckmP2n2_nlRl8c8g0Cntce7o3qbz2g9v2_9eGOszXhntOW72uB2-URGyh_hFOCNxKoZcdXuIujBmHzHDk4R1i2EK2/s0/fx_icon_114.png" alt="Ícone de Postar" style="width: 32px; height: 29px; margin-right: 8px;">
                            Postar
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success mt-2" style="display: flex; align-items: center;">
                            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgpq8cJIhKuB23UP9ou4kjnSDMflGC_rlsn_7t2iU2NztSi4EV4Gc_ckmP2n2_nlRl8c8g0Cntce7o3qbz2g9v2_9eGOszXhntOW72uB2-URGyh_hFOCNxKoZcdXuIujBmHzHDk4R1i2EK2/s0/fx_icon_114.png" alt="Ícone de Postar" style="width: 32px; height: 29px; margin-right: 8px;">
                            Postar
                        </a>
                    @endauth

                    <button type="button" class="btn btn-yellow mt-2" onclick="{{ Auth::check() ? 'togglePromoteRoomForm()' : 'redirectToLogin()' }}" style="background-color: #ffc107; border-color: #ffc107; color: #212529; display: flex; align-items: center;">
                        <img src="https://www.habboassets.com/images/catalog-icons/55" alt="Ícone" style="width: 18px; height: 18px; margin-right: 8px;">
                        Divulgar Quarto
                    </button>
                    <button type="button" class="btn btn-danger mt-2" onclick="{{ Auth::check() ? 'toggleReportHabboForm()' : 'redirectToLogin()' }}" style="display: flex; align-items: center;">
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhtap5n0qy6gLx4HIJQrnLrfUqfDM-DTbN4m8ye50xG-Y4JusdkL4TK59Oy43qmkr4Ig8eBwTYZNKbnsG7Z0VYdHENWgvaCj8pz3-vIjVS3MoXm5RPqUOmgfxTUhjZc1p6mWoxmRCDSTh0e/s0/icon_small_notification.png" alt="Ícone de Denúncia" style="width: 20px; height: 21px; margin-right: 8px;">
                        Denunciar Habbo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- Exibir os tópicos -->
    
    <div id="topicsContainer">
    <!-- Os tópicos serão carregados dinamicamente aqui -->
</div>

<div id="loadingIndicator" class="text-center mt-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Carregando...</span>
    </div>
</div>
    
        

                        
    </div>
</div>


<style>@media (max-width: 600px) {
    .room-thumbnail {
        width: 100px !important;
        height: 100px !important;
    }
}
</style>

<script src="{{ asset('js/feed/comentarios.js') }}"></script>
<script src="{{ asset('js/feed/home.js') }}"></script>
<script src="{{ asset('js/feed/postcard.js') }}"></script>
<script src="{{ asset('js/feed/quarto.js') }}"></script>
<script src="{{ asset('js/feed/report.js') }}"></script>
<script>
    window.isAuthenticated = @json(auth()->check());
</script>

@endsection
