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

// Função para listar produtos
function listarProdutos() {
    global $conn;
    $sql = "SELECT * FROM produtos";
    return $conn->query($sql);
}

// Testar a listagem de produtos
$produtos = listarProdutos();
if ($produtos) {
    $resultado = array();
    while ($produto = $produtos->fetch_assoc()) {
        $resultado[] = $produto;
    }
    echo json_encode($resultado);
} else {
    echo "Erro ao listar produtos: " . $conn->error; // Log de erro
}

$conn->close();
?>
