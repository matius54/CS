<?php
    $title = "Formulario";
    include "components/header.php";
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
                <input type="text" class="form-control" name="state" id="estado" placeholder="" />
                <label for="estado">Estado</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="city" id="ciudad" placeholder="" />
                <label for="ciudad">Ciudad</label>
            </div>
        </fieldset>
    </div>
    <button type="submit" class="btn w-25 m-auto">
        Guardar Datos
    </button>
</form>

<?php
    include "components/footer.php";
?>