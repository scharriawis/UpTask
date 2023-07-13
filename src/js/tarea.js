(function() {

    //Mostrar tareas
    obtenerTareas();

    //Variable global contenedor de tareas en el lado front-end, usuario o navegador. virtual DOM 
    let tareas = [];
    let filtradas = [];

    //Mostrar el modal con el formulario
    const nuevaTareabtn = document.querySelector('#agregar-tarea');
    nuevaTareabtn.addEventListener('click', function () {
        mostrarFormulario();
    });

    //filtros
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtarTareas);
    });

    function filtarTareas(e){

        const filtro = e.target.value;

        switch (filtro) {
            case '1':
                filtradas = tareas.filter(tareaMemoria => tareaMemoria.estado === filtro);
                mostrarTareas();
                break;
            case '0':
                filtradas = tareas.filter(tareaMemoria => tareaMemoria.estado === filtro);
                mostrarTareas();
                break;
                default:
                filtradas = [];
                mostrarTareas();
                break;
        }
    }

    async function obtenerTareas(){
        try {

            const getId = obtenerURL();
            
            const url = `${location.origin}/api/tareas?id=${getId}`;
            //Esperar la conexión con la API por default get
            const respuesta = await fetch(url);

            //Esperar la respuesta de la API
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

            
        } catch (error) {
            console.log(error);
        }
    }
    
    function mostrarTareas(){
        //limpiar el html
        limpiarTareas();

        totalPendientes();
        totalCompleto();

        const arrayTarea = filtradas.length ? filtradas : tareas;
        //No hay resultado
        if(arrayTarea.length === 0){
            const contenedorTareas = document.querySelector('#listado-tarea');

            const textoNoTarea = document.createElement('LI');
            textoNoTarea.textContent = 'No hay Tareas';
            textoNoTarea.classList.add('no-tareas');

            contenedorTareas.append(textoNoTarea);
            return;
        }

        //Diccionario
        const estados = {
            0: 'Pendiente',
            1: 'Completado'
        }

        //Iterar y mostrar tarea        
        arrayTarea.forEach(tarea => {

            //Contenedor
            const contenedorTareas = document.createElement('LI');
            contenedorTareas.dataset.tareaId = tarea.id;
            contenedorTareas.classList.add('tarea');

            //Texto
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(true, {... tarea});
            }

            //Contenedor opciones
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            //Botones

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);     //.toLowerCase()  Convierte un string en minúsculas
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            //llamar la function cambiar estado
            //Eso lo que va hacer es sacar ese objeto en memoria, crear una copia y esa copia es la quevamos a midificar.
            btnEstadoTarea.ondblclick = () => {cambiarEstadoTarea({...tarea});}

            const btnEliminarTarea =document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Elimnar';

            //LLamar la function elimnar
            btnEliminarTarea.ondblclick = function () {
                confirmarElimnarTarea({... tarea});
            }

            //Scripting
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTareas.appendChild(nombreTarea);
            contenedorTareas.appendChild(opcionesDiv);

            //Almacenar en el HTML padre a agregar
            const listadoTarea = document.querySelector('#listado-tarea');

            listadoTarea.appendChild(contenedorTareas);
            
        });
    }

    //stack
    function mostrarFormulario( editar = false, tarea = {}){

        const modalMovil = document.querySelector('.modal');

        if (modalMovil) {
            modalMovil.remove();
        }

        const modal =  document.createElement('DIV');
        modal.classList.add('modal');

        

        modal.innerHTML = `
            <form class ="formulario nueva-tarea">
                <legend>${editar ? 'Editar tarea' : 'Añade nueva tarea'}</legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input 
                        type="text"
                        name="tarea"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : ''}"
                        placeholder="${tarea.nombre ? 'Edita la tarea' : 'Añadir tarea al proyecto'}"
                    />
                </div>
                <div class="opciones">
                    <input type="submit" value="${tarea.nombre ? 'Guardar cambios' : 'Añadir tarea'}" class="submit-nueva-tarea">
                    <button class="cerrar-modal" type="button">Cancelar</button>
                </div>
            </form>
        `;

        //Queue
        setTimeout( ()=>{
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        //Delegations javaScript
        modal.addEventListener('click', function (e){
            e.preventDefault();
            
            //Cerrar modal
            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            //Alerta válidar formulario
            if(e.target.classList.contains('submit-nueva-tarea')){

                const nombreTarea = document.querySelector('#tarea').value.trim();    //.trim elimina el espaciado
        
                //Validar formulario
                if(tarea === ''){
                    mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('form legend'));
                    return;
                }
                
                if (editar) {
                    //Actualizar tarea
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                } else {
                    //Crear tarea
                   agregarTarea(nombreTarea); 
                }
            }
            
        });
        
        document.querySelector('.dashboard').appendChild(modal);
    }

    //Mostrar Alerta
    function mostrarAlerta(mensaje, tipo, referencia){

        //Prevenir las multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        //Crear elemento HTML a la alerta
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        //Posicionar la alerta antes del legend
        //referencia.parentElement.insertBefore(alerta, $referencia);

        //Posicionar la alerta después del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //Establecer tiempo de alerta
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    function totalPendientes() {
        //filtra un array crea variable temporal con la asociación a verificar.
        const totalPendientes = tareas.filter(total => total.estado === '0');

        const pendientes = document.querySelector('#pendientes');

        
        if (totalPendientes.length === 0) {
            //Desactivar
            pendientes.disabled = true;
        }else{
            //Habilitar
            pendientes.disabled = false
        }
    }
    function totalCompleto() {

        const totalCompleto = tareas.filter(total => total.estado === '1');
        
        const completas = document.querySelector('#completado');

        if (totalCompleto.length === 0) {
            completas.disabled = true;
        }else{
            completas.disabled = false;
        }
    }
    
    //CRUD

    //Agregar tarea
    async function agregarTarea(tarea){

        //Crear la variable para enviar datos
        const datos = new FormData();
        //Agregar datos a enviar
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerURL());

        try {
            
            const url = `${location.origin}/api/tarea`;
            //Establecemos la conexión con la API por medio de fetch
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });


            //Esperamos la respuesta de la API por medio de .json gracias a fetch
            const resultado = await respuesta.json();
            
            
            //Mostar alerta
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('form legend'));

            //Quitar la ventana modal
            if (resultado.tipo === 'exito') {
               const modal = document.querySelector('.modal');
               setTimeout(() => {
                modal.remove();
               }, 3000);

               //Agregar datos a la global tareas
               const tareaObj = {
                    id: resultado.id,                           //PHP
                    nombre: tarea,                              //javScript
                    estado: '0',
                    proyectoId: String(resultado.proyectoId)    //PHP
               };

               //Conserva una copia en la variable let tareas global y anexa más datos.
               tareas = [...tareas, tareaObj];

               mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    function cambiarEstadoTarea(tarea) {
        
        //Código ternario cambiar estado
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;

        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {

        //Crear variable para enviar datos
        const datos = new FormData();

        const {id, nombre, estado} = tarea;

        //Datos a enviar a la API
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerURL());       //Enviar url para comprobar el usuario es el mismo mayor seguridad.
        
        /* Comprobar lo que enviamos en el FromData();
        for(let valor of datos.values()){
            console.log(valor);
        }*/

        try {
            const url = `${location.origin}/api/tarea/actualizar`;
            //Esperamos a establecer la conexión con la API
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //Esperamos la respuesta de la API por medio de .json gracias a fetch
            const resultado = await respuesta.json();

            //Mostrar alerta
            if(resultado.respuesta.tipo === 'exito'){
                setTimeout(() => {
                    const modal = document.querySelector('.modal');
                    if (modal) {
                        modal.remove();
                    }
                }, 3000); 
                Swal.fire('Tarea Actualizada', resultado.mensaje, 'success');

                //.map crea una variable temporal en el virtual DOM
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    //Retornar la variable temporal.
                    return tareaMemoria;
                });
                    
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }

    function confirmarElimnarTarea(tarea){
        Swal.fire({
            title: '¿Eliminar Tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
              eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea){
        //Crear variable a enviar datos
        const datos = new FormData();

        const {id, nombre, estado} = tarea;

        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerURL());

        try {
            const url = `${location.origin}/api/tarea/eliminar`;

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            //Esperamos la respuesta de la API por medio de .json gracias a fetch
            const resultado = await respuesta.json();

            console.log(resultado);

            if(resultado.tipo === 'error'){
                mostrarAlerta(
                    resultado.mensaje, 
                    resultado.tipo, 
                    document.querySelector('.contenedor-agregar-tarea')
                );
                return;
            }

            if(resultado.tipo === 'exito'){
                /*mostrarAlerta(
                    resultado.mensaje, 
                    resultado.tipo, 
                    document.querySelector('.contenedor-agregar-tarea')
                );*/

                Swal.fire('Eliminado!', resultado.mensaje, 'success');

            }

            //.filtra trae todos las tareas diferentes al id. 
            tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== id);

            mostrarTareas();

        } catch (error) {
            console.log(error);
        }
    }

    function obtenerURL(){
        //Obtenemos la url
        const URLParams = new URLSearchParams(window.location.search);

        //Recorremos el objeto
        const URL = Object.fromEntries(URLParams.entries());

        //Retornamos el indice a mostrar
        return URL.id;
    }

    function limpiarTareas (){
        const contenedorTareas = document.querySelector('#listado-tarea');

        //Mientras haya un elemento hijo lo vamos a eliminar el primer elemento uno a uno.
        while (contenedorTareas.firstChild) {
            contenedorTareas.removeChild(contenedorTareas.firstChild);
        }
    }

}) () ;