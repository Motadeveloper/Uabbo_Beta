document.addEventListener("DOMContentLoaded", () => {
    // Seleção dos elementos no DOM
    const followBtn = document.getElementById('followBtn');
    const followerCount = document.getElementById('followersCount');
    const followingCount = document.getElementById('followingCount');
    
    // Se o botão ou os contadores não existirem, loga erro
    if (!followBtn || !followerCount || !followingCount) {
        console.error('Elementos "followBtn", "followersCount" ou "followingCount" não encontrados.');
        return;
    }

    // Função para ocultar o botão "Seguir" se o usuário estiver no seu próprio perfil
    function hideFollowButtonIfOwnProfile() {
        const loggedUserId = window.loggedUserId; // Variável global com o ID do usuário logado
        const profileUserId = followBtn.getAttribute('data-user-id'); // ID do usuário do perfil

        // Se o ID do usuário logado for o mesmo do perfil, oculta o botão
        if (loggedUserId === profileUserId) {
            followBtn.style.display = 'none'; // Oculta o botão "Seguir"
        } else {
            followBtn.style.display = 'block'; // Garante que o botão "Seguir" está visível em outros casos
        }
    }

    // Chama a função para ocultar o botão "Seguir" se o usuário estiver no seu próprio perfil
    hideFollowButtonIfOwnProfile();

    // Verifica se o usuário está logado
    const isLoggedIn = window.isUserLoggedIn; // Variável global injetada no HTML

    // Se o usuário não estiver logado, o botão de seguir deve redirecionar para login
    if (!isLoggedIn) {
        followBtn.addEventListener('click', () => {
            window.location.href = '/login'; // Redireciona para o login
        });
    } else {
        // Verifica se o usuário já segue
        const userId = followBtn.getAttribute('data-user-id');

        // Requisição para verificar se o usuário já segue o outro
        fetch(`/is-following/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.isFollowing) {
                    followBtn.innerText = "Deixar de Seguir";
                    followBtn.style.backgroundColor = "#dc3545"; // Cor vermelha para "Deixar de Seguir"
                } else {
                    followBtn.innerText = "Seguir";
                    followBtn.style.backgroundColor = "#00FF00"; // Cor laranja para "Seguir"
                }
            })
            .catch(error => {
                console.error('Erro ao verificar seguimento:', error);
            });

        // Função para alternar entre "Seguir" e "Deixar de Seguir"
        followBtn.addEventListener('click', () => {
            const action = followBtn.innerText === "Seguir" ? "follow" : "unfollow";
            
            // Envia a requisição para o servidor para seguir ou deixar de seguir
            fetch(`/follow-toggle/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza o texto e a cor do botão
                    if (action === "follow") {
                        followBtn.innerText = "Deixar de Seguir";
                        followBtn.style.backgroundColor = "#dc3545"; // Cor vermelha para "Deixar de Seguir"
                    } else {
                        followBtn.innerText = "Seguir";
                        followBtn.style.backgroundColor = "#00FF00"; // Cor laranja para "Seguir"
                    }

                    // Atualiza o contador de seguidores no frontend
                    followerCount.innerText = data.followersCount;
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar requisição:', error);
            });
        });
    }

    // Modal de Seguidores
    const followersModal = document.getElementById('followersModal');
    const closeFollowersModal = document.getElementById('closeFollowersModal');
    
    if (followersModal && closeFollowersModal) {
        document.querySelector('.user-stats p').addEventListener('click', (e) => {
            if (e.target.innerText.includes('Seguidores')) {
                followersModal.style.display = "block";  // Exibe o modal de seguidores
            } else if (e.target.innerText.includes('Seguindo')) {
                followingModal.style.display = "block";  // Exibe o modal de seguidos
            }
        });

        closeFollowersModal.addEventListener('click', () => {
            followersModal.style.display = "none";  // Fecha o modal de seguidores
        });

        // Fechar o modal de seguidores ao clicar fora
        window.addEventListener('click', (event) => {
            if (event.target == followersModal) {
                followersModal.style.display = "none";  // Fecha o modal de seguidores
            }
        });
    }

    // Modal de Seguindo
    const followingModal = document.getElementById('followingModal');
    const closeFollowingModal = document.getElementById('closeFollowingModal');
    
    if (followingModal && closeFollowingModal) {
        closeFollowingModal.addEventListener('click', () => {
            followingModal.style.display = "none";  // Fecha o modal de seguidos
        });

        // Fechar o modal de seguidos ao clicar fora
        window.addEventListener('click', (event) => {
            if (event.target == followingModal) {
                followingModal.style.display = "none";  // Fecha o modal de seguidos
            }
        });
    }
});

/*document.addEventListener("DOMContentLoaded", () => {
    const likeBtn = document.getElementById('likeBtn');
    const likeCount = document.getElementById('likeCount');
    
    if (likeBtn && likeCount) {
        const userId = likeBtn.getAttribute('data-user-id');

        // Verifica se o usuário já curtiu o perfil
        fetch(`/is-liked/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.isLiked) {
                    likeBtn.innerText = "Você já curtiu!";
                    likeBtn.disabled = true;  // Desabilita o botão se já curtiu
                }
            })
            .catch(error => {
                console.error('Erro ao verificar like:', error);
            });

        // Verifica se o usuário está logado
        const isLoggedIn = window.isUserLoggedIn;

        if (!isLoggedIn) {
            likeBtn.addEventListener('click', () => {
                window.location.href = '/login'; // Redireciona para a página de login
            });
        } else {
            // Adiciona um like
            likeBtn.addEventListener('click', () => {
                fetch(`/add-like/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeBtn.innerText = "Você curtiu!";
                        likeBtn.disabled = true;

                        // Animação de curtir
                        const heart = document.createElement('div');
                        heart.classList.add('heart');
                        heart.innerHTML = "❤️";
                        document.body.appendChild(heart);
                        heart.style.left = e.clientX + 'px';
                        heart.style.top = e.clientY + 'px';
                        heart.addEventListener('animationend', () => {
                            heart.remove();
                        });

                        // Atualiza o contador de likes
                        likeCount.innerText = parseInt(likeCount.innerText) + 1;
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => {
                    console.error('Erro ao adicionar like:', error);
                });
            });
        }
    }
});
*/

