<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Curso</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; color: #333; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links { margin-right: 2rem; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
        .main-container { max-width: 1200px; margin: auto; padding: 2rem; }
        .curso-detalle-card { background-color: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); margin-bottom: 2rem; }
        .curso-detalle-card h1 { font-size: 2rem; color: #1e3a8a; margin-top: 0; }
        .curso-info { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
        .info-item strong { display: block; color: #6b7280; font-size: 0.9rem; }
        .info-item span { font-size: 1.1rem; }
        .alumnos-card { background-color: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); overflow: hidden; padding: 0; }
        .alumnos-card h2 { font-size: 1.2rem; text-transform: uppercase; margin: 0; background-color: #50D2C2; color: white; padding: 1rem 2rem; }
        .alumnos-card-content { padding: 1.5rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        thead tr { background-color: #f9fafb; }
        th { font-weight: 700; text-transform: uppercase; font-size: 0.8rem; }
        .opciones-btn { border: none; background: none; cursor: pointer; font-size: 1rem; padding: 5px; margin-right: 5px; }
        .btn-ver { color: #16a34a; }
        .detalle-alumno { display: none; background-color: #f9fafb; }
        .detalle-alumno td { padding: 1rem 1rem 1rem 30px; line-height: 1.6; }
        .acciones-curso { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 1rem; }
        .btn-accion { padding: 0.7rem 1.5rem; font-weight: 700; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-editar { background-color: #6b7280; color: white; }
        .btn-editar:hover { background-color: #4b5563; }
        .btn-eliminar { background-color: #ef4444; color: white; }
        .btn-eliminar:hover { background-color: #b91c1c; }
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

    <main class="main-container">
        <div class="curso-detalle-card">
            <h1 id="curso-titulo">Cargando...</h1>
            <div id="curso-info" class="curso-info"></div>
            <div class="acciones-curso">
                <a href="#" id="btn-editar" class="btn-accion btn-editar">Editar Curso</a>
                <button id="btn-eliminar" class="btn-accion btn-eliminar">Eliminar Curso</button>
            </div>
        </div>
        
        <div class="alumnos-card">
            <h2>Alumnos Inscritos</h2>
            <div class="alumnos-card-content">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Alumno</th>
                            <th>Correo electrónico</th>
                            <th>Fecha de Inscripción</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-alumnos"></tbody>
                </table>
            </div>
        </div>
    </main>

<script>
$(document).ready(function() {
    // --- LÓGICA DE LA CABECERA COMPLETA ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario || (datosUsuario.rol !== 'instructor' && datosUsuario.rol !== 'admin')) {
        alert("Acceso denegado.");
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
    $(".nav-links").append(`<a href="mis_cursos.php" ${estiloCursos}>MIS CURSOS</a>`);
    $(".nav-links").append('<a href="crear-curso.php">CREAR CURSO</a>');

    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
    $("#searchFormGlobal").on("submit", function(event) { event.preventDefault(); const t = $("#searchInputGlobal").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; }});

    // --- LÓGICA DE LA PÁGINA "GESTIONAR CURSO" COMPLETA ---
    const urlParams = new URLSearchParams(window.location.search);
    const idCurso = urlParams.get('id');

    if (!idCurso) { $(".main-container").html("<h1>Error: No se especificó un curso.</h1>"); return; }

    // 1. Cargar los detalles del curso
    $.ajax({
        url: `../api/obtenerCurso.php?id=${idCurso}`,
        method: 'GET',
        success: function(response) {
            const curso = response.datos;
            $("#curso-titulo").text(curso.titulo);
            const detallesHTML = `
                <div class="info-item"><strong>Categoría</strong> <span>${getCategoryName(curso.id_categoria)}</span></div>
                <div class="info-item"><strong>Modalidad</strong> <span>${curso.modalidad}</span></div>
                <div class="info-item"><strong>Cupos Restantes</strong> <span>${curso.cupo_disponible}</span></div>
                <div class="info-item"><strong>Precio</strong> <span>$${curso.precio} MXN</span></div>
            `;
            $("#curso-info").html(detallesHTML);
        }
    });

    // 2. Cargar la tabla de alumnos inscritos
    $.ajax({
        url: `../api/obtenerInscritos.php?id_curso=${idCurso}`,
        method: 'GET',
        success: function(response) {
            const tabla = $("#tabla-alumnos");
            tabla.empty();
            if (response.datos.length === 0) {
                tabla.append('<tr><td colspan="4">Aún no hay alumnos inscritos en este curso.</td></tr>');
                return;
            }

            response.datos.forEach(alumno => {
                const fechaInscripcion = new Date(alumno.fecha_inscripcion).toLocaleDateString('es-ES');
                const filaAlumno = `
                    <tr class="fila-principal" data-id-alumno="${alumno.id_usuario}">
                        <td>${alumno.nombre}</td>
                        <td>${alumno.email}</td>
                        <td>${fechaInscripcion}</td>
                        <td><button class="opciones-btn btn-ver"><i class="fa-solid fa-eye"></i></button></td>
                    </tr>
                    <tr class="detalle-alumno" id="detalle-${alumno.id_usuario}">
                        <td colspan="4">
                            <strong>Detalles de Contacto:</strong><br>
                            Nombre Completo: ${alumno.nombre}<br>
                            Correo Electrónico: ${alumno.email}
                        </td>
                    </tr>
                `;
                tabla.append(filaAlumno);
            });
        }
    });

    // 3. Lógica para los botones de acción
    $(document).on('click', '.btn-ver', function() {
        const filaDetalle = $(this).closest('tr').next('.detalle-alumno');
        filaDetalle.toggle();
        const icono = $(this).find('i');
        if (filaDetalle.is(':visible')) { icono.removeClass('fa-eye').addClass('fa-eye-slash'); } else { icono.removeClass('fa-eye-slash').addClass('fa-eye'); }
    });

    $(document).on('click', '#btn-eliminar', function() {
        if (confirm("¿Estás seguro de que quieres eliminar este curso? Esta acción es irreversible.")) {
            $.ajax({
                url: '../api/eliminarCurso.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id_curso: idCurso }),
                success: function(response) {
                    alert(response.mensaje);
                    window.location.href = 'mis-cursos.php';
                },
                error: function(jqXHR) {
                    alert(jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Error desconocido.");
                }
            });
        }
    });

    $(document).on('click', '#btn-editar', function(e) {
        e.preventDefault();
        window.location.href = `editar-curso.php?id=${idCurso}`;
    });
    
    function getCategoryName(id) {
        const categorias = { 1: "Desarrollo Backend", 2: "Desarrollo Frontend", 3: "Marketing", 4: "Bases de Datos", 5: "Diseño UI/UX", 6: "Gestión de Software" };
        return categorias[id] || "General";
    }
});
</script>

</body>
</html>