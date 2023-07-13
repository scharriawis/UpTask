<div class="contenedor olvido">
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

        <form action="/olvido" method="POST" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    placeholder="Tu Email"
                />
            </div>
            <input type="submit" value="Recuperar Password" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? inicia sesión</a>
            <a href="/crear">¿No tienes una cuenta? crea una</a>
        </div>
    </div>
</div>