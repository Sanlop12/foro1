<?php
session_start();
require 'db.php';

$error = "";
$success = "";

// REGISTRO
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $birthdate = $_POST['birthdate'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } elseif ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El usuario o correo ya está registrado.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, fullname, phone, address, birthdate, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("sssssss", $username, $email, $fullname, $phone, $address, $birthdate, $hash);
            $insert->execute();
            $success = "Cuenta creada correctamente. Ahora puedes iniciar sesión.";
        }
    }
}

// LOGIN
if (isset($_POST['login'])) {
    $user = trim($_POST['user']);
    $pass = $_POST['pass'];

    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $stmt->bind_result($username, $hashed);

    if ($stmt->fetch() && password_verify($pass, $hashed)) {
        $_SESSION['user'] = $username;
        header("Location: welcome.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Autenticación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h4 class="text-center mb-3">Iniciar sesión</h4>
                    <form method="post">
                        <input type="hidden" name="login">
                        <div class="mb-3">
                            <input type="text" name="user" class="form-control" placeholder="Usuario o correo" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="pass" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <button class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-3">Crear cuenta</h4>
                    <form method="post">
                        <input type="hidden" name="register">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Usuario" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="fullname" class="form-control" placeholder="Nombres completos" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" placeholder="Teléfono">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="address" class="form-control" placeholder="Dirección">
                        </div>
                        <div class="mb-3">
                            <input type="date" name="birthdate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="confirm" class="form-control" placeholder="Repetir contraseña" required>
                        </div>
                        <button class="btn btn-success w-100">Registrarse</button>
                    </form>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success mt-3"><?php echo $success; ?></div>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
