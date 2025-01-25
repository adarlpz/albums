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
    <link rel="stylesheet" href="http://localhost/albums/cruds/album/ver.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&display=swap" rel="stylesheet">
    <script>
        function toggleSearch() {
            const searchBar = document.getElementById("search-bar");
            searchBar.style.display = searchBar.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="toolbar">
        <p class="title">Albums</p>

        <?php if ($_SESSION['tipousuario'] == 'admin') : ?>
            <a href="http://localhost/albums/cruds/album/a%c3%b1adir.php">
                <img width="35px" src="http://localhost/albums/icons/plus.png">
            </a>
        <?php endif; ?>

        <button onclick="toggleSearch()">Buscar</button>
        <div id="search-bar" style="display:none;">
            <form method="GET">
                <input type="text" name="search" placeholder="Buscar albums...">
                <button type="submit">Buscar</button>
            </form>
        </div>
        
    </div>

    <div class="inicio">
        <?php
        // Capturar término de búsqueda
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

        // Consulta combinada: buscar títulos y portadas
        $sql = "SELECT id_album, titulo, portada FROM album";
        if (!empty($search)) {
            $sql .= " WHERE titulo LIKE '%$search%'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<a href="http://localhost/albums/cruds/album/album.php?id=' . $row['id_album'] . '">
                        <img width="350px" src="' . $row['portada'] . '">
                      </a>';
            }
        } else {
            echo "<p>No se encontraron álbumes.</p>";
        }
        ?>
    </div>

    <?php
    $conn->close();
    ?>
</body>
</html>
