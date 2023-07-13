<div class="contenedor reestablecer">
    <?php
        include_once __DIR__ . '/../templates/nombre-pagina.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
            Reestablece tu password
        </p>

        <?php
            include_once __DIR__ . '/../templates/alertas.php';
        ?>

        <?php if($mostrar) : ?>

            <form method="POST" class="formulario">
                <div class="campo">
                    <label for="password">Nuevo Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        placeholder="Reestablece tu password"
                    />
                </div>
                <input type="submit" value="Actualizar Password" class="boton">
            </form>

        <?php endif; ?>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? inicia sesión</a>
            <a href="/olvido">¿Olvidaste el password?</a>        
        </div>
    </div>
</div>