<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Curso</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links { margin-right: 2rem; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
        .detalle-container { max-width: 800px; margin: 2rem auto; background-color: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .curso-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .curso-header h1 { font-size: 2.5rem; color: #1e3a8a; margin: 0; }
        .curso-header .icon { font-size: 2.5rem; }
        .curso-descripcion { font-size: 1.1rem; color: #555; line-height: 1.6; margin-bottom: 2rem; }
        .curso-info { display: grid; grid-template-columns: 150px 1fr; gap: 1rem; margin-bottom: 2rem; }
        .curso-info strong { font-weight: 700; color: #333; }
        .curso-info span { color: #555; }
        .btn-inscribir, .btn-iniciar, .btn-gestionar { display: inline-block; padding: 0.8rem 2rem; font-size: 1rem; font-weight: 700; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; transition: background-color 0.3s; }
        .btn-inscribir { background-color: #2563eb; }
        .btn-inscribir:hover { background-color: #1e40af; }
        .btn-iniciar { background-color: #16a34a; }
        .btn-iniciar:hover { background-color: #15803d; }
        .btn-gestionar { background-color: #6b7280; }
        .btn-gestionar:hover { background-color: #4b5563; }
        #mensaje { margin-top: 15px; padding: 10px; border-radius: 5px; font-weight: bold; display: none; }
        #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
        #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo"><a href="dashboard.php" style="text-decoration:none; color: #1e3a8a;">GestionAgil</a></div>
        <div class="search-bar">
            <form id="searchFormGlobal"><input type="text" id="searchInputGlobal" placeholder="&#128269; Buscar curso..."></form>
        </div>
        <nav class="nav-links"></nav>
        <div class="user-profile">
            <span id="user-name"></span>
            <div id="user-initials" class="user-initials"></div>
        </div>
    </header>

    <main class="detalle-container">
        <div class="curso-header">
            <h1 id="curso-titulo">Cargando...</h1>
            <div id="curso-icono" class="icon"></div>
        </div>
        
        <p id="curso-descripcion"></p>
        <div class="curso-info">
            <strong>Categoría</strong> <span id="curso-categoria"></span>
            <strong>Modalidad</strong> <span id="curso-modalidad"></span>
            <strong>Fechas</strong> <span id="curso-fechas"></span>
            <strong>Cupo</strong> <span id="curso-cupo"></span>
            <strong>Instructor</strong> <span id="curso-instructor"></span>
        </div>
        
        <div id="cta-container"></div>
        <div id="mensaje"></div>
    </main>

<script>
$(document).ready(function() {
    // --- LÓGICA DE LA CABECERA ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario) { window.location.href = 'inicio_sesion.php'; return; }

    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    
    const paginaActual = window.location.pathname.split('/').pop();
    let estiloInscripciones = (paginaActual === 'mis_inscripciones.php') ? 'style="color: #2563eb;"' : '';
    let estiloCursos = (paginaActual === 'mis_cursos.php') ? 'style="color: #2563eb;"' : '';
    $(".nav-links").append(`<a href="mis_inscripciones.php" ${estiloInscripciones}>MIS INSCRIPCIONES</a>`);
    if (datosUsuario.rol === 'instructor' || datosUsuario.rol === 'admin') {
        $(".nav-links").append(`<a href="mis_cursos.php" ${estiloCursos}>MIS CURSOS</a>`);
    }

    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
    $("#searchFormGlobal").on("submit", function(event) { event.preventDefault(); const t = $("#searchInputGlobal").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; }});

    // --- LÓGICA DE LA PÁGINA DE DETALLES ---
    const urlParams = new URLSearchParams(window.location.search);
    const idCurso = urlParams.get('id');

    if (!idCurso) { $(".detalle-container").html("<h1>Error: No se especificó un curso.</h1>"); return; }

    function verificarInscripcionYMostrarBoton(curso) {
        $.ajax({
            url: `../api/obtenerMisInscripciones.php?id_usuario=${datosUsuario.id_usuario}`,
            method: 'GET',
            success: function(misCursos) {
                const yaInscrito = misCursos.some(c => c.id_curso == idCurso);
                if (yaInscrito) {
                    $("#cta-container").html('<button id="btn-iniciar" class="btn-iniciar">Iniciar Curso</button>');
                } else if (curso.cupo_disponible > 0) {
                    $("#cta-container").html(`<a href="pago.php?id_curso=${idCurso}" class="btn-inscribir">Inscribirme</a>`);
                } else {
                    $("#cta-container").html('<p><strong>Este curso ya no tiene cupos disponibles.</strong></p>');
                }
            }
        });
    }

    $.ajax({
        url: `../api/obtenerCurso.php?id=${idCurso}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const curso = response.datos;
            
            $("#curso-titulo").text(curso.titulo);
            $("#curso-icono").text(getEmojiForCategory(curso.id_categoria));
            $("#curso-descripcion").text(curso.descripcion);
            $("#curso-categoria").text(getCategoryName(curso.id_categoria));
            $("#curso-modalidad").text(curso.modalidad);
            const fechaInicio = new Date(curso.fecha_inicio).toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });
            const fechaFin = new Date(curso.fecha_fin).toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });
            $("#curso-fechas").text(`${fechaInicio} - ${fechaFin}`);
            $("#curso-cupo").text(`${curso.cupo_disponible} disponibles`);
            $("#curso-instructor").text(curso.nombre_instructor);

            if (datosUsuario.rol === 'alumno') {
                verificarInscripcionYMostrarBoton(curso);
            } else if (datosUsuario.rol === 'instructor') {
                if (curso.id_instructor == datosUsuario.id_usuario) {
                    $("#cta-container").html(`<a href="gestion_alumnos.php?id=${idCurso}" class="btn-gestionar">Gestionar Mi Curso</a>`);
                } else {
                    verificarInscripcionYMostrarBoton(curso);
                }
            } else if (datosUsuario.rol === 'admin') {
                 $("#cta-container").html(`<a href="gestion_alumnos.php?id=${idCurso}" class="btn-gestionar">Gestionar Curso (Admin)</a>`);
            }
        },
        error: function() {
            $(".detalle-container").html("<h1>Error: Curso no encontrado.</h1>");
        }
    });

    $(document).on('click', '#btn-iniciar', function() {
        $("#mensaje").html("<h2>¡Bienvenido al curso!</h2><p>Aquí es donde comenzarían las lecciones, los videos y los materiales de estudio.</p>").addClass("exito");
    });
    
    function getEmojiForCategory(id) {
        const emojis = { 1: '💻', 2: '🎨', 3: '📈', 4: '💾', 5: '📱', 6: '📋' };
        return emojis[id] || '🎓';
    }
    function getCategoryName(id) {
        const categorias = { 1: "Desarrollo Backend", 2: "Desarrollo Frontend", 3: "Marketing", 4: "Bases de Datos", 5: "Diseño UI/UX", 6: "Gestión de Software" };
        return categorias[id] || "General";
    }
});
</script>
</body>
</html>