<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Panel de Administrador</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
         body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; color: #4b5563; display: flex; }
    
    /* ✅ ESTILOS DEL PANEL LATERAL ACTUALIZADOS */
    .sidebar {
        width: 250px;
        background-color: #2563eb; /* El color azul que te gustó */
        color: white;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        flex-shrink: 0;
    }
    .sidebar-header {
        padding: 1.5rem;
        text-align: center;
        font-size: 1.8rem; /* Un poco más grande */
        font-weight: 700;
        border-bottom: 1px solid #1d4ed8; /* Un borde azul más oscuro */
    }
    .sidebar-nav { flex-grow: 1; margin-top: 1rem; }
    .nav-item {
        display: block;
        padding: 1rem 1.5rem;
        color: #dbeafe; /* Azul muy claro para el texto normal */
        text-decoration: none;
        border-left: 4px solid transparent;
        transition: all 0.2s;
    }
    .nav-item:hover {
        background-color: #1d4ed8; /* Azul oscuro al pasar el mouse */
        color: white;
    }
    .nav-item.active {
        background-color: #1e40af; /* Azul más oscuro para el activo */
        border-left-color: #60a5fa; /* Borde azul claro */
        color: white;
        font-weight: 700;
    }
    .sidebar-footer {
        padding: 1.5rem;
        border-top: 1px solid #1d4ed8;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        background-color: #1e3a8a; /* Fondo aún más oscuro para el pie */
    }
    .user-initials {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #f97316; /* Color naranja para que resalte */
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    /* --- (El resto de los estilos para el contenido principal no cambian) --- */
    .main-content { flex-grow: 1; padding: 2rem; overflow-y: auto; height: 100vh; }
    .content-header h1 { font-size: 1.8rem; color: #1e293b; margin: 0 0 2rem 0; }
        .content-view { display: none; }
        .content-view.active { display: block; }
        
        .data-card, .instructor-card { background-color: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 1.5rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .expand-button { background: none; border: none; font-size: 1rem; cursor: pointer; color: #2563eb; }
        .expandable-content { display: none; background-color: #f8fafc; }
        .expandable-content ul { margin: 0; padding: 1rem 1.5rem 1rem 4rem; list-style-type: disc; }
        
        .instructor-header { display: flex; align-items: center; gap: 1rem; padding: 1.5rem 2rem; border-bottom: 1px solid #e5e7eb; }
        .instructor-initials-avatar { width: 50px; height: 50px; border-radius: 50%; background-color: #f87171; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; font-size: 1.2rem; flex-shrink: 0; }
        .instructor-info h2 { margin: 0; font-size: 1.25rem; color: #1e293b; }
        .instructor-info p { margin: 0; color: #64748b; }
        /* Ajustes para la tabla principal de cursos */
        .courses-table th, .courses-table td { 
            padding: 12px 1.5rem; /* Asegura el padding base */
            text-align: left; 
            border-bottom: 1px solid #e5e7eb; 
        }
        .courses-table thead th { 
            font-weight: 700; text-transform: uppercase; font-size: 0.8rem; 
            color: #64748b; background-color: #f9fafb; 
        }

        /* Estilos para la fila que contiene la tabla de alumnos */
        .alumnos-row.hidden { display: none; }
        .alumnos-row td { 
            padding: 0; /* Importantísimo: remover el padding de la celda padre */
            background-color: #f9fafb; 
            border-bottom: none; /* Que no tenga borde inferior propio */
        }

        /* Estilos para la tabla de alumnos desplegable */
        .alumnos-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.9rem;
            margin-left: 0; /* Resetear cualquier margen */
        }
        .alumnos-table th, .alumnos-table td { 
            padding: 8px 1.5rem; /* Ajustar padding para que se vea anidado */
            text-align: left; 
            border-bottom: 1px solid #e0e7ff; 
        }
        .alumnos-table thead th { 
            background-color: #eef2ff; 
            color: #3730a3; 
            font-weight: 600; 
            padding-left: 3rem; /* Indentar cabecera */
        }
        .alumnos-table tbody td {
            padding-left: 3rem; /* Indentar contenido */
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">GestionAgil</div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active" data-view="dashboard">Dashboard</a>
            <a href="#" class="nav-item" data-view="maestros">Maestros</a>
            <a href="#" class="nav-item" data-view="alumnos">Alumnos</a>
        </nav>
        <div class="sidebar-footer" id="logout-button">
            <div id="user-initials" class="user-initials"></div>
            <span id="user-name"></span>
        </div>
    </aside>

    <main class="main-content">
        <div id="view-dashboard" class="content-view active">
            <div class="content-header"><h1>Resumen de profesores y cursos</h1></div>
            <div id="dashboard-content">Cargando resumen...</div>
        </div>
        <div id="view-maestros" class="content-view">
            <div class="content-header"><h1>Gestión de Maestros</h1></div>
            <div class="data-card"><table class="table-maestros"><thead><tr><th>Nombre</th><th>Correo</th><th>Fecha Creación</th><th>Cursos (#)</th></tr></thead><tbody id="maestros-table-body"></tbody></table></div>
        </div>
        <div id="view-alumnos" class="content-view">
             <div class="content-header"><h1>Gestión de Alumnos</h1></div>
             <div class="data-card"><table><thead><tr><th>Nombre</th><th>Correo</th><th>Fecha Creación</th><th>Cursos Inscritos</th></tr></thead><tbody id="alumnos-table-body"></tbody></table></div>
        </div>
    </main>

<script>
$(document).ready(function() {
    // --- LÓGICA DE LA CABECERA Y SESIÓN ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario || datosUsuario.rol !== 'admin') {
        window.location.href = 'inicio_sesion.php';
        return;
    }
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    $('#logout-button').on('click', function() {
        if (confirm("¿Deseas cerrar la sesión?")) {
            localStorage.removeItem('usuario');
            window.location.href = 'inicio_sesion.php';
        }
    });

    // --- LÓGICA DE NAVEGACIÓN DEL PANEL ---
    $('.nav-item').on('click', function(e) {
        e.preventDefault();
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        const viewToShow = $(this).data('view');
        $('.content-view').removeClass('active');
        $('#view-' + viewToShow).addClass('active');
    });

    // --- CARGA DE DATOS PARA CADA VISTA ---

    // 1. Cargar Dashboard
    $.ajax({
        url: '../api/adminDashboard.php',
        method: 'GET',
        success: function(instructores) {
            const listContainer = $("#dashboard-content");
            listContainer.empty();
            if (!instructores || instructores.length === 0) {
                listContainer.html("<p>No hay datos para mostrar.</p>");
                return;
            }
            instructores.forEach(function(instructor, instructorIndex) {
                let coursesAndAlumnosHTML = '';
                instructor.cursos.forEach(function(curso, cursoIndex) {
                    let alumnosHTML = '<tr><td colspan="2">No hay alumnos inscritos.</td></tr>';
                    if (curso.alumnos_inscritos.length > 0) {
                        alumnosHTML = curso.alumnos_inscritos.map(alumno => `<tr><td>${alumno.nombre_alumno}</td><td>${new Date(alumno.fecha_inscripcion).toLocaleDateString()}</td></tr>`).join('');
                    }
                    coursesAndAlumnosHTML += `
                        <tr class="fila-curso">
                            <td>${curso.nombre_curso} <button class="expand-button" data-toggle-id="dash-alumnos-${instructorIndex}-${cursoIndex}"><i class="fas fa-plus"></i></button></td>
                            <td>${curso.alumnos_inscritos.length}</td>
                        </tr>
                        <tr class="alumnos-row hidden" id="dash-alumnos-${instructorIndex}-${cursoIndex}"><td colspan="2"><table class="alumnos-table"><thead><tr><th>Alumno</th><th>Fecha de Inscripción</th></tr></thead><tbody>${alumnosHTML}</tbody></table></td></tr>`;
                });
                const inicialesInstructor = instructor.nombre_instructor.split(' ').map(n => n[0]).join('');
                const instructorCardHTML = `<div class="instructor-card"><div class="instructor-header"><div class="instructor-initials-avatar">${inicialesInstructor}</div><div class="instructor-info"><h2>${instructor.nombre_instructor}</h2><p>Profesor</p></div></div><table><thead><tr><th>Curso</th><th>Inscritos</th></tr></thead><tbody>${coursesAndAlumnosHTML}</tbody></table></div>`;
                listContainer.append(instructorCardHTML);
            });
        }
    });

    // 2. Cargar Maestros
    $.ajax({
        url: '../api/obtenerInstructores.php',
        method: 'GET',
        success: function(instructores) {
            const tbody = $('#maestros-table-body');
            tbody.empty();
            instructores.forEach((ins, index) => {
                let cursosList = ins.cursos.length > 0 ? '<ul>' + ins.cursos.map(c => `<li>${c}</li>`).join('') + '</ul>' : '<p style="padding:1rem 0;">No tiene cursos asignados.</p>';
                const fila = `
                    <tr>
                        <td>${ins.nombre}</td><td>${ins.email}</td><td>${new Date(ins.created_at).toLocaleDateString()}</td>
                        <td>${ins.total_cursos} <button class="expand-button" data-target="cursos-${index}"><i class="fas fa-plus"></i></button></td>
                    </tr>
                    <tr class="expandable-content" id="cursos-${index}"><td colspan="4">${cursosList}</td></tr>`;
                tbody.append(fila);
            });
        }
    });

    // 3. Cargar Alumnos
    $.ajax({
        url: '../api/obtenerAlumnos.php',
        method: 'GET',
        success: function(alumnos) {
            const tbody = $('#alumnos-table-body');
            tbody.empty();
            alumnos.forEach((alu, index) => {
                let cursosList = alu.cursos_inscritos.length > 0 ? '<ul>' + alu.cursos_inscritos.map(c => `<li>${c}</li>`).join('') + '</ul>' : '<p style="padding:1rem 0;">No está inscrito en cursos.</p>';
                const fila = `
                    <tr>
                        <td>${alu.nombre}</td><td>${alu.email}</td><td>${new Date(alu.created_at).toLocaleDateString()}</td>
                        <td>${alu.cursos_inscritos.length} <button class="expand-button" data-target="alu-cursos-${index}"><i class="fas fa-plus"></i></button></td>
                    </tr>
                    <tr class="expandable-content" id="alu-cursos-${index}"><td colspan="4">${cursosList}</td></tr>`;
                tbody.append(fila);
            });
        }
    });

    // Lógica para botones de expandir (+)
    $(document).on('click', '.expand-button', function() {
        $('#' + $(this).data('target')).toggle();
        $('#' + $(this).data('toggle-id')).toggleClass('hidden');
        $(this).find('i').toggleClass('fa-plus fa-minus');
    });
});
</script>
</body>
</html>