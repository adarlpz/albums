<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_artista = intval($_GET['id']); 

    $sql_verificar = "SELECT * FROM artista WHERE id_artista = $id_artista";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        $sql_eliminar = "DELETE FROM artista WHERE id_artista = $id_artista";
        if ($conn->query($sql_eliminar) === TRUE) {
            echo "<p class='success'>Artista eliminado correctamente.</p>";
            echo "<a href='http://localhost/albums/cruds/artista/'>Volver a la lista de artistas</a>";
        } else {
            echo "<p class='error'>Error al eliminar el artista: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>El artista no existe o ya fue eliminado.</p>";
    }
} else {
    echo "<p class='error'>Solicitud inválida. ID de artista no proporcionado.</p>";
}

$conn->close();
?>
