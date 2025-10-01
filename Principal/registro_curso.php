<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Curso</title>
    <link rel="stylesheet" href="../principal/estilos.css"> <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* --- ESTILOS ESPECÍFICOS PARA ESTE FORMULARIO --- */
        
        .form-container {
            background: #f0f4f8; /* Fondo gris claro para toda la página */
            padding: 2rem 1rem;
        }

        .form-box {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            overflow: hidden; /* Para que el header redondeado funcione */
        }
        
        .form-header {
            background-color: #50D2C2; /* Color verde menta del título */
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .form-content {
            padding: 2.5rem;
        }
        
        .form_grupo {
            margin-bottom: 1.5rem;
        }

        .form_grupo label {
            display: block;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #4b5563;
            text-transform: uppercase;
        }

        .form_grupo input,
        .form_grupo textarea,
        .form_grupo select {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border: none; /* Sin borde */
            border-radius: 8px;
            background-color: #f0f8ff; /* Fondo azul claro (AliceBlue) */
            font-family: 'Roboto', sans-serif;
        }
        .form_grupo input:focus,
        .form_grupo textarea:focus,
        .form_grupo select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(58, 119, 232, 0.3); /* Resplandor azul al hacer foco */
        }

        .form-row {
            display: flex;
            gap: 1.5rem;
        }
        .form-row .form_grupo {
            flex: 1;
        }

        .form-actions {
            text-align: center;
            margin-top: 2rem;
        }

        .btn-agregar {
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            background-color: #3A77E8; /* Azul del botón */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .btn-agregar:hover {
            background-color: #2a57a9;
            box-shadow: 0 4px 10px rgba(58, 119, 232, 0.4);
        }
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

    <main class="form-container">
        <div class="form-box">
            <div class="form-header">
                *Añadir Nuevo Curso*
            </div>
            
            <form class="form-content" id="crearCursoForm">
                <div id="mensaje"></div>
                <div class="form_grupo">
                    <label for="titulo">Nombre del Curso*</label>
                    <input type="text" id="titulo" placeholder="Ejem. Inglés Avanzado" required>
                </div>

                <div class="form_grupo">
                    <label for="descripcion">Descripción*</label>
                    <textarea id="descripcion" rows="4" placeholder="Ejem. Curso enfocado en desarrollar habilidades de conversación." required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form_grupo">
                        <label for="categoria">Categoría*</label>
                        <select id="categoria" required></select>
                    </div>
                    <div class="form_grupo">
                        <label for="modalidad">Modalidad*</label>
                        <select id="modalidad" required>
                            <option value="en_linea">En línea</option>
                            <option value="presencial">Presencial</option>
                            <option value="hibrido">Híbrido</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form_grupo">
                        <label for="fecha_inicio">Fecha de Inicio*</label>
                        <input type="date" id="fecha_inicio" required>
                    </div>
                    <div class="form_grupo">
                        <label for="fecha_fin">Fecha de Fin*</label>
                        <input type="date" id="fecha_fin" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form_grupo">
                        <label for="cupo">Cupos Disponibles*</label>
                        <input type="number" id="cupo" min="1" required>
                    </div>
                    <div class="form_grupo">
                        <label for="precio">Precio (MXN)*</label>
                        <input type="number" id="precio" step="0.01" min="0" required>
                    </div>
                </div>

                 <div class="form_grupo">
                    <label for="estado">Estado Inicial*</label>
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
    
   <script>
$(document).ready(function() {
    // 1. AUTORIZACIÓN Y LÓGICA DE CABECERA
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
        $(".nav-links").append('<a href="crear-curso.php" style="color: #2563eb;">CREAR CURSO</a>');
    }
    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
    $("#searchFormGlobal").on("submit", function(event) { event.preventDefault(); const t = $("#searchInputGlobal").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; }});


    // 2. CARGAR CATEGORÍAS EN EL SELECT (LA PARTE QUE FALTABA)
    $.ajax({
        url: '../api/obtenerCategorias.php',
        method: 'GET',
        success: function(categorias) {
            const selectCategoria = $("#categoria");
            selectCategoria.append('<option value="">-- Selecciona una categoría --</option>');
            categorias.forEach(cat => {
                selectCategoria.append(`<option value="${cat.id_categoria}">${cat.nombre}</option>`);
            });
        }
    });

    // 3. LÓGICA PARA ENVIAR EL FORMULARIO
    $("#crearCursoForm").on("submit", function(event) {
        event.preventDefault();
        $("#mensaje").empty().removeClass("error exito");

        let datosCurso = {
            titulo: $("#titulo").val(),
            descripcion: $("#descripcion").val(),
            fecha_inicio: $("#fecha_inicio").val(),
            fecha_fin: $("#fecha_fin").val(),
            cupo_disponible: $("#cupo").val(),
            precio: $("#precio").val(),
            estado: $("#estado").val(),
            modalidad: $("#modalidad").val(),
            id_categoria: $("#categoria").val(),
            id_instructor: datosUsuario.id_usuario 
        };

        if (!datosCurso.titulo || !datosCurso.id_categoria || !datosCurso.fecha_inicio || !datosCurso.fecha_fin) {
             $("#mensaje").text("Por favor, completa todos los campos obligatorios.").addClass("error");
             return;
        }

        const hoy = new Date().toISOString().split('T')[0];
        if (datosCurso.fecha_inicio < hoy) {
            $("#mensaje").text("La fecha de inicio no puede ser anterior a la fecha actual.").addClass("error");
            return;
        }
        if (datosCurso.fecha_fin < datosCurso.fecha_inicio) {
            $("#mensaje").text("La fecha de fin no puede ser anterior a la fecha de inicio.").addClass("error");
            return;
        }

        $.ajax({
            url: '../api/crearCurso.php',
            method: 'POST',
            contentType: 'application/json',
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