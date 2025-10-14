<?php
    include('../../banco/conexao.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmaSenha = $_POST['confirmaSenha'] ?? '';

        if ($senha !== $confirmaSenha) {
            header('Location: ../index/index.php?senhaerrada=1');
            exit;
        }

        if ($nome === '' || $email === '' || $senha === '') {
            header('Location: ../index/index.php?error=1');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO tbusuario (nomeUsuario, emailUsuario, senhaUsuario) VALUES (:nome, :email, :senha)");
        $executou = $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha
        ]);

        if ($executou) {
            header('Location: ../index/index.php?success=1');
            exit;
        } else {
            header('Location: ../index/index.php?error=1');
            exit;
        }
    } else {
        header('Location: ../index/index.php');
        exit;
    }
?>
