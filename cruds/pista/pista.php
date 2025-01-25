<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); 
    exit();
}
?>
<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "albums");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_pista = $_GET['id'];

    // Consulta para obtener los detalles de la pista
    $query = "
        SELECT 
            p.nombre_pista, 
            p.duracion, 
            p.letras, 
            g.nombregenero AS genero, 
            a.titulo AS album
        FROM pista p
        INNER JOIN genero g ON p.id_genero = g.id_genero
        INNER JOIN album a ON p.id_album = a.id_album
        WHERE p.id_pista = $id_pista
    ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<p>No se encontró la pista.</p>";
        exit();
    }
} else {
    echo "<p>ID de pista no especificado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Pista</title>
    <link rel="stylesheet" href="pista.css">
</head>
<body>
    <div class="toolbar">
        <div>
            <p class="title" >Detalles de la Pista</p>
        </div>

        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>
            <div class="botones">
            <a href="eliminar.php?id=<?php echo $id_pista; ?>">
                <img width="35px" src="http://localhost/albums/icons/basura.png">
            </a>
            <a href="editar.php?id=<?php echo $id_pista; ?>">
                <img width="35px" src="http://localhost/albums/icons/lapiz.png">
            </a>
        </div>
            <?php else : ?>
            <?php endif; ?>
    </div>

    <div class="details">
    <p class="title"><?php echo $row['nombre_pista']; ?></p>
    <p class="title"><?php echo $row['duracion']; ?> segundos</p>
    <p class="title"><?php echo $row['genero']; ?></p>
    <p class="title"><?php echo $row['album']; ?></p>
    <div class="title">
        <?php 
            $letras = $row['letras'];
            $letras_con_saltos = preg_replace('/([A-Z])/', "\n$1", $letras);
            echo nl2br(htmlspecialchars($letras_con_saltos)); 
        ?>
    </div>
</div>



</body>
</html>
