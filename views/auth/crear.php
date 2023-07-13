<div class="contenedor crear">
    <?php
        include_once __DIR__ . '/../templates/nombre-pagina.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Crea tu cuenta en Uptask
        </p>

        <?php
            include_once __DIR__ . '/../templates/alertas.php';
        ?>

        <form action="/crear" method="POST" class="formulario">
        <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="nombre" 
                    name="nombre" 
                    id="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo $usuario->nombre; ?>"
                />
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email"
                    value="<?php echo $usuario->email ?>"
                />
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    placeholder="Tu Password"
                />
            </div>
            <div class="campo">
                <label for="password2">Repite tu Password</label>
                <input 
                    type="password" 
                    name="password2" 
                    id="password2"
                    placeholder="Repite tu Password"
                />
            </div>
            <input type="submit" value="Crear Cuenta" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? inicia sesión</a>
            <a href="/olvido">¿Olvidaste el password?</a>
        </div>
    </div>
</div>