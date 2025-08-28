<?php
header('Content-Type: application/json');

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
        $erros[] = 'Insira o seu nome';
    } elseif (strlen($nome) < 3) {
        $erros[] = 'Nome deve ter no mínimo 3 caracteres';
    }

    // Validação do email
    if (empty($email)) {
        $erros[] = 'Digite o seu email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido';
    }

    // Validação da senha
    if (empty($senha)) {
        $erros[] = 'Digite a sua senha';
    } elseif (strlen($senha) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres';
    }

    // Validação do telefone
    if (empty($telefone)) {
        $erros[] = 'Insira seu número de telefone';
    } elseif (!preg_match('/^[0-9]{8,15}$/', $telefone)) {
        $erros[] = 'Telefone inválido, deve conter (8-15 dígitos)';
    }

    // Verificar se há erros de validação
    if (!empty($erros)) {
        echo json_encode([
            'sucesso' => false,
            'erros' => $erros
        ]);
        exit;
    }

    $check = $pdo->prepare("SELECT COUNT(*) FROM cadastro WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->fetchColumn() > 0) {
        echo json_encode([
            'sucesso' => false,
            'erros' => ['E-mail já cadastrado']
        ]);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    //Preparando para inserção
    $sql = "INSERT INTO cadastro (nome, email, senha, telefone)
            VALUES (:nome, :email, :senha, :telefone)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':telefone' => $telefone,
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Cadastro realizado com sucesso!'
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'sucesso' => false,
        'erros' => ['Erro no banco de dados. Tente novamente.']
    ]);
}
?>