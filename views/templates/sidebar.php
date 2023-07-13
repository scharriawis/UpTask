<div class="sidebar">
    <div class="contenedor-sidebar">
        <h2>Uptask</h2>
        <div class="cerrar-menu">
            <img id="cerrar-menu" src="build/img/cerrar.svg" alt="cerrar menu mobile">
        </div>
    </div>

    <div class="side-nav">
        <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear Proyecto') ? 'activo' : ''; ?>" href="/crear-Proyecto">Crear Proyecto</a>
        <a class="<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </div>

    <div class="cerrar-menu-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
    </div>
</div>