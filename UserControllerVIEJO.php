<?php


//
//login user to application -->
//recuperar lo que el usuario envio POST -->
// conectar MySQL -->
// select users -->
// evaluar el resultado -->
// redirigir a donde toca userprofile -->
// }
session_start();

// check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p>Got past POST check</p>";
    // check button
    if (isset($_POST["login"])) {
        $user = new UserController();
        echo "<p>Got past MySQL connection</p>";
        echo "<p>Login button is clicked.</p>";
        $user->login();
    }

    if (isset($_POST["logout"])) {
        $user = new UserController();
        echo "<p>Logout button is clicked.</p>";
        $user->logout();
    }

    if (isset($_POST["register"])) {
        $user = new UserController();
        echo "<p>Register button is clicked.</p>";
        $user->register();
    }
}

class UserController
{

    /** STEP BY STEP LOGIN 
     * LOGIN USER TO APPLICATION 
     * RECUPERAR LO QUE EL USUARIO ENVIO $POST
     * CONECTAR MYSQL
     * EVALUAR EL RESULTADO
     * REDIRIGIR A USERPROFILE
     */

    private $conn;
    public function __construct()
    {
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "CFC";

        $this->conn = new mysqli($servername, $username, $password, $database);

        $dbCheck = $this->conn->query("SELECT DATABASE()");
        $dbRow = $dbCheck->fetch_row();
        echo ("Connected to DB: " . $dbRow[0]);

        if ($this->conn->connect_error) {
            die("Conexión failed: " . $this->conn->connect_error);
        }
    }

    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            header("Location: ../View/login.php");
            exit;
        }

        // Validar campos
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION["error"] = "Por favor complete todos los campos";
            header("Location: ../View/login.php");
            exit;
        }

        $email = $_POST['email'];
        $inputPassword = $_POST['password'];

        // Obtener usuario de la base de datos
        $stmt = $this->conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {
            $_SESSION["error"] = "Error en la consulta";
            header("Location: ../View/login.php");
            exit;
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION["error"] = "Usuario no encontrado";
            header("Location: ../View/login.php");
            exit;
        }

        $user = $result->fetch_assoc();

        // Verificar contraseña
        if (!password_verify($inputPassword, $user['password'])) {
            $_SESSION["error"] = "Contraseña incorrecta";
            header("Location: ../View/login.php");
            exit;
        }

        // Login exitoso
        $_SESSION['logged'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        $this->conn->close();
        header("Location: ../index.php");
        exit;
    }


    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../View/login.php");
        exit;
    }
    public function register(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = ($_POST['usuario']);
            $email = ($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $date = date("Y-m-d");

            if (empty($username) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo ("Invalid input.");
                exit("Invalid input.");
            } else {
                $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, creation_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $password, $date);

                $checkstmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
                $checkstmt->bind_param("s", $email);
                $checkstmt->execute();
                $checkstmt->store_result();
                if ($checkstmt->num_rows > 0) {
                    $_SESSION["error"] = "La dirección de correo introducida ya existe.";
                    header("Location: ../View/sign_in.php");
                    exit;
                }
                $checkstmt->close();

                if (!$stmt->execute()) {
                    echo ("DB insert failed: " . $stmt->error);
                    $_SESSION["error"] = "Error al crear la cuenta, contacta un administrador.";
                    header("Location: ../View/sign_in.php");
                    exit;
                }


                echo ("Insert ID: " . $this->conn->insert_id);
                $result = $this->conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 1");
                $row = $result->fetch_assoc();
                echo ("Last inserted user: " . json_encode($row));

                $stmt->close();

                echo ("Inserted values successfully.");
                echo ("Inserted values successfully.");
                header("Location: ../View/login.php");

                exit;
            }
        }
    }
}
