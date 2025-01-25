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

function convertirDuracion($segundos) {
    $minutos = floor($segundos / 60);
    $segundosRestantes = $segundos % 60;
    return sprintf("%02d:%02d", $minutos, $segundosRestantes); 
}

if (isset($_GET['id'])) {
    $id_album = $_GET['id'];

    $sql_album = "
    SELECT 
        album.*, 
        artista.nombre AS nombre_artista,
        banda.nombre AS nombre_banda,
        disquera.nombredisquera AS nombre_disquera 
    FROM album
    LEFT JOIN artista ON album.id_artista = artista.id_artista
    LEFT JOIN banda ON album.id_banda = banda.id_banda
    LEFT JOIN disquera ON album.id_disquera = disquera.id_disquera
    WHERE album.id_album = $id_album";

    $result_album = $conn->query($sql_album);

    if ($result_album->num_rows > 0) {
        $album = $result_album->fetch_assoc();
    } else {
        echo "Álbum no encontrado.";
        exit;
    }

    $sql_pistas = "SELECT * FROM pista WHERE id_album = $id_album";
    $result_pistas = $conn->query($sql_pistas);
    $pistas = [];
    while ($row_pista = $result_pistas->fetch_assoc()) {
        $pistas[] = $row_pista;
    }

    $sql_resenas = "
    SELECT 
        resena.comentario, 
        usuario.nomusuario, 
        usuario.ape 
    FROM resena
    INNER JOIN usuario ON resena.id_usuario = usuario.id_usuario
    WHERE resena.id_album = $id_album
    ORDER BY resena.id_resena DESC";

    $result_resenas = $conn->query($sql_resenas);
    $resenas = [];
    while ($row_resena = $result_resenas->fetch_assoc()) {
        $resenas[] = $row_resena;
    }

} else {
    echo "ID no proporcionado.";
    exit;
}

$conn->close();
?>
<html>
<head> 
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/album.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="toolbar">
        <div>
            <p class="title">Detalles del Álbum</p>
        </div>

        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>
            <div class="botones">
            <a href="eliminar.php?id=<?php echo $id_album; ?>">
                <img width="35px" src="http://localhost/albums/icons/basura.png">
            </a>
            <a href="editar.php?id=<?php echo $id_album; ?>">
                <img width="35px" src="http://localhost/albums/icons/lapiz.png">
            </a>
        </div>
            <?php else : ?>
            <?php endif; ?>

    </div>

    <div class="head">
        <div class="idk">
            <img class="portadas" width="350px" src="<?php echo $album['portada']; ?>" alt="Portada del álbum">
            <div>
                <p class="title"><?php echo $album['titulo']; ?></p>
                <p class="title">
                    Artista: <?php echo $album['nombre_artista'] ? $album['nombre_artista'] : 'Desconocido'; ?>
                </p>
                <p class="title">
                    Banda: <?php echo $album['nombre_banda'] ? $album['nombre_banda'] : 'Desconocida'; ?>
                </p>
                <p class="title">Año: <?php echo $album['anio']; ?></p>
                <p class="title">Cantidad de pistas: <?php echo $album['cantidad_pistas']; ?></p>
                <p class="title">Duración total: <?php echo convertirDuracion($album['duracion']); ?> min</p>
                <p class="title">Código de producto: <?php echo $album['nproducto']; ?></p>
                <p class="title">Disquera: <?php echo $album['nombre_disquera']; ?></p>

                <?php if (!empty($album['booklet'])) { ?>
                    <a href="<?php echo $album['booklet']; ?>" target="_blank">
                        <button>Ver Booklet</button>
                    </a>
                <?php } else { ?>
                    <p>No hay booklet disponible para este álbum.</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php foreach ($pistas as $pista) { ?>
        <div class="listview">
            <div class="idk2">
                <p class="title"><?php echo $pista['num_pista']; ?></p>
                <p class="title"><?php echo $pista['nombre_pista']; ?></p>
            </div>  
            <div class="idk2">
                <p class="title"><?php echo convertirDuracion($pista['duracion']); ?></p>
                <a href="http://localhost/albums/cruds/pista/pista.php?id=<?php echo $pista['id_pista']; ?>">
                    <p class="title">Ver</p>
                </a>
            </div>
        </div>
    <?php } ?>

    <div class="reseñas">
        <p class="title" >Reseñas</p>
        <?php if (count($resenas) > 0) { ?>
            <?php foreach ($resenas as $resena) { ?>
                <div class="resena">
                    <p class="title"><?php echo htmlspecialchars($resena['nomusuario']) . " " . htmlspecialchars($resena['ape']); ?></p>
                    <p class="title"><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
                </div>
                <hr>
            <?php } ?>
        <?php } else { ?>
            <p>No hay reseñas disponibles para este álbum.</p>
        <?php } ?>
    </div>

</body>
</html>
