<?php
    @include_once __DIR__ . '/header.php';
?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <a href="/cambiar-password" class="enlace">Cambiar Password</a>

        <form action="/perfil" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    id="nombre"
                    name="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo $usuario->nombre; ?>"
                />
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu Email"
                    value="<?php echo $usuario->email; ?>"
                />
            </div>
<!--
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Reestablece Tu Password"
                />
            </div>
-->

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>

<?php
    @include_once __DIR__ . '/footer.php';
?>