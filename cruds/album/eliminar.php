<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id_album = $_GET['id'];

    $sql_album = "SELECT * FROM album WHERE id_album = $id_album";
    $result_album = $conn->query($sql_album);

    if ($result_album->num_rows > 0) {
        $album = $result_album->fetch_assoc(); 
    } else {
        echo "<p>El álbum no existe.</p>";
        exit;
    }

    $sql_pistas = "SELECT id_pista, nombre_pista, num_pista FROM pista WHERE id_album = $id_album";
    $result_pistas = $conn->query($sql_pistas);
    $pistas = [];
    while ($row_pista = $result_pistas->fetch_assoc()) {
        $pistas[] = $row_pista; 
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_album'])) {
    $id_album = $_POST['id_album'];

    if (isset($_POST['delete_album'])) {
        $conn->query("DELETE FROM resena WHERE id_album = $id_album");
        $conn->query("DELETE FROM pista WHERE id_album = $id_album");
        $conn->query("DELETE FROM album WHERE id_album = $id_album");
        echo "<p class='success'>Álbum eliminado completamente.</p>";
    } else {
        if (isset($_POST['delete_pistas'])) {
            foreach ($_POST['delete_pistas'] as $id_pista) {
                $conn->query("DELETE FROM pista WHERE id_pista = $id_pista");
            }
        }
        if (isset($_POST['delete_resenas'])) {
            $conn->query("DELETE FROM resena WHERE id_album = $id_album");
        }

        echo "<p class='success'>Se eliminaron los campos seleccionados.</p>";
    }

    $conn->close();
    exit;
} else {
    echo "<p>Solicitud no válida.</p>";
    exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="album.css">
</head>
<body>
    <p class="title" >Eliminar Álbum</p>
    <form action="eliminar.php" method="POST">
        <input type="hidden" name="id_album" value="<?php echo $id_album; ?>">

        <p class="title" >Detalles del Álbum</p>
        <p class="title" ><?php echo $album['titulo']; ?></p>
        <p class="title" ><?php echo $album['anio']; ?></p>
        <p class="title" ><?php echo $album['duracion']; ?> segundos</p>
        <p class="title" ><?php echo $album['cantidad_pistas']; ?></p>

        <p class="title" >¿Qué desea eliminar?</p>
        <div>
            <input type="checkbox" id="delete_resenas" name="delete_resenas">
            <label for="delete_resenas">Eliminar todas las reseñas</label>
        </div>
        <div>
            <input type="checkbox" id="delete_album" name="delete_album">
            <label for="delete_album">Eliminar todo el álbum (incluye pistas y reseñas)</label>
        </div>

        <p class="title" >Eliminar Pistas Específicas</p>
        <?php if (!empty($pistas)) { ?>
            <?php foreach ($pistas as $pista) { ?>
                <div>
                    <input type="checkbox" id="pista_<?php echo $pista['id_pista']; ?>" name="delete_pistas[]" value="<?php echo $pista['id_pista']; ?>">
                    <label for="pista_<?php echo $pista['id_pista']; ?>">
                        <?php echo "Pista " . $pista['num_pista'] . ": " . $pista['nombre_pista']; ?>
                    </label>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No hay pistas asociadas a este álbum.</p>
        <?php } ?>

        <button type="submit">Eliminar</button>
    </form>
</body>
</html>
