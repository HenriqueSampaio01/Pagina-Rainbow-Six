function validarformulario() {
    const nomeInput = document.getElementById('nome');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');
    const telefoneInput = document.getElementById('telefone');
    const generoRadios = document.querySelectorAll('input[name="genero"]');
    const mensagemUsuario = document.getElementById('mensagem-usuario');

    mensagemUsuario.innerHTML = '';
    mensagemUsuario.className = 'mensagem';
    nomeInput.classList.remove('erro');
    emailInput.classList.remove('erro');
    senhaInput.classList.remove('erro');
    telefoneInput.classList.remove('erro');

    const nome = nomeInput.value.trim();
    const email = emailInput.value.trim();
    const senha = senhaInput.value.trim();
    const telefone = telefoneInput.value.trim();

    if (!nome || !email || !senha || !telefone) {
        mensagemUsuario.innerHTML = 'Preencha todos os campos obrigatórios.';
        mensagemUsuario.classList.add('erro-msg');
        if (!nome) nomeInput.classList.add('erro');
        if (!email) emailInput.classList.add('erro');
        if (!senha) senhaInput.classList.add('erro');
        if (!telefone) telefoneInput.classList.add('erro');
        return false;
    }

    let generoSelecionado = false;
    generoRadios.forEach(radio => {
        if (radio.checked) {
            generoSelecionado = true;
        }
    });

    if (!generoSelecionado) {
        mensagemUsuario.innerHTML = 'Informe seu gênero.';
        mensagemUsuario.classList.add('erro-msg');
        return false;
    }

    const regexTelefone = /^[0-9]{8,15}$/;
    if (!regexTelefone.test(telefone)) {
        mensagemUsuario.innerHTML = 'Telefone inválido.';
        mensagemUsuario.classList.add('erro-msg');
        telefoneInput.classList.add('erro');
        return false;
    }

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        mensagemUsuario.innerHTML = 'E-mail inválido.';
        mensagemUsuario.classList.add('erro-msg');
        emailInput.classList.add('erro');
        return false;
    }
    mensagemUsuario.innerHTML = 'Formulário enviado com sucesso!';
    mensagemUsuario.classList.add('sucesso-msg');
}