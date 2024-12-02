

<link rel="stylesheet" href="{{ asset('css/perfil/body.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil/header.css') }}">
    


<head>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Perfil de Usuário</title>
<link rel="stylesheet" href="styles.css">

<body>
    <!-- Cabeçalho com a logo e capa -->
    <header class="profile-header" style="background-image: url('{{ $user->capa ?? 'https://1.bp.blogspot.com/-qoV1ydFOVfk/V-xZH03su-I/AAAAAAAAvYI/nyjy861rikQfZPuZC6hxAWZ-Vd_U4dlDQCPcB/s1600/lpromo_habbohistories_gen1.png' }}');">
        <!-- Logo do site -->
        <div class="logo-container">
            <a href="/" class="logo-link">UABBO</a>
        </div>
        <!-- Botão de alterar capa (apenas para o autor logado) -->
        @if(Auth::check() && Auth::id() === $user->id)
        <div class="change-cover-container">
            <button id="changeCoverButton" class="change-cover-btn" onclick="document.getElementById('uploadCoverInput').click()">
                Alterar Capa
            </button>
            <form id="uploadCoverForm" action="{{ route('perfil.alterarCapa', $user->id) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" id="uploadCoverInput" name="capa" accept="image/*" onchange="document.getElementById('uploadCoverForm').submit()" />
            </form>
        </div>
        @endif

        <h1 class="banner-title">TWITCH.TV/NANDOHABBO</h1>
        
        <div class="wave-container">
            <!-- Duas ondas duplicadas para movimento contínuo -->
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </header>

    <!-- Detalhes do perfil -->
    <section class="profile-section">
        
        <div class="avatar-container">
            <img src="https://www.habbo.com.br/habbo-imaging/avatarimage?user={{ $user->name }}&action=std&direction=2&head_direction=3&gesture=sml&size=l" alt="Avatar Habbo" class="avatar">
        </div>
        
            <div class="user-details">
            <h2 class="user-name">{{ $user->name }}</h2>
            <div class="user-actions">
                <!-- Botão de Seguir (sempre exibido, mas redirecionando para login caso não esteja logado) -->
                <button class="follow-btn" id="followBtn" data-user-id="{{ $user->id }}">
                    Seguir
                </button>
                
                
            </div>
            <div class="user-stats" style="margin-top: 7px; margin-bottom: 14px;">
                <p><strong>Seguidores:</strong> <span id="followersCount">{{ $user->followers()->count() }}</span>
                <strong>Seguindo:</strong> <span id="followingCount">{{ $user->following()->count() }}</span></p>
            </div>
            <p><img src="https://www.habborator.org/archive/icons/mini/tab_icon_03_community.gif" width="17" height="16" alt="Ícone Missão jogador do Habbo Hotel" style="vertical-align: middle; margin-right: 8px;"><strong>Missão:</strong> [TWITCH.TV/NANDOHABBO]</p>
            <p><img src="https://www.habborator.org/archive/icons/mini/new_19.gif" width="10" height="16" alt="Ícone Jogador online no Habbo Hotel" style="vertical-align: middle; margin-right: 8px;"><strong>Online:</strong> Não</p>
            <p><img src="https://www.habborator.org/archive/icons/mini/new_19.gif" width="10" height="16" alt="Ícone Último acesso no Habbo Hotel" style="vertical-align: middle; margin-right: 8px;"><strong>Último Acesso:</strong> 28/11/2024, 11:35</p>
            <p><img src="https://www.habborator.org/archive/icons/mini/icon_myhabbo.gif" width="28" height="22" alt="Ícone Data criação de conta Habbo" style="vertical-align: middle; margin-right: 8px;"><strong>Conta criada em:</strong> 06/08/2012</p>
            <p><img src="https://www.habborator.org/archive/icons/mini/tab_icon_09_hc.gif" width="21" height="13" alt="Ícone Habbo Clube" style="vertical-align: middle; margin-right: 8px;"><strong>Membro HC:</strong> Sim</p>
            <p><img src="https://www.habborator.org/archive/icons/mini/tools_widgets.gif" width="28" height="20" alt="Ícone Clube do Arquiteto" style="vertical-align: middle; margin-right: 8px;"><strong>Membro CA:</strong> Sim</p>
        </div>
    </section>

    <!-- Modal de Seguidores -->
    <div id="followersModal" class="modal">
        <div class="modal-content">
            <span id="closeFollowersModal" class="close">&times;</span>
            <h3>Seguidores</h3>
            <ul id="followersList"></ul>
        </div>
    </div>

    <!-- Modal de Seguindo -->
    <div id="followingModal" class="modal">
        <div class="modal-content">
            <span id="closeFollowingModal" class="close">&times;</span>
            <h3>Seguindo</h3>
            <ul id="followingList"></ul>
        </div>
    </div>

    

    <!-- Conteúdo Principal -->
    <section class="content-container">
        <div class="content-main">
            <h2>Conteúdo Principal</h2>
            <p>Aqui está o conteúdo principal que ocupa 70% da largura.</p>
        </div>
        <aside class="content-sidebar">
            <div class="sidebar-section" id="amigos-sidebar">
                <h3>Amigos (1000)</h3>
                <p>Conteúdo da sidebar Amigos.</p>
            </div>
            <div class="sidebar-section" id="grupos-sidebar">
                <h3>Grupos (23)</h3>
                <p>Conteúdo da sidebar Grupos.</p>
            </div>
            <div class="sidebar-section" id="quartos-sidebar">
                <h3>Quartos (12)</h3>
                <p>Conteúdo da sidebar Quartos.</p>
            </div>
            <div class="sidebar-section" id="emblemas-sidebar">
                <h3>Emblemas (4875)</h3>
                <p>Conteúdo da sidebar Emblemas.</p>
            </div>
        </aside>
    </section>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const changeCoverButton = document.getElementById('changeCoverButton');

        // Verifica se há uma mensagem de sucesso na sessão
        @if(session('success'))
            // Altera o botão para o estado de sucesso
            changeCoverButton.classList.add('success');
            changeCoverButton.innerHTML = `<img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0-ZOqHJwpE2QNLZfWi5jH7KqdztKjQic7_Q7EWtHIehNsABn2Uh884b5KUh_mBHbhDiiAxWh0X5odT64laka0O7r_LpjaYW4VAexdR3iwYTnfbVlf4tHr-zetwJBIp-5FemDQ-9TPe48r/s0/ui_navigator_icon_fav_on.png" alt="Ícone de sucesso"> Capa alterada com sucesso!`;

            // Após 5 segundos, volta para o estado padrão
            setTimeout(() => {
                changeCoverButton.classList.remove('success');
                changeCoverButton.innerHTML = 'Alterar Capa';
            }, 5000);
        @endif
    });
</script>
<script>
    // Passando a variável de login para o JavaScript
    window.isUserLoggedIn = @json(Auth::check());

    
</script>



    <script src="{{ asset('js/perfil/header.js') }}"></script>
    
    





