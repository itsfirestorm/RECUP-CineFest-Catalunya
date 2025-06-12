<?php include "../Controller/UserController.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineFest Catalunya // Perfil</title>
    <link rel="stylesheet" href="./files/style/profile.css">
    <link rel="stylesheet" href="./files/style/navbar.css">
</head>

<body>
    <header>
        <ul id="navbar">
            <h1 id="logo">CFC</h1>
            <input type="checkbox" id="check">
            <label for="check" class="menubtn">
                <i class="fas fa-bars"></i>
            </label>
            <div id="nav-left">
                <a href="../index.php" id="home">Home</a>
                <a href="./events.php" id="events">Eventos</a>
                <a href="./calendar.php" id="calendar">Calendario</a>
                <a href="#" id="news">Noticias</a>
                <a href="#" id="forums">Foros</a>
            </div>
            <input type="checkbox" id="showprofile">
            <label for="showprofile" id="profilebtn" class="navbar-right">
                <i class="fa-solid fa-user" style="font-size: 24px;"></i>
            </label>
            <div id="search-container">
                <input type="text" placeholder="Search...">
                <button type="submit"><i class="fa fa-search" style="color:white"></i></button>
            </div>
            <div id="user-info">
                <h1 id="profile">Perfil</h1>
                <?php if (isset($_SESSION["email"])) {
                    echo '
                    <h3 id="usr-email">' . $_SESSION['email'] . '</h3>
                    <img src="./files/img/usr_test.png" id="user-pfp">
                    <h1 id="usr-name">Bienvenido, ' . $_SESSION['username'] . '!</h1>
                    <a href="./profile.php"><button class="user-action" id="prof-redirect">Perfil</button></a>
                    <a href="#"><button class="user-action" id="passwd-redirect">Cambiar Contraseña</button></a>
                    <!--placeholders-->
                    <a href="#"><button class="user-action" id="useraction2">Lorem ipsum</button></a>
                    <!--placeholders-->
                    <a href="../Controller/logout.php"><button class="user-action" id="logout">Cerrar sesión</button></a>';
                } else {
                    echo '<h1 id="not-logged">No has iniciado sesión</h1>
                    <a href="./login.php"><button class="user-action" id="login">Login</button></a>';
                } ?>
            </div>
        </ul>
    </header>
    <div id="container">
        <?php if (isset($_SESSION["email"])) {
            echo '<div id="profile-container">
            <div id="sidebar">
                <h2>Configuración</h2>
                <ul>
                    <li><a href="./profile.php">Datos Personales</a></li>
                    <li><a href="#" class="active">Cambiar Contraseña</a></li>
                    <li><a href="#">Notificaciones</a></li>
                    <li><a href="./security.php">Seguridad</a></li>
                    <li><a href="../Controller/logout.php">Cerrar sesión</a></li>
                </ul>
            </div>

            <div id="content">
                <div id="welcome-section">
                    <h1>Bienvenido, ' . $_SESSION["username"] . '</h1>
                </div>

                <div class="profile-section">
                    <h2>Cambiar Contraseña</h2>
                    ';
                    if (isset($_SESSION["error"])) {
                        echo '<div id="error">'.$_SESSION["error"].'</div>';
                        unset($_SESSION["error"]);
                    }
                    if (isset($_SESSION["success"])) {
                        echo '<div id="success">'.$_SESSION["success"].'</div>';
                        unset($_SESSION["success"]);
                    }
                    echo '
                    <form id="profile-form" method="POST" action="../Controller/UserController.php">
                        <div class="form-group">
                            <label for="oldpassword">Contraseña actual:</label>
                            <input type="password" id="oldpassword" class="inputbox" name="oldpassword">
                        </div>

                        <div class="form-group">
                            <label for="newpassword">Contraseña nueva:</label>
                            <input type="password" id="newpassword" class="inputbox" name="newpassword">
                        </div>

                        <div class="form-group">
                            <label for="confirm">Confirma la nueva contraseña:</label>
                            <input type="password" id="confirm" class="inputbox" name="confirm">
                        </div>

                        <button type="submit" id="save-btn" name="update-password">Actualizar contraseña</button>
                    </form>
                </div>
            </div>
        </div>';
        } else {
            echo 'No has iniciado sesión.';
        } ?>
    </div>
</body>
<script src="https://kit.fontawesome.com/e1205d9581.js" crossorigin="anonymous"></script>

</html>