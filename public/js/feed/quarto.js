function togglePromoteRoomForm() {

     // Verificar se o usuário está autenticado
    if (typeof isAuthenticated !== 'undefined' && isAuthenticated === 'false') {
        // Redirecionar para a tela de login se o usuário não estiver autenticado
        window.location.href = '/login';
        return;
    }

    // Verificar se os elementos existem antes de manipulá-los
    const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
    const promoteForm = document.getElementById('promoteRoomForm');
    const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const publishButton = document.getElementById('publishButton');
    const charCount = document.getElementById('charCount');
    const userAvatar = document.getElementById('userAvatar');

    if (reportButton) reportButton.style.display = 'none'; // Ocultar botão "Denunciar Habbo"
    if (promoteForm) promoteForm.style.display = 'block'; // Exibir o formulário "Divulgar Quarto"
    if (promoteButton) promoteButton.style.display = 'none'; // Ocultar botão "Divulgar Quarto"
    if (contentTextarea) contentTextarea.style.display = 'none'; // Ocultar textarea de pensamento
    if (publishButton) publishButton.style.display = 'none'; // Ocultar botão "Postar"
    if (charCount) charCount.style.display = 'none'; // Ocultar contador de caracteres
    if (userAvatar) userAvatar.style.display = 'none'; // Ocultar avatar de pensamento
}

function cancelPromotion() {
    // Verificar se os elementos existem antes de manipulá-los
    const reportButton = document.querySelector('button[onclick="toggleReportHabboForm()"]');
    const promoteForm = document.getElementById('promoteRoomForm');
    const promoteButton = document.querySelector('button[onclick="togglePromoteRoomForm()"]');
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const publishButton = document.getElementById('publishButton');
    const charCount = document.getElementById('charCount');
    const userAvatar = document.getElementById('userAvatar');

    if (reportButton) reportButton.style.display = 'inline-block'; // Restaurar botão "Denunciar Habbo"
    if (promoteButton) promoteButton.style.display = 'inline-block'; // Restaurar botão "Divulgar Quarto"
    if (contentTextarea) contentTextarea.style.display = 'block'; // Restaurar textarea de pensamento
    if (publishButton) publishButton.style.display = 'inline-block'; // Restaurar botão "Postar"
    if (charCount) charCount.style.display = 'block'; // Restaurar contador de caracteres
    if (userAvatar) userAvatar.style.display = 'block'; // Restaurar avatar de pensamento

    if (promoteForm) promoteForm.style.display = 'none'; // Ocultar o formulário "Divulgar Quarto"
}




function fetchRoomData() {
    const roomIdInput = document.getElementById('roomId');
    const roomId = roomIdInput?.value.trim();

    if (!roomId) {
        alert('Por favor, insira o ID do quarto.');
        return;
    }

    // URL da API do Habbo
    const apiUrl = `https://www.habbo.com.br/api/public/rooms/${roomId}`;

    fetch(apiUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Quarto não encontrado ou API indisponível.');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados do quarto recebidos:', data);

            // Atualizar elementos na prévia
            const roomName = data.name || 'N/A';
            const roomDescription = data.description || 'N/A';
            const roomOwner = data.ownerName || 'N/A';

            const creationDate = new Date(data.creationTime || Date.now());
            const dayOfWeek = creationDate.toLocaleDateString('pt-BR', { weekday: 'long' });
            const formattedDay = dayOfWeek.charAt(0).toUpperCase() + dayOfWeek.slice(1);

            const formattedDate = creationDate.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
            });

            const formattedTime = creationDate.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
            });

            // Atualizar campos da prévia
            document.getElementById('roomName').textContent = roomName;
            document.getElementById('roomDescription').textContent = roomDescription;
            document.getElementById('roomCreationTime').textContent = `${formattedDay}, ${formattedDate}, às ${formattedTime}`;
            document.getElementById('roomOwner').textContent = roomOwner;

            const groupElement = document.getElementById('roomGroup');
            if (groupElement) {
                groupElement.textContent = data.habboGroupId
                    ? `Grupo: ${data.habboGroupId}`
                    : 'Sem grupo associado.';
            }

            // Atualizar a imagem do quarto
            const roomThumbnail = document.getElementById('roomThumbnail');
            roomThumbnail.src = data.thumbnailUrl || 'https://via.placeholder.com/150';

            // Adicionar o botão "Ir para o Quarto"
            const roomButtonContainer = document.getElementById('roomButtonContainer');
            if (roomButtonContainer) {
                roomButtonContainer.innerHTML = `
                    <a href="https://www.habbo.com.br/room/${roomId}" target="_blank" class="custom-button">
                        <img src="https://www.habborator.org/archive/icons/mini/new_07.gif" alt="Ícone">
                        <span>Ir para o quarto</span>
                    </a>
                `;
            }

            // Mostrar o contêiner da prévia
            const roomDetails = document.getElementById('roomDetails');
            if (roomDetails) {
                roomDetails.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erro ao buscar dados do quarto:', error);
            alert('Erro ao buscar os dados do quarto. Verifique o ID e tente novamente.');
        });
}


function confirmRoomPromotion() {
    // Obter os dados da prévia
    const roomName = document.getElementById('roomName')?.textContent.trim() || 'N/A';
    const roomDescription = document.getElementById('roomDescription')?.textContent.trim() || 'N/A';
    const roomCreationTime = document.getElementById('roomCreationTime')?.textContent.trim() || 'N/A';
    const roomOwner = document.getElementById('roomOwner')?.textContent.trim() || 'N/A';

    const roomThumbnailElement = document.getElementById('roomThumbnail');
    const roomThumbnail = roomThumbnailElement?.src || 'https://via.placeholder.com/150';

    const roomIdInput = document.getElementById('roomId');
    const roomId = roomIdInput?.value.trim() || 'N/A';

    // Construir o conteúdo formatado com o botão "Ir para o quarto"
    const content = `
        <div style="display: flex; flex-wrap: wrap; align-items: flex-start; gap: 15px; padding: 10px;">
            <img src="${roomThumbnail}" alt="Imagem do Quarto" style="
                width: 100px; 
                height: 100px; 
                border-radius: 10px; 
                box-shadow: 0px 4px 8px rgba(0,0,0,0.2); 
                flex-shrink: 0;
                transition: width 0.3s, height 0.3s;
            " class="room-thumbnail">
            <div style="flex: 1; min-width: 200px;">
                <h3 style="margin: 0; font-size: 18px;">Divulgação do Quarto</h3>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Nome:</strong> ${roomName}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Descrição:</strong> ${roomDescription}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Data de Criação:</strong> ${roomCreationTime}</p>
                <p style="margin: 5px 0; font-size: 14px;"><strong>Proprietário:</strong> ${roomOwner}</p>
                <a href="https://www.habbo.com.br/room/${roomId}" target="_blank" 
                    style="display: inline-flex; align-items: center; background: linear-gradient(to bottom, #009929 50%, #008f1f 50%);
                    color: white; font-family: Arial, sans-serif; font-size: 14px; padding: 10px 15px; text-decoration: none;
                    clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%); transition: background 0.3s ease; border: none;
                    cursor: pointer; margin-top: 10px; width: fit-content;">
                    <img src="https://www.habborator.org/archive/icons/mini/new_07.gif" alt="Ícone" style="margin-right: 10px; width: 16px; height: 16px;">
                    Ir para o quarto
                </a>
            </div>
        </div>
    `;

    // Atualizar o conteúdo do textarea
    const contentTextarea = document.querySelector('textarea[name="content"]');
    if (!contentTextarea) {
        alert('Erro: Área de conteúdo não encontrada.');
        return;
    }
    contentTextarea.value = content;

    // Submeter o formulário automaticamente
    const topicForm = document.getElementById('createTopicForm');
    if (topicForm) {
        topicForm.submit();
    } else {
        alert('Erro: Formulário não encontrado.');
    }
}

function redirectToLogin() {
    // Redireciona o usuário para a tela de login
    window.location.href = '/login';
}
