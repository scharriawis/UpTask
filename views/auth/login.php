<div class="contenedor login">
    <?php
        include_once __DIR__ . '/../templates/nombre-pagina.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Iniciar Sesión
        </p>

        <?php
            include_once __DIR__ . '/../templates/alertas.php';
        ?>

        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email"
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
            <input type="submit" value="Iniciar Sesión" class="boton">
        </form>

        <div class="acciones">
            <a href="/crear">¿No tienes una cuenta? crea una</a>
            <a href="/olvido">¿Olvidaste el password?</a>
        </div>
    </div>
</div>
