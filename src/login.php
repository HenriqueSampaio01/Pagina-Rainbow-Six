<?php
session_start();
$host = "db";
$usuario = "root";
$senha = "root";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($usuario) || empty($senha)) {
        echo "Preencha todos os campos.";
        exit;
    }

    $sql = "SELECT * FROM cadastro WHERE nome = :nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $usuario]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario['nome'];
        header("Location: area-restrita.html");
        exit;
    } else {
        echo "Usuário ou senha inválidos.";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar: " . $e->getMessage();
}
?>
