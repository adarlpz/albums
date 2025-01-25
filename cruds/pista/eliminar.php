<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_pista = intval($_GET['id']);

    $sql_verificar = "SELECT * FROM pista WHERE id_pista = $id_pista";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        $sql_eliminar = "DELETE FROM pista WHERE id_pista = $id_pista";
        if ($conn->query($sql_eliminar) === TRUE) {
            echo "<p class='success'>Pista eliminada correctamente.</p>";
            echo "<a href='http://localhost/albums/cruds/pista/'>Volver a la lista de pistas</a>";
        } else {
            echo "<p class='error'>Error al eliminar la pista: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='error'>La pista no existe o ya fue eliminada.</p>";
    }
} else {
    echo "<p class='error'>Solicitud inválida. ID de pista no proporcionado.</p>";
}

$conn->close();
?>
