<?php
include "components/header.php";
include "index.php";
?>

<form action="" method="post" class="container d-grid text-align-center">
    <div class="row">
        <fieldset class="col">
            <legend>Datos Personales</legend>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="" />
                <label for="nombre">Nombres</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="" />
                <label for="apellido">Apellidos</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="" />
                <label for="cedula">Cedula</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="rif" id="rif" placeholder="" />
                <label for="rif">Rif</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" name="nacimiento" id="fecha" placeholder="1994" />
                <label for="fecha">Fecha de Nacimiento</label>
            </div>
        </fieldset>
        <fieldset class="col">
            <legend>Contacto</legend>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre" id="telefono" placeholder="" />
                <label for="telefono">Telefono</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="apellido" id="correo" placeholder="" />
                <label for="correo">Correo</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nombre" id="direccion" placeholder="" />
                <label for="direccion">Direccion</label>
            </div>
        </fieldset>
    </div>
    <button type="submit" class="btn w-25 m-auto">
        Guardar Datos
    </button>
</form>