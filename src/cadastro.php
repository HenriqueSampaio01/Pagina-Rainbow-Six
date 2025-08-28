<?php
$host = "db";
$nome = "root";
$senha = "root";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $nome, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");

    //Validando e obtendo dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha =  $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $erros = [];

    if (empty($nome)) {
        $erros[] = 'Nome é obrigatório';
    } elseif (strlen($nome) < 3) {
        $erros[] = 'Nome deve ter pelo menos 3 caracteres';
    }

    // Validação do email
    if (empty($email)) {
        $erros[] = 'E-mail é obrigatório';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido';
    }

    // Validação da senha
    if (empty($senha)) {
        $erros[] = 'Senha é obrigatória';
    } elseif (strlen($senha) < 6) {
        $erros[] = 'Senha deve ter pelo menos 6 caracteres';
    }

    // Validação do telefone
    if (empty($telefone)) {
        $erros[] = 'Telefone é obrigatório';
    } elseif (!preg_match('/^[0-9]{8,15}$/', $telefone)) {
        $erros[] = 'Telefone inválido (8-15 dígitos)';
    }

    $check = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->fetchColumn() > 0) {
        header("Location: cadastro.html?erro=email_duplicado");
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    //Preparando para inserção
    $sql = "INSERT INTO cadastro (nome, email, senha, telefone)
            VALUES (:nome, :email, :senha, :telefone,)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':telefone' => $telefone,
    ]);

    header("Location: login.html?sucesso=1");
    exit;
} catch (PDOException $e) {
    header("Location: cadastro.html?erro=erro_banco");
    exit;
}
?>