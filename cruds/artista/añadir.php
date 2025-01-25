<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $bio = $_POST['bio'];
    $id_genero = $_POST['id_genero'];
    $id_pais = $_POST['id_pais'];

    $sql = "INSERT INTO artista (nombre, bio, id_genero, id_pais)
            VALUES ('$nombre', '$bio', '$id_genero', '$id_pais')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Nuevo artista agregado exitosamente.</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$sql_generos = "SELECT id_genero, nombregenero FROM genero";
$result_generos = $conn->query($sql_generos);

$sql_paises = "SELECT id_pais, nombrepais FROM pais";
$result_paises = $conn->query($sql_paises);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Artista</title>
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/añadir.css">
</head>
<body>
    <div class="form-container">
        <p class="title">Añadir un Nuevo Artista</p>
        <form action="añadir.php" method="POST">
            <div class="form-group">
                <p class="subtitle">Nombre Artista</p>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre del artista" required>
            </div>

            <div class="form-group">
                <p class="subtitle">Biografía</p>
                <textarea id="bio" name="bio" placeholder="Breve biografía del artista" required></textarea>
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
                <p class="subtitle">País</p>
                <select id="id_pais" name="id_pais" required>
                    <option value="">Seleccionar País</option>
                    <?php while ($row = $result_paises->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_pais']; ?>"><?php echo $row['nombrepais']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Añadir Artista</button>
        </form>
    </div>
</body>
</html>
