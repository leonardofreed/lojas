<?php
$servername = "localhost";
$username = "root"; // padrão do XAMPP
$password = ""; // padrão do XAMPP
$dbname = "delivery_system"; // nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    if (adicionarCliente($nome, $email, $telefone)) {
        error_log("Cliente adicionado: Nome: $nome, Email: $email, Telefone: $telefone"); // Log para verificar a adição
        echo "Cliente adicionado com sucesso!";
    } else {
        error_log("Erro ao adicionar cliente: " . $conn->error); // Log de erro
        echo "Erro ao adicionar cliente.";
    }
}

if (isset($_GET['listar']) && $_GET['listar'] == 'true') {
    // Adicionando log para verificar a listagem de clientes
    error_log("Listando clientes.");

    $clientes = listarClientes();
    $resultado = array();
    while ($cliente = $clientes->fetch_assoc()) {
        $resultado[] = $cliente;
    }
    echo json_encode($resultado);
    exit;
}

function adicionarCliente($nome, $email, $telefone) {
    global $conn;
    
    // Verificar se o cliente já existe
    $sqlVerificar = "SELECT * FROM clientes WHERE email='$email'";
    $resultado = $conn->query($sqlVerificar);
    
    if ($resultado->num_rows > 0) {
        error_log("Erro: Cliente com email $email já existe."); // Log de erro
        return false; // Retorna falso se o cliente já existir
    }
    
    $sql = "INSERT INTO clientes (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')";
    return $conn->query($sql);
}

function listarClientes() {
    // Adicionando log para verificar a execução da função
    error_log("Executando a função listarClientes.");

    global $conn;
    $sql = "SELECT * FROM clientes";
    return $conn->query($sql);
}

function atualizarCliente($id, $nome, $email, $telefone) {
    global $conn;
    $sql = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id";
    return $conn->query($sql);
}

if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    
    if (atualizarCliente($id, $nome, $email, $telefone)) {
        echo "Cliente atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar cliente.";
    }
}

function deletarCliente($id) {
    global $conn;
    $sql = "DELETE FROM clientes WHERE id=$id";
    return $conn->query($sql);
}

if (isset($_POST['deletar'])) {
    $id = $_POST['id'];
    
    if (deletarCliente($id)) {
        error_log("Cliente com ID $id deletado."); // Log para verificar a deleção
        echo "Cliente deletado com sucesso!";
    } else {
        echo "Erro ao deletar cliente.";
    }
}

// Funções para gerenciar produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionarProduto'])) {
    error_log("Tentando adicionar produto: Nome: $nomeProduto, Preço: $precoProduto"); // Log para verificar a adição

    $nomeProduto = $_POST['nomeProduto'];
    $precoProduto = $_POST['precoProduto'];
    $descricaoProduto = $_POST['descricaoProduto'];
    
    if (adicionarProduto($nomeProduto, $precoProduto, $descricaoProduto)) {
        error_log("Produto adicionado: Nome: $nomeProduto, Preço: $precoProduto"); // Log para verificar a adição
        echo "Produto adicionado com sucesso!";
    } else {
        error_log("Erro ao adicionar produto: " . $conn->error); // Log de erro
        echo "Erro ao adicionar produto.";
    }
}

function adicionarProduto($nome, $preco, $descricao) {
    global $conn;
    $sql = "INSERT INTO produtos (nome, preco, descricao) VALUES ('$nome', $preco, '$descricao')";
    
    if (!$conn->query($sql)) {
        error_log("Erro ao adicionar produto: " . $conn->error); // Log de erro
        return false; // Retorna falso se houver erro
    }
    return true; // Retorna verdadeiro se a adição for bem-sucedida
}


function listarProdutos() {
    // Adicionando log para verificar a execução da função
    error_log("Executando a função listarProdutos.");

    global $conn;
    $sql = "SELECT * FROM produtos";
    return $conn->query($sql);
}


function atualizarProduto($id, $nome, $preco, $descricao) {
    global $conn;
    $sql = "UPDATE produtos SET nome='$nome', preco=$preco, descricao='$descricao' WHERE id=$id";
    return $conn->query($sql);
}

function deletarProduto($id) {
    global $conn;
    $sql = "DELETE FROM produtos WHERE id=$id";
    return $conn->query($sql);
}

$conn->close();
?>
