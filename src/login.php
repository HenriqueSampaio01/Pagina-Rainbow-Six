<?php
$host = "db";
$nome = "root";
$senha = "root";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $nome, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
       $nome = $_POST['nome'] ?? '';
       $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($senha)) {
        header("Location: login.html?erro=campos_vazios");
        exit;
        }

    $sql = "SELECT * FROM cadastro WHERE nome = :nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);

    $nome = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($nome && password_verify($senha, $nome['senha'])) {
            $_SESSION['nome'] = $nome['nome'];
            header("Location: area-restrita.html");
            exit;
        } else {
            header("Location: login.html?erro=invalido");
            exit;
        }
    } else {
        header("Location: login.html");
        exit;
    }
} catch (PDOException $e) {
    header("Location: login.html?erro=erro_banco");
    exit;
}
?>