<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Curso</title>
    <link rel="stylesheet" href="../principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Estilos para adaptar el formulario */
        .form-container-crear { padding: 2rem; background-color: #f8f9fa; }
        .course-form-box { background-color: white; padding: 0; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); max-width: 750px; margin: auto; overflow: hidden; }
        .form-header { background-color: #50D2C2; color: white; padding: 1.5rem; text-align: center; font-size: 1.5rem; font-weight: 700; text-transform: uppercase; }
        .form-content { padding: 2.5rem; }
        .form-row { display: flex; gap: 1.5rem; }
        .form-group { flex: 1; display: flex; flex-direction: column; margin-bottom: 1.5rem; }
        .form-group label { font-weight: 700; margin-bottom: 0.5rem; font-size: 0.9rem; color: #4b5563; text-transform: uppercase; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 0.8rem 1rem; font-size: 1rem; border: none; border-radius: 8px; background-color: #f0f8ff; font-family: 'Roboto', sans-serif; }
        .form-actions { text-align: center; margin-top: 1rem; }
        .btn-agregar { padding: 1rem 3rem; font-size: 1.1rem; font-weight: 700; color: #fff; background-color: #3A77E8; border: none; border-radius: 8px; cursor: pointer; text-transform: uppercase; }
        #mensaje { margin-bottom: 1.5rem; }

        /* Estilos para el popup (modal) */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.6);
            justify-content: center; align-items: center; z-index: 1000;
        }
        .modal-box { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 90%; max-width: 400px; }
        .modal-box h3 { margin-top: 0; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;}
        .modal-btn { padding: 0.6rem 1.2rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; }
        .btn-guardar { background-color: #2563eb; color: white; }
        .btn-cancelar { background-color: #e5e7eb; color: #333; }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo"><a href="dashboard.php" style="text-decoration:none; color: #1e3a8a;">GestionAgil</a></div>
        <div class="search-bar">
             <form id="searchFormGlobal"><input type="text" id="searchInputGlobal" placeholder="Buscar en todo el catálogo..."></form>
        </div>
        <nav class="nav-links"></nav>
        <div class="user-profile">
            <span id="user-name"></span>
            <div id="user-initials" class="user-initials"></div>
        </div>
    </header>

    <main class="form-container-crear">
        <div class="course-form-box">
            <div class="form-header">*AÑADIR NUEVO CURSO*</div>
            <form class="form-content" id="crearCursoForm"> 
                <div id="mensaje"></div>
                <div class="form-group">
                    <label for="titulo">NOMBRE DEL CURSO*</label>
                    <input type="text" id="titulo" placeholder="Ejem. Inglés Avanzado" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">DESCRIPCIÓN*</label>
                    <textarea id="descripcion" rows="4" placeholder="Ejem. Curso enfocado en desarrollar habilidades de conversación." required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">CATEGORÍA*</label>
                        <select id="categoria" required></select>
                    </div>
                    <div class="form-group">
                        <label for="modalidad">MODALIDAD*</label>
                        <select id="modalidad" required>
                            <option value="en_linea">En línea</option>
                            <option value="presencial">Presencial</option>
                            <option value="hibrido">Híbrido</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">FECHA DE INICIO*</label>
                        <input type="date" id="fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">FECHA DE FIN*</label>
                        <input type="date" id="fecha_fin" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="cupo">CUPOS DISPONIBLES*</label>
                        <input type="number" id="cupo" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">PRECIO (MXN)*</label>
                        <input type="number" id="precio" step="0.01" min="0" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="estado">ESTADO INICIAL*</label>
                    <select id="estado" required>
                        <option value="publicado">Publicado (Visible para alumnos)</option>
                        <option value="borrador">Borrador (Solo visible para ti)</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-agregar">AGREGAR</button>
                </div>
            </form>
        </div>
    </main>
    
    <div id="modal-categoria" class="modal-overlay">
        <div class="modal-box">
            <h3>Añadir Nueva Categoría</h3>
            <div class="form-group">
                <label for="nueva-categoria-nombre">Nombre de la categoría</label>
                <input type="text" id="nueva-categoria-nombre" placeholder="Ejem. Ciencia de Datos">
            </div>
            <div class="modal-actions">
                <button type="button" id="btn-cancelar-categoria" class="modal-btn btn-cancelar">Cancelar</button>
                <button type="button" id="btn-guardar-categoria" class="modal-btn btn-guardar">Guardar</button>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // --- LÓGICA DE LA CABECERA (la de siempre) ---
        const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
        if (!datosUsuario || (datosUsuario.rol !== 'instructor' && datosUsuario.rol !== 'admin')) {
            alert("Acceso denegado. Solo los instructores pueden crear cursos.");
            window.location.href = 'dashboard.php';
            return;
        }
        $("#user-name").text(datosUsuario.nombre);
        const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
        $("#user-initials").text(iniciales);
        const paginaActual = window.location.pathname.split('/').pop();
        let estiloInscripciones = (paginaActual === 'mis_inscripciones.php') ? 'style="color: #2563eb;"' : '';
        let estiloCursos = (paginaActual === 'mis_cursos.php') ? 'style="color: #2563eb;"' : '';
        $(".nav-links").append(`<a href="mis_inscripciones.php" ${estiloInscripciones}>MIS INSCRIPCIONES</a>`);
        if (datosUsuario.rol === 'instructor' || datosUsuario.rol === 'admin') {
            $(".nav-links").append(`<a href="mis_cursos.php" ${estiloCursos}>MIS CURSOS</a>`);
            $(".nav-links").append('<a href="crear_curso.php" style="color: #2563eb;">CREAR CURSO</a>');
        }
        $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
        $("#searchFormGlobal").on("submit", function(event) { event.preventDefault(); const t = $("#searchInputGlobal").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; }});

        // --- LÓGICA DE LA PÁGINA "CREAR CURSO" ---
        const selectCategoria = $("#categoria");
        let valorPrevioCategoria;

        selectCategoria.on('mousedown', function() { valorPrevioCategoria = $(this).val(); });

        function cargarCategorias() {
            return $.ajax({
                url: '../api/obtenerCategorias.php',
                method: 'GET',
                success: function(categorias) {
                    selectCategoria.empty();
                    selectCategoria.append('<option value="">-- Selecciona una categoría --</option>');
                    categorias.forEach(cat => {
                        selectCategoria.append(`<option value="${cat.id_categoria}">${cat.nombre}</option>`);
                    });
                    selectCategoria.append('<option value="nueva" style="font-weight: bold; color: #2563eb;">+ Agregar Nueva Categoría</option>');
                }
            });
        }
        cargarCategorias();

        selectCategoria.on('change', function() {
            if ($(this).val() === 'nueva') {
                $('#modal-categoria').css('display', 'flex');
                $(this).val(valorPrevioCategoria);
            }
        });
        $('#btn-cancelar-categoria').on('click', function() { $('#modal-categoria').hide(); });
        $('#btn-guardar-categoria').on('click', function() {
            const nombreNuevaCategoria = $('#nueva-categoria-nombre').val().trim();
            if (nombreNuevaCategoria === '') { alert("Por favor, escribe un nombre para la categoría."); return; }
            $.ajax({
                url: '../api/crearCategoria.php', method: 'POST', contentType: 'application/json',
                data: JSON.stringify({ nombre: nombreNuevaCategoria }),
                success: function(response) {
                    alert(response.mensaje);
                    $('#modal-categoria').hide();
                    $('#nueva-categoria-nombre').val('');
                    cargarCategorias().done(function() { selectCategoria.val(response.nueva_categoria.id); });
                },
                error: function() { alert("Error al guardar la categoría."); }
            });
        });
        
        $("#crearCursoForm").on("submit", function(event) {
            event.preventDefault();
            $("#mensaje").empty().removeClass("error exito");
            let datosCurso = {
                titulo: $("#titulo").val(), descripcion: $("#descripcion").val(), fecha_inicio: $("#fecha_inicio").val(),
                fecha_fin: $("#fecha_fin").val(), cupo_disponible: $("#cupo").val(), precio: $("#precio").val(),
                estado: $("#estado").val(), modalidad: $("#modalidad").val(), id_categoria: $("#categoria").val(),
                id_instructor: datosUsuario.id_usuario 
            };
            if (!datosCurso.titulo || !datosCurso.id_categoria) { $("#mensaje").text("Por favor, completa al menos el título y la categoría.").addClass("error"); return; }
            const hoy = new Date().toISOString().split('T')[0];
            if (datosCurso.fecha_inicio < hoy) { $("#mensaje").text("La fecha de inicio no puede ser anterior a la fecha actual.").addClass("error"); return; }
            if (datosCurso.fecha_fin < datosCurso.fecha_inicio) { $("#mensaje").text("La fecha de fin no puede ser anterior a la fecha de inicio.").addClass("error"); return; }
            $.ajax({
                url: '../api/crearCurso.php', method: 'POST', contentType: 'application/json',
                data: JSON.stringify(datosCurso),
                success: function(response) {
                    $("#mensaje").text(response.mensaje + " Serás redirigido a 'Mis Cursos'.").addClass("exito");
                    setTimeout(function() { window.location.href = 'mis_cursos.php'; }, 2000);
                },
                error: function(jqXHR) {
                    let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Error desconocido.";
                    $("#mensaje").text(errorMsg).addClass("error");
                }
            });
        });
    });
    </script>
</body>
</html>