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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artistas</title>
    <link rel="stylesheet" href="http://localhost/albums/cruds/artista/ver.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <script>
        function toggleSearch() {
            const searchBar = document.getElementById("search-bar");
            searchBar.style.display = searchBar.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="toolbar">
        <p class="title">Artistas</p>

        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>
            <a href="http://localhost/albums/cruds/artista/añadir.php">
                <img width="35px" src="http://localhost/albums/icons/plus.png" alt="Añadir">
            </a>
        <?php endif; ?>

        <button onclick="toggleSearch()">Buscar</button>
        <div id="search-bar" style="display:none;">
            <form method="GET">
                <input type="text" name="search" placeholder="Buscar artistas...">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </div>

    <div class="artist-list">
        <?php
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

        $sql = "SELECT id_artista, nombre FROM artista";
        if (!empty($search)) {
            $sql .= " WHERE nombre LIKE '%$search%'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="idk">
                        <p class="title">' . htmlspecialchars($row['nombre']) . '</p>
                        <a class="view-link" href="http://localhost/albums/cruds/artista/artista.php?id=' . $row['id_artista'] . '">
                        <p class="title">Ver</p>   
                        </a>
                      </div>';
            }
        } else {
            echo "<p>No se encontraron artistas.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
