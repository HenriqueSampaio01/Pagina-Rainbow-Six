<?php
session_start();
header('Content-Type: application/json');

$host = "db";
$nome = "root";
$senha = "root";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $nome, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $erros = [];

    if (empty($email)) {
        $erros[] = 'E-mail é obrigatório';
    }

    if (empty($senha)) {
        $erros[] = 'Senha é obrigatória';
    }

    if (!empty($erros)) {
        echo json_encode([
            'sucesso' => false,
            'erros' => $erros
        ]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM cadastro WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Login realizado com sucesso!',
            'redirect' => 'area-restrita.html'
        ]);
    } else {
        echo json_encode([
            'sucesso' => false,
            'erros' => ['E-mail ou senha incorretos']
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'sucesso' => false,
        'erros' => ['Erro no banco de dados. Tente novamente.']
    ]);
}
?>