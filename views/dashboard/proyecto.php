
<?php
    @include_once __DIR__ . '/header.php';
?>

    <div class="contenedor-sm">
        <div class="contenedor-agregar-tarea">
            <button type="button" class="agregar-tarea" id="agregar-tarea"> &#43 Agregar Tarea </button>
        </div>

        <?php include_once __DIR__ . '/filtros.php'; ?>

        <ul class="listado-tarea" id="listado-tarea"></ul>
    </div>

<?php
    @include_once __DIR__ . '/footer.php';
?>

<?php
    //Concatenar archivos posteriores js footer
    $script .= '        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/tarea.js"></script>
    ';
?>