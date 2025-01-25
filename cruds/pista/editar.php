<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id_pista = intval($_GET['id']);

    // Obtener información de la pista
    $query = "
        SELECT 
            p.nombre_pista, 
            p.duracion, 
            p.letras, 
            p.id_genero, 
            p.id_album 
        FROM pista p 
        WHERE p.id_pista = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_pista);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pista = $result->fetch_assoc();
    } else {
        echo "<p>No se encontró la pista.</p>";
        exit();
    }
    $stmt->close();

    // Obtener géneros
    $query_generos = "SELECT id_genero, nombregenero FROM genero";
    $result_generos = $conn->query($query_generos);

    // Obtener álbumes
    $query_albumes = "SELECT id_album, titulo FROM album";
    $result_albumes = $conn->query($query_albumes);
} else {
    echo "<p>ID de pista no especificado.</p>";
    exit();
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_pista = $conn->real_escape_string($_POST['nombre_pista']);
    $duracion = intval($_POST['duracion']);
    $letras = $conn->real_escape_string($_POST['letras']);
    $id_genero = intval($_POST['id_genero']);
    $id_album = intval($_POST['id_album']);

    $query_update = "
        UPDATE pista 
        SET 
            nombre_pista = ?, 
            duracion = ?, 
            letras = ?, 
            id_genero = ?, 
            id_album = ? 
        WHERE id_pista = ?
    ";

    $stmt = $conn->prepare($query_update);
    $stmt->bind_param("sisiii", $nombre_pista, $duracion, $letras, $id_genero, $id_album, $id_pista);

    if ($stmt->execute()) {
        echo "<p>Pista actualizada correctamente.</p>";
    } else {
        echo "<p>Error al actualizar la pista: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editar.css">
</head>
<body>
    <p class="title" >Editar Pista</p>
    <form action="" method="POST">
        <p class="title" >Nombre de la pista</p>
        <input type="text" name="nombre_pista" id="nombre_pista" value="<?php echo htmlspecialchars($pista['nombre_pista']); ?>" required>

        <p class="title" >Duracion(segundos)</p>
        <input type="number" name="duracion" id="duracion" value="<?php echo intval($pista['duracion']); ?>" required>

        <p class="title" >Letras</p>
        <textarea name="letras" id="letras" rows="5"><?php echo htmlspecialchars($pista['letras']); ?></textarea>

        <label for="id_genero">Género</label>
        <select name="id_genero" id="id_genero" required>
            <?php while ($genero = $result_generos->fetch_assoc()): ?>
                <option value="<?php echo $genero['id_genero']; ?>" 
                    <?php echo $genero['id_genero'] == $pista['id_genero'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($genero['nombregenero']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <p class="title" >Album</p>
        <select name="id_album" id="id_album" required>
            <?php while ($album = $result_albumes->fetch_assoc()): ?>
                <option value="<?php echo $album['id_album']; ?>" 
                    <?php echo $album['id_album'] == $pista['id_album'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($album['titulo']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Actualizar Pista</button>
    </form>
</body>
</html>
