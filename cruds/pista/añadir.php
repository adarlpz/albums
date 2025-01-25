<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_album = $_POST['id_album'];
    $id_genero = $_POST['id_genero'];
    $duracion = $_POST['duracion'];
    $num_pista = $_POST['num_pista'];
    $letras = $_POST['letras'];
    $nombre_pista = $_POST['nombre_pista'];

    $sql = "INSERT INTO pista (id_album, id_genero, duracion, num_pista, letras, nombre_pista)
            VALUES ('$id_album', '$id_genero', '$duracion', '$num_pista', '$letras', '$nombre_pista')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Nueva pista agregada exitosamente.</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$sql_albums = "SELECT id_album, titulo FROM album";
$result_albums = $conn->query($sql_albums);

$sql_generos = "SELECT id_genero, nombregenero FROM genero";
$result_generos = $conn->query($sql_generos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Pista</title>
    <link rel="stylesheet" href="http://localhost/albums/cruds/pista/añadir.css">
</head>
<body>
    <div class="form-container">
        <p class="title">Añadir una Nueva Pista</p>
        <form action="añadir.php" method="POST">
            <div class="form-group">
                <p class="subtitle">Álbum</p>
                <select id="id_album" name="id_album" required>
                    <option value="">Seleccionar Álbum</option>
                    <?php while ($row = $result_albums->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_album']; ?>"><?php echo $row['titulo']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <p class="subtitle">Género</p>
                <select id="id_genero" name="id_genero" required>
                    <option value="">Seleccionar Género</option>
                    <?php while ($row = $result_generos->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_genero']; ?>"><?php echo $row['nombregenero']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <p class="subtitle">Duración (en segundos)</p>
                <input type="number" id="duracion" name="duracion" min="1" placeholder="Duración de la pista" required>
            </div>

            <div class="form-group">
                <p class="subtitle">Número de la Pista</p>
                <input type="number" id="num_pista" name="num_pista" min="1" placeholder="Número de la pista en el álbum" required>
            </div>

            <div class="form-group">
                <p class="subtitle">Nombre de la Pista</p>
                <input type="text" id="nombre_pista" name="nombre_pista" placeholder="Nombre de la pista" required>
            </div>

            <div class="form-group">
                <p class="subtitle">Letras</p>
                <textarea id="letras" name="letras" placeholder="Letras de la pista (opcional)"></textarea>
            </div>

            <button type="submit" class="submit-btn">Añadir Pista</button>
        </form>
    </div>
</body>
</html>
