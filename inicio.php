<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); 
    exit();
}
?>

<html>

<head> 
    <link rel="stylesheet" href="inicio.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>

    <body>
        <div class="toolbar">
                <p class="title" >Proyecto Integrador Base de Datos II</p>
                <img width="50px" src="http://localhost/albums/icons/ceti.png">
        </div>

        <p class="title" >Bienvenido, <?php echo htmlspecialchars($_SESSION['nomusuario']); ?>!</p>
    <p class="title" >Tipo de usuario: <?php echo htmlspecialchars($_SESSION['tipousuario']); ?></p>

        <div class="listview">
            <div class="idk2" >    
                <p class="subtitle">Albums</p>
            </div>
            <div class="idk2" >
            <a class="link" href="http://localhost/albums/cruds/album/ver.php">
                <p class="subtitle" >Ver</p>
            </a>
            </div>
        </div> 
        <div class="listview">
            <div class="idk2" >    
                <p class="subtitle">Artistas</p>
            </div>
            <div class="idk2" >
                <a class="link" href="http://localhost/albums/cruds/artista/ver.php">
                    <p class="subtitle" >Ver</p>
                </a>
            </div>
        </div> 
        <div class="listview">
            <div class="idk2" >    
                <p class="subtitle">Pistas</p>
            </div>
            <div class="idk2" >
                <a class="link" href="http://localhost/albums/cruds/pista/ver.php">
                    <p class="subtitle" >Ver</p>
                </a>
            </div>
        </div> 

        <a href="logout.php">
            <p class="title" >Cerrar Sesion</p>
    </a>
    </body>
</html>
