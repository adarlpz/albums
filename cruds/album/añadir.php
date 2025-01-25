<?php
$conn = new mysqli("localhost", "root", "", "albums");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $id_artista = $_POST['id_artista'];
    $id_banda = $_POST['id_banda'];
    $id_disquera = $_POST['id_disquera'];
    $anio = $_POST['anio'];
    $duracion = $_POST['duracion'];
    $nproducto = $_POST['nproducto'];
    $formato = $_POST['formato'];
    $cexplicito = $_POST['cexplicito'];

    $portada = null;
    if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
        $directorioPortadas = "covers/"; 
        $nombrePortada = basename($_FILES['portada']['name']);
        $rutaPortada = $directorioPortadas . uniqid() . "_" . $nombrePortada;

        $tipoArchivo = $_FILES['portada']['type'];
        if ($tipoArchivo === 'image/jpeg' || $tipoArchivo === 'image/png') {
            if (move_uploaded_file($_FILES['portada']['tmp_name'], $rutaPortada)) {
                $portada = $rutaPortada;
            } else {
                echo "<p class='error'>Error al subir la portada. No se puede mover el archivo.</p>";
            }
        } else {
            echo "<p class='error'>La portada debe ser una imagen JPG o PNG.</p>";
            exit;
        }
    }

    $booklet = null;
    if (isset($_FILES['booklet']) && $_FILES['booklet']['error'] === UPLOAD_ERR_OK) {
        $directorioBooklets = "booklets/";
        if (!file_exists($directorioBooklets)) {
            mkdir($directorioBooklets, 0777, true); 
        }
        $nombreBooklet = basename($_FILES['booklet']['name']);
        $rutaBooklet = $directorioBooklets . uniqid() . "_" . $nombreBooklet;

        if (move_uploaded_file($_FILES['booklet']['tmp_name'], $rutaBooklet)) {
            $booklet = $rutaBooklet;
        } else {
            echo "<p class='error'>Error al subir el archivo del booklet.</p>";
        }
    }

    $sql = "INSERT INTO album (titulo, portada, id_artista, id_banda, id_disquera, anio, duracion, nproducto, formato, cexplicito, booklet)
            VALUES ('$titulo', '$portada', '$id_artista', '$id_banda', '$id_disquera', '$anio', '$duracion', '$nproducto', '$formato', '$cexplicito', '$booklet')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Nuevo álbum agregado exitosamente.</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$sql_artistas = "SELECT id_artista, nombre FROM artista";
$result_artistas = $conn->query($sql_artistas);

$sql_bandas = "SELECT id_banda, nombre FROM banda";
$result_bandas = $conn->query($sql_bandas);

$sql_disqueras = "SELECT id_disquera, nombredisquera FROM disquera";
$result_disqueras = $conn->query($sql_disqueras);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Álbum</title>
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/añadir.css">
</head>
<body>
    <div class="form-container">
        <p class="title">Añadir un Nuevo Álbum</p>
        <form action="añadir.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <p class="subtitle" for="titulo">Título del Álbum</p>
                <input type="text" id="titulo" name="titulo" placeholder="Título del álbum" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="id_disquera">Seleccionar Disquera</p>
                <select id="id_disquera" name="id_disquera" required>
                    <option value="">Seleccionar Disquera</option>
                    <?php while ($row = $result_disqueras->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_disquera']; ?>"><?php echo $row['nombredisquera']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <p class="subtitle" for="id_artista">Seleccionar Artista</p>
                <select id="id_artista" name="id_artista">
                    <option value="">Seleccionar Artista</option>
                    <?php while ($row = $result_artistas->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_artista']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <p class="subtitle" for="id_banda">Seleccionar Banda</p>
                <select id="id_banda" name="id_banda">
                    <option value="">Seleccionar Banda</option>
                    <?php while ($row = $result_bandas->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_banda']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <p class="subtitle" for="anio">Año de Lanzamiento</p>
                <input type="number" id="anio" maxlength="4" name="anio" min="1900" max="<?php echo date('Y'); ?>" placeholder="Año de lanzamiento" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="duracion">Duración Total (en segundos)</p>
                <input type="number" id="duracion" name="duracion" placeholder="Duración total en segundos" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="formato">Formato</p>
                <input type="text" id="formato" name="formato" placeholder="Formato" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="nproducto">Código de Producto</p>
                <input type="text" maxlength="13" id="nproducto" name="nproducto" placeholder="Código de producto" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="portada">Portada (Archivo de Imagen JPG/PNG)</p>
                <input type="file" id="portada" name="portada" accept="image/jpeg, image/png" required>
            </div>

            <div class="form-group">
                <p class="subtitle" for="booklet">Booklet (Archivo PDF)</p>
                <input type="file" id="booklet" name="booklet" accept="application/pdf">
            </div>

            <div class="form-group">
                <p class="subtitle" for="cexplicito">Contenido Explícito</p>
                <select id="cexplicito" name="cexplicito" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Añadir Álbum</button>
        </form>
    </div>
</body>
</html>
