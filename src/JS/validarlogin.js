function validarlogin() {
    const nomeinput = document.getElementById('nome').value.trim();
    const senha = document.getElementById('senha').value.trim();

    if (!nomeinput || !senha) {
        alert("Preencha todos os campos.");
        return false;
    }
    return true;
}