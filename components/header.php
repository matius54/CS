<?php
if (!isset($_SESSION)) session_start();
$title = $title ?? "titulo";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="components/bootstrap/bootstrap.min.css">
    <script src="components/bootstrap/bootstrap.min.js"></script>
    <link href="components/cs.css" rel="stylesheet">
</head>

<body>
    <nav>
        <h1><?= $title ?></h1>
        <a href="./" class="button">Página principal</a>
        <?php if ($userId = $_SESSION["user"] ?? null) : ?>
            <a href="php/profesor.php?action=logout" class="button">Cerrar sesion</a>
        <?php else : ?>
            <a href="login.php" class="button">Iniciar sesion</a>
        <?php endif; ?>
        <a href="formulario.php" class="button">Formulario de informacion</a>
        <a href="register.php" class="button">Registrar profesor</a>
        <div class="img">
            <img src="components/images.webp" alt="Logo">
        </div>
    </nav>
    <main>