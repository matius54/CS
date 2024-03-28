<?php

include "components/header.php";
?>

<form action="" method="post" style="width: 100%; margin-top: 1rem;">
    <fieldset class="container m-auto p-5 d-grid gap-3 shadow">
        <legend class="h2 mb-5">Inicio de Sesion</legend>
        <div class="row">
            <div class="col d-flex flex-column gap-5 align-items-center p-5">
                <div class="form-floating w-75 mr-5">
                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="" />
                    <label for="usuario">Usuario</label>
                </div>
                <div class="form-floating mb-3 w-75">
                    <input type="password" class="form-control" name="clave" id="clave" placeholder="" />
                    <label for="clave">Contraseña</label>
                </div>
            </div>
            <div class="col d-flex flex-column align-items-center justify-content-center">
                <img src="components/images.webp" alt="logo universidad">
            </div>
        </div>
        <button type="submit" class="btn m-auto w-25">Iniciar Sesión</button>
    </fieldset>
</form>