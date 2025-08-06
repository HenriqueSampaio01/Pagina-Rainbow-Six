const params = new URLSearchParams(window.location.search);
const mensagemDiv = document.getElementById('mensagem-usuario');

if (params.has('erro')) {
    mensagemDiv.style.color = 'red';
    const erro = params.get('erro');

    if (erro === 'email_duplicado') {
        mensagemDiv.textContent = 'Este e-mail já está cadastrado.';
    } else if (erro === 'campos_vazios') {
        mensagemDiv.textContent = 'Preencha todos os campos obrigatórios.';
    } else {
        mensagemDiv.textContent = 'Erro ao processar o cadastro. Tente novamente.';
    }
}

if (params.has('sucesso')) {
    mensagemDiv.style.color = 'green';
    mensagemDiv.textContent = 'Cadastro realizado com sucesso!';
}