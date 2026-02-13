<?php
// processa_doacao.php

// Incluindo o arquivo de conexão com o banco de dados
require '../db_connection.php';

// Captura os dados do formulário
$nome_fantasia = $_POST['nome_fantasia'];
$razao_social = $_POST['razao_social'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cnpj = $_POST['cnpj'];
$tipo_registro = $_POST['tipo_empresa'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

// Captura dados do endereço e garante que o CEP tenha no máximo 8 caracteres (ou ajuste conforme a definição do banco)
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$logradouro = $_POST['logradouro'];
$cep = substr(preg_replace('/[^0-9]/', '', $_POST['cep']), 0, 8); // Limita o CEP a 8 dígitos
$bairro = $_POST['bairro'];
$municipio = $_POST['municipio'];
$estado = $_POST['estado'];

// Inicia uma transação
$conn->begin_transaction();

try {
    // Insere na tabela Empresa
    $stmt_empresa = $conn->prepare("INSERT INTO Empresa (CNPJ, Nome_Fantasia, Razao_Social) VALUES (?, ?, ?)");
    $stmt_empresa->bind_param("sss", $cnpj, $nome_fantasia, $razao_social);
    if (!$stmt_empresa->execute()) {
        throw new Exception("Erro ao inserir Empresa: " . $stmt_empresa->error);
    }

    // Insere na tabela Endereco
    $stmt_endereco = $conn->prepare("INSERT INTO Endereco (Numero, Complemento, Logradouro, CEP, Bairro, Municipio, Estado, FK_ID_Empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_endereco->bind_param("ssssssss", $numero, $complemento, $logradouro, $cep, $bairro, $municipio, $estado, $cnpj);
    if (!$stmt_endereco->execute()) {
        throw new Exception("Erro ao inserir Endereço: " . $stmt_endereco->error);
    }

    // Insere na tabela Contato
    $stmt_contato = $conn->prepare("INSERT INTO Contato (DDD, Telefone, Celular, Email, FK_CNPJ) VALUES (?, ?, ?, ?, ?)");
    $ddd = substr($telefone, 1, 2); // Pega o DDD
    $telefone_formatado = substr($telefone, 4); // Pega o número sem o DDD
    $stmt_contato->bind_param("sssss", $ddd, $telefone_formatado, $telefone_formatado, $email, $cnpj);
    if (!$stmt_contato->execute()) {
        throw new Exception("Erro ao inserir Contato: " . $stmt_contato->error);
    }

    // Insere na tabela Tipo_Empresa
    $tipo_descricao = ($tipo_registro == '1') ? '1' : '2';
    $stmt_tipo_empresa = $conn->prepare("INSERT INTO Tipo_Empresa (ID_Tipo_Empresa, Descricao, FK_ID_Empresa) VALUES (NULL, ?, ?)");
    $stmt_tipo_empresa->bind_param("ss", $tipo_descricao, $cnpj);
    if (!$stmt_tipo_empresa->execute()) {
        throw new Exception("Erro ao inserir Tipo_Empresa: " . $stmt_tipo_empresa->error);
    }

    // Insere na tabela Login
    $stmt_login = $conn->prepare("INSERT INTO Login (CNPJ, Senha) VALUES (?, ?)");
    $stmt_login->bind_param("ss", $cnpj, $senha);
    if (!$stmt_login->execute()) {
        throw new Exception("Erro ao inserir Login: " . $stmt_login->error);
    }

    // Confirma a transação
    $conn->commit();

    echo "Cadastro realizado com sucesso!";

    // Redireciona para a página de sucesso
    header("Location: dashboard_mercado.php");
    exit();

} catch (Exception $e) {
    // Em caso de erro, desfaz a transação
    $conn->rollback();
    echo "Erro ao cadastrar: " . $e->getMessage();
}

// Verifica se as variáveis estão definidas antes de fechar as conexões
if (isset($stmt_empresa)) $stmt_empresa->close();
if (isset($stmt_endereco)) $stmt_endereco->close();
if (isset($stmt_contato)) $stmt_contato->close();
if (isset($stmt_tipo_empresa)) $stmt_tipo_empresa->close();
if (isset($stmt_login)) $stmt_login->close();
$conn->close();
?>