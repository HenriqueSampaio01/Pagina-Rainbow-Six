<?php
session_start();

$host = "db";
$nome = "root";
$senha = "root";
$banco = "cadastro";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $nome, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $erros = [];

        // Validação dos campos
        if (empty($nome)) {
            $erros[] = 'Nome é obrigatório';
        }

        if (empty($senha)) {
            $erros[] = 'Senha é obrigatória';
        }

        // Se houver erros, redireciona
        if (!empty($erros)) {
            $erroString = urlencode(implode('|', $erros));
            header("Location: login.html?erro=$erroString");
            exit;
        }

        // Busca usuário
        $sql = "SELECT * FROM cadastro WHERE nome = :nome";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nome]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            header("Location: area-restrita.html");
            exit;
        } else {
            header("Location: login.html?erro=credenciais_invalidas");
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