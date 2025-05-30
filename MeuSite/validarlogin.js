function validarlogin() {
    const nomeinput = document.getElementById('nome').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (nomeinput === '' || senha === '') {
        alert("Preencha todos os campos.");
        return false;
    } else if (nomeinput === 'admin' && senha === 'admin')
    {
        alert("Login realizado com sucesso.");
        window.location.href = "area-restrita.html";
        return false;
    }else {
        alert("Este usuário ainda não foi cadastrado.")
        return false;
    }
}