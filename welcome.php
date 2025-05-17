<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-success text-white d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="text-center">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']); ?> 🎉</h1>
        <a href="logout.php" class="btn btn-light mt-3">Cerrar sesión</a>
    </div>
</body>
</html>
