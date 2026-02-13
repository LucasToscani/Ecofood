<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../EcoFood/Css/login.css">
    <title>Login</title>
    <style>
    p{
    color: white;
    }
</style>
</head>
<body>

    <?php
    error_reporting(0);
    ini_set('display_errors', 0);
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION['logado'])) {
        echo "Você ainda não se conectou!";
    }

    // Mostra mensagem de erro, se existir
    if (!empty($_SESSION['erro'])) {
        echo $_SESSION['erro'] ;
    }
?>

    <form class="box" action="dashboard_mercado.html" method="post" onsubmit="return validarlogin();">
        <h1>Login</h1>
        <input type="text" id="login" name="usuario" placeholder="Username" required>
        <input type="password" name="senha" placeholder="Password">
        <input type="submit" name="enviar" value="enviar" required>
        <p>/A senha é projeto!/</p>
        
        <a href="form.html"><button type="button" class="btn-cadastro">Cadastro</button></a>
    </form>


    
</body>
</html>