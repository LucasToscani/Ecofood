// Função para navegação entre páginas no menu lateral
const menuItems = document.querySelectorAll('.menu-item');
const pages = document.querySelectorAll('.page');

menuItems.forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        // Remover classe 'active' de todos os itens do menu
        menuItems.forEach(i => i.classList.remove('active'));
        // Adicionar a classe 'active' ao item clicado
        this.classList.add('active');

        // Identificar a página a ser mostrada
        const pageId = this.getAttribute('data-page');
        
        // Esconder todas as páginas
        pages.forEach(page => page.classList.remove('active'));
        
        // Exibir a página correspondente ao item do menu
        document.getElementById(pageId).classList.add('active');
    });
});

// Função para selecionar ONG
const ongCards = document.querySelectorAll('.ong-card');

ongCards.forEach(card => {
    card.addEventListener('click', function() {
        // Remover a classe 'active' de todos os cartões
        ongCards.forEach(c => c.classList.remove('active'));

        // Adicionar a classe 'active' ao cartão clicado
        this.classList.add('active');

        // Exibir a ONG selecionada no console (ou pode ser enviado ao backend)
        const selectedOng = this.getAttribute('data-ong');
        console.log("ONG selecionada: " + selectedOng);
    });
});

// Função para adicionar itens à lista de doações
const addItemButton = document.getElementById('add-item');
const listaDoacoes = document.getElementById('lista-doacoes');
const formDoacao = document.getElementById('form-doacao');

addItemButton.addEventListener('click', () => {
    const produto = formDoacao.produto.value.trim();
    const quantidade = formDoacao.quantidade.value.trim();
    const validade = formDoacao.validade.value;

    if (produto && quantidade && validade) {
        const li = document.createElement('li');
        li.textContent = `${produto} - Quantidade: ${quantidade} - Validade: ${new Date(validade).toLocaleDateString()}`;

        // Botão para remover item
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Excluir';
        deleteButton.classList.add('delete-btn');
        deleteButton.onclick = () => {
            listaDoacoes.removeChild(li);
        };

        li.appendChild(deleteButton);
        listaDoacoes.appendChild(li);

        // Limpar o formulário
        formDoacao.reset();
    } else {
        alert('Por favor, preencha todos os campos!');
    }
});
