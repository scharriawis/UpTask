<?php
    @include_once __DIR__ . '/header.php';
?>

    <?php if(count($proyectos) === 0){ ?>
        <p>No hay proyectos aún</p>
        <a class="no-proyectos" href="/crear-Proyecto">Comienza creando uno</a>
    <?php }else{ ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto) : ?>
                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        <?php echo $proyecto->proyecto; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php }?>

<?php
    @include_once __DIR__ . '/footer.php';
?>