<?php

$title = "Formulario";
require_once "php/ve.php";
require_once "php/utils.php";
include "components/header.php";
if ($userId) :
?>

    <form action="php/profesor.php?action=setdata" method="post" class="container d-grid text-align-center">
        <div class="row">
            <fieldset class="col">
                <legend>Datos Personales</legend>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="firstname" id="nombre" placeholder="" />
                    <label for="nombre">Nombres</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="lastname" id="apellido" placeholder="" />
                    <label for="apellido">Apellidos</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="ci" id="cedula" placeholder="" />
                    <label for="cedula">Cedula</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="rif" id="rif" placeholder="" />
                    <label for="rif">Rif</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" name="birthdate" id="fecha" placeholder="1994" />
                    <label for="fecha">Fecha de Nacimiento</label>
                </div>
            </fieldset>
            <fieldset class="col">
                <legend>Contacto</legend>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="phone" id="telefono" placeholder="" />
                    <label for="telefono">Telefono</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="email" id="correo" placeholder="" />
                    <label for="correo">Correo</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="address" id="direccion" placeholder="" />
                    <label for="direccion">Direccion</label>
                </div>
                <div class="form-floating mb-3">
                    <?php
                    $ve = new Ve;
                    $states = $ve->states();
                    echo HTML::array2list($states, ["name" => "state", "id" => "estado", "class" => "form-control"], "selecciona el estado", sameValueName: true);
                    ?>
                    <label for="estado">Estado</label>
                </div>
                <div class="form-floating mb-3">
                    <?= HTML::array2list([], ["name" => "city", "id" => "ciudad", "class" => "form-control", "disabled" => "disabled"], sameValueName: true) ?>
                    <label for="ciudad">Ciudad</label>
                </div>
            </fieldset>
        </div>
        <input type="submit" value="Guardar Datos" class="button btn w-25 m-auto">
    </form>
    <script src="js/ajax.js"></script>
    <script src="js/ve.js"></script>
    <script src="js/list.js"></script>
    <script>
        const ve = new Ve(document.querySelector("select[name=\"state\"]"), document.querySelector("select[name=\"city\"]"));
    </script>
<?php else : ?>
    <i>Inicia sesion primero...</i>
<?php
endif;
include "components/footer.php";
?>