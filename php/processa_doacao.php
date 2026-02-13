<?php
// processa_doacao.php

// Incluindo o arquivo de conexão com o banco de dados
require 'db_connection.php'; // Certifique-se de que o caminho está correto

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto = trim($_POST['produto']);
    $quantidade = trim($_POST['quantidade']);
    $validade = $_POST['validade'];
    $dataRegistro = date('Y-m-d'); // Data atual

    // Inserindo os dados na tabela Produto
    try {
        $sql = "INSERT INTO Produto (Nome, Categoria, Quantidade, Validade, Data_Registro, FK_ID_Empresa) VALUES (:nome, :categoria, :quantidade, :validade, :data_registro, :fk_id_empresa)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $produto);
        $stmt->bindValue(':categoria', 'Categoria do Produto'); // Defina uma categoria ou mude para um campo do formulário
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':validade', $validade);
        $stmt->bindValue(':data_registro', $dataRegistro);
        $stmt->bindValue(':fk_id_empresa', 'CNPJ da Empresa'); // Defina o CNPJ da empresa que está doando

        $stmt->execute();

        echo "Doação registrada com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao registrar a doação: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>
