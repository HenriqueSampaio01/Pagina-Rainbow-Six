<?php
$host = "mysql";
$usuario = "user";
$senha = "userpass";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");

    //Validando e obtendo dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha =  $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $genero = isset($_POST['genero']) && in_array($_POST['genero'], ['0', '1']) ? intval($_POST['genero']) : null;

    if (empty($nome) || empty($email) || empty($senha) || empty($telefone)) {
        header("Location: cadastro.html?erro=campos_vazios");
        exit;
    }

    $check = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->fetchColumn() > 0) {
        header("Location: cadastro.html?erro=email_duplicado");
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    //Preparando para inserção
    $sql = "INSERT INTO cadastro (nome, email, senha, telefone, genero)
            VALUES (:nome, :email, :senha, :telefone, :genero)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':telefone' => $telefone,
        ':genero' => $genero,
    ]);

    header("Location: cadastro.html?sucesso=1");
    exit;
} catch (PDOException $e) {
    header("Location: cadastro.html?erro=erro_banco");
    exit;
}
