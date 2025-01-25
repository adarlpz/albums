<?php
$conn = new mysqli("localhost", "root", "", "albums");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<html>

<head>
    <link rel="stylesheet" href="http://localhost/albums/cruds/pista/ver.css">
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/album.php">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <script>
        function toggleSearch() {
            const searchBar = document.getElementById("search-bar");
            searchBar.style.display = searchBar.style.display === "none" ? "block" : "none";
        }
    </script>
</head>

<body>

    <div class="toolbar">
        <p class="title">Pistas</p>
        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>
            <a href="http://localhost/albums/cruds/pista/añadir.php">
                <img width="35px" src="http://localhost/albums/icons/plus.png">
            </a>
        <?php endif; ?>

        <button onclick="toggleSearch()">Buscar</button>
        <div id="search-bar" style="display:none;">
            <form method="GET">
                <input type="text" name="search" placeholder="Buscar pistas...">
                <button type="submit">Buscar</button>
            </form>
        </div>
        
    </div>

    <?php
    // Recuperar el término de búsqueda si existe
    $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

    // Filtrar las pistas por el término de búsqueda
    $sql = "SELECT id_pista, nombre_pista FROM pista";
    if (!empty($search)) {
        $sql .= " WHERE nombre_pista LIKE '%$search%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $id_pistas = [];
        $nombres = [];

        while ($row = $result->fetch_assoc()) {
            $id_pistas[] = $row['id_pista'];
            $nombres[] = $row['nombre_pista'];
        }
    } else {
        echo "No se encontraron pistas.";
    }
    $conn->close();
    ?>

    <?php
    if (!empty($id_pistas)) {
        foreach ($id_pistas as $index => $id_pista) {
            $nombre = $nombres[$index];
            echo '
            <div class="toolbar">
                <div class="toolbar">
                    <p class="title">' . $nombre . '</p>
                </div>  
                <div class="idk2">
                    <a href="http://localhost/albums/cruds/pista/pista.php?id=' . $id_pista . '">
                        <p class="title">Ver</p>
                    </a>
                </div>
            </div>
            ';
        }
    }
    ?>
</body>
</html>
