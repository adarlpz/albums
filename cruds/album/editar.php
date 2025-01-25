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

    $sql_artistas = "SELECT id_artista, nombre FROM artista";
    $result_artistas = $conn->query($sql_artistas);

    $sql_bandas = "SELECT id_banda, nombre FROM banda";
    $result_bandas = $conn->query($sql_bandas);

    $sql_disqueras = "SELECT id_disquera, nombredisquera FROM disquera";
    $result_disqueras = $conn->query($sql_disqueras);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_album'])) {
    $id_album = $_POST['id_album'];
    $titulo = $_POST['titulo'];
    $id_artista = $_POST['id_artista'] ?: NULL; // Manejar valores vacíos
    $id_banda = $_POST['id_banda'] ?: NULL; 
    $id_disquera = $_POST['id_disquera'] ?: NULL; 
    $anio = $_POST['anio'];
    $cantidad_pistas = $_POST['cantidad_pistas'];
    $duracion = $_POST['duracion'];
    $nproducto = $_POST['nproducto'];
    $formato = $_POST['formato'];
    $cexplicito = $_POST['cexplicito'];

    // Actualizar los datos del álbum
    $sql_update = "UPDATE album SET 
                    titulo = '$titulo',
                    id_artista = " . ($id_artista === NULL ? "NULL" : "'$id_artista'") . ",
                    id_banda = " . ($id_banda === NULL ? "NULL" : "'$id_banda'") . ",
                    id_disquera = " . ($id_disquera === NULL ? "NULL" : "'$id_disquera'") . ",
                    anio = '$anio',
                    cantidad_pistas = '$cantidad_pistas',
                    duracion = '$duracion',
                    nproducto = '$nproducto',
                    formato = '$formato',
                    cexplicito = '$cexplicito'
                   WHERE id_album = $id_album";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p class='success'>Álbum actualizado correctamente.</p>";
    } else {
        echo "<p class='error'>Error al actualizar el álbum: " . $conn->error . "</p>";
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
    <p class="title" >Editar Álbum</p>
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/editar.css">
</head>
<body>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id_album" value="<?php echo $album['id_album']; ?>">

        <div>
            <p class="title" >Titulo</p>
            <input type="text" id="titulo" name="titulo" value="<?php echo $album['titulo']; ?>" required>
        </div>

        <div>
        <p class="title" >Artista</p>
            <select id="id_artista" name="id_artista">
                <option value="" <?php echo is_null($album['id_artista']) ? 'selected' : ''; ?>>Sin seleccionar</option>
                <?php while ($row = $result_artistas->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_artista']; ?>" <?php echo ($album['id_artista'] == $row['id_artista']) ? 'selected' : ''; ?>>
                        <?php echo $row['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <p class="title" >Banda</p>
            <select id="id_banda" name="id_banda">
                <option value="" <?php echo is_null($album['id_banda']) ? 'selected' : ''; ?>>Sin seleccionar</option>
                <?php while ($row = $result_bandas->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_banda']; ?>" <?php echo ($album['id_banda'] == $row['id_banda']) ? 'selected' : ''; ?>>
                        <?php echo $row['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <p class="title" >Disquera</p>
            <select id="id_disquera" name="id_disquera">
                <option value="" <?php echo is_null($album['id_disquera']) ? 'selected' : ''; ?>>Sin seleccionar</option>
                <?php while ($row = $result_disqueras->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_disquera']; ?>" <?php echo ($album['id_disquera'] == $row['id_disquera']) ? 'selected' : ''; ?>>
                        <?php echo $row['nombredisquera']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div>
            <p class="title" >Año</p>
            <input type="number" id="anio" name="anio" value="<?php echo $album['anio']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
        </div>

        <div>
            <p class="title" >Cantidad de Pistas</p>
            <input type="number" id="cantidad_pistas" name="cantidad_pistas" value="<?php echo $album['cantidad_pistas']; ?>" required>
        </div>

        <div>
            <p class="title" >Duracion(en minutos)</p>
            <input type="number" id="duracion" name="duracion" value="<?php echo $album['duracion']; ?>" step="0.01" required>
        </div>

        <div>
            <p class="title" >Código de Producto</p>
            <input type="text" id="nproducto" name="nproducto" value="<?php echo $album['nproducto']; ?>" required>
        </div>

        <div>
            <p class="title" >Formato</p>
            <input type="text" id="formato" name="formato" value="<?php echo $album['formato']; ?>" required>
        </div>

        <div>
            <p class="title" >Contenido Explícito</p>
            <select id="cexplicito" name="cexplicito" required>
                <option value="1" <?php echo ($album['cexplicito'] == 1) ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo ($album['cexplicito'] == 0) ? 'selected' : ''; ?>>No</option>
            </select>
        </div>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
