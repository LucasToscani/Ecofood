async function isValidCNPJ(cnpj) {
    try {
        const response = await fetch(`https://www.receitaws.com.br/v1/cnpj/${cnpj}`);
        const data = await response.json();

        if (data.status === 'OK') {
            return true; // CNPJ válido
        } else {
            return false; // CNPJ inválido
        }
    } catch (error) {
        console.error("Erro ao validar CNPJ:", error);
        return false; // Em caso de erro, considerar inválido
    }
}

document.getElementById('cadastro-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const cnpjInput = document.getElementById('cnpj');
    const cnpjMessage = document.getElementById('cnpj-message');
    const cnpj = cnpjInput.value;

    // Validação do CNPJ via API
    const isCNPJValid = await isValidCNPJ(cnpj);
    if (cnpj && !isCNPJValid) {
        cnpjMessage.textContent = 'CNPJ inválido. Por favor, verifique.';
        cnpjMessage.style.display = 'block';
        return; // Impede o envio do formulário
    } else {
        cnpjMessage.textContent = ''; // Limpa a mensagem
        cnpjMessage.style.display = 'none';
    }

    // Se o CNPJ for válido, prosseguir com o envio do formulário
    alert('Cadastro realizado com sucesso!');
    this.reset(); // Limpa o formulário após o envio
});
