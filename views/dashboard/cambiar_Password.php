<?php
    @include_once __DIR__ . '/header.php';
?>

    <div class="contenedor-sm">
        <a href="/perfil" class="enlace">Volver al Perfil</a>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/cambiar-password" class="formulario" method="POST">

            <div class="campo">
                <label for="passwordActual">Password Actual</label>
                <input 
                    type="password" 
                    name="passwordActual" 
                    id="passwordActual"
                    placeholder="Password Actual"
                />
            </div>

            <div class="campo">
                <label for="passwordNuevo">Password Nuevo</label>
                <input 
                    type="password" 
                    name="passwordNuevo" 
                    id="passwordNuevo"
                    placeholder="Actualiza Tu Password"
                />
            </div>

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>

<?php
    @include_once __DIR__ . '/footer.php';
?>