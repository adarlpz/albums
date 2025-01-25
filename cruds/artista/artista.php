<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); 
    exit();
}
?>
<?php
$conn = new mysqli("localhost", "root", "", "albums");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_artista = $_GET['id'];
    $sql = "
        SELECT 
            artista.nombre AS nombre_artista,
            artista.bio,
            genero.nombregenero AS genero_artista,
            pais.nombrepais AS pais_artista
        FROM 
            artista
        INNER JOIN 
            genero ON artista.id_genero = genero.id_genero
        INNER JOIN 
            pais ON artista.id_pais = pais.id_pais
        WHERE 
            artista.id_artista = $id_artista;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $artista = $result->fetch_assoc();
    } else {
        echo "Artista no encontrado.";
        exit;
    }

    $sql_biografias = "SELECT bio FROM artista WHERE id_artista = $id_artista";
    $result_biografias = $conn->query($sql_biografias);

    $biografias = [];
    if ($result_biografias->num_rows > 0) {
        while ($row = $result_biografias->fetch_assoc()) {
            $biografias[] = $row;
        }
    }
} else {
    echo "ID del artista no proporcionado.";
    exit;
}

$conn->close();
?>
<html>

<head>
    <link rel="stylesheet" href="http://localhost/albums/cruds/artista/artista.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="toolbar">
        <div>
            <p class="title">Detalles Artista</p>
        </div>

        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>

            <div class="botones">
            <a href="http://localhost/albums/cruds/artista/eliminar.php?id=<?php echo $id_artista; ?>">
                <img width="35px" src="http://localhost/albums/icons/basura.png">
            </a>
            <a href="http://localhost/albums/cruds/artista/editar.php?id=<?php echo $id_artista; ?>">
                <img width="35px" src="http://localhost/albums/icons/lapiz.png">
            </a>
        </div>

            <?php else : ?>
            <?php endif; ?>
    </div>

    <div class="head">
        <div class="idk">
            <div>
                <p class="title"><?php echo $artista['nombre_artista']; ?></p>
                <p class="title"><?php echo $artista['bio']; ?></p>
                <p class="title"><?php echo $artista['genero_artista']; ?></p>
                <p class="title"><?php echo $artista['pais_artista']; ?></p>
            </div>
        </div>
    </div>
</body>

</html>
