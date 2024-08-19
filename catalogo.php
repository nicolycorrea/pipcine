<?php
session_start(); // Inicia a sessão

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "cinema_db";

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na Conexão: " . $conn->connect_error);
}

// Função para buscar todos os filmes e suas sessões
function getFilmesESessoes($conn) {
    $sql = "SELECT f.id AS filme_id, f.nome AS filme_nome, f.genero, f.direcao, f.duracao, f.sinopse, f.capa, 
                   s.id AS sessao_id, s.hora, s.sala, s.assentos 
            FROM filmes f 
            LEFT JOIN sessoes s ON f.id = s.filme_id 
            ORDER BY f.nome, s.hora";
    return $conn->query($sql);
}

$filmesESessoes = getFilmesESessoes($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Filmes</title>
    <link rel="stylesheet" href="css/catalogo.css">
</head>
<body>
    <div class="catalog-container">
        <h1>Catálogo de Filmes</h1>
        <?php
        $ultimo_filme_id = 0;
        while ($row = $filmesESessoes->fetch_assoc()):
            if ($ultimo_filme_id != $row['filme_id']):
                if ($ultimo_filme_id != 0): ?>
                    </div> <!-- Fecha o último filme-card -->
                <?php endif; ?>

                <div class="filme-card">
                <img src="funcoes/cadastrar/catalogo/<?php echo basename($row['capa']); ?>" alt="<?php echo $row['filme_nome']; ?>">
                    <div class="filme-info">
                        <h2><?php echo $row['filme_nome']; ?></h2>
                        <p><strong>Gênero:</strong> <?php echo $row['genero']; ?></p>
                        <p><strong>Direção:</strong> <?php echo $row['direcao']; ?></p>
                        <p><strong>Duração:</strong> <?php echo $row['duracao']; ?> minutos</p>
                        <p><strong>Sinopse:</strong> <?php echo $row['sinopse']; ?></p>
                    </div>
            <?php endif; ?>

            <?php if ($row['sessao_id']): ?>
                <div class="sessao-info">
                    <p><strong>Hora:</strong> <?php echo $row['hora']; ?> | <strong>Sala:</strong> <?php echo $row['sala']; ?> | <strong>Assentos:</strong> <?php echo $row['assentos']; ?></p>
                    <a href="comprar_ingresso.php?sessao_id=<?php echo $row['sessao_id']; ?>" class="button-comprar">Ver Preço</a>
                </div>
            <?php endif; ?>

            <?php $ultimo_filme_id = $row['filme_id']; ?>
        <?php endwhile; ?>
        </div> <!-- Fecha o último filme-card -->
    </div>
</body>
</html>
