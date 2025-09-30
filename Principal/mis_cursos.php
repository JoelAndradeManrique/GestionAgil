<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos Creados</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; color: #333; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links { margin-right: 2rem; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
        .cursos-container { padding: 2rem; max-width: 1200px; margin: auto; }
        .cursos-container h1 { margin-bottom: 2rem; text-align: center; color: #1e3a8a;}
        .cursos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
        .curso-card { background-color: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); overflow: hidden; text-align: center; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .curso-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .curso-card .card-icon { font-size: 3rem; margin-bottom: 1rem; }
        .curso-card .card-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .curso-card .card-details { color: #666; margin-bottom: 1.5rem; flex-grow: 1; }
        .curso-card .card-button { display: block; padding: 0.75rem; background-color: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 700; }
        .container-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .container-header h1 {
            margin-bottom: 0; /* Quitamos el margen que tenía para alinearlo bien */
        }
        .btn-crear {
            padding: 0.75rem 1.5rem;
            background-color: #16a34a; /* Verde para una acción positiva */
            color: white;
            font-weight: 700;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .btn-crear:hover {
            background-color: #15803d;
            text-decoration: none;
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

    <main class="cursos-container">
        <div class="container-header">
            <h1>Mis Cursos Creados</h1>
            <a href="registro_curso.php" class="btn-crear">Crear Nuevo Curso</a>
        </div>
        <div id="cursos-grid" class="cursos-grid">
            </div>
    </main>

<script>
$(document).ready(function() {
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario || (datosUsuario.rol !== 'instructor' && datosUsuario.rol !== 'admin')) {
        // Protegemos la ruta para que solo instructores o admins puedan verla
        window.location.href = 'dashboard.php';
        return;
    }

    // --- LÓGICA DE LA CABECERA (la nueva versión) ---
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    
    const paginaActual = window.location.pathname.split('/').pop();
    if (datosUsuario.rol === 'instructor' || datosUsuario.rol === 'admin') {
        let estiloInscripciones = (paginaActual === 'mis_inscripciones.php') ? 'style="color: #2563eb;"' : '';
        let estiloCursos = (paginaActual === 'mis_ursos.php') ? 'style="color: #2563eb;"' : '';
        $(".nav-links").append(`<a href="mis_inscripciones.php" ${estiloInscripciones}>MIS INSCRIPCIONES</a>`);
        $(".nav-links").append(`<a href="mis_cursos.php" ${estiloCursos}>MIS CURSOS</a>`);
        $(".nav-links").append('<a href="registro_curso.php">CREAR CURSO</a>');
    } else {
        let estiloInscripciones = (paginaActual === 'mis_inscripciones.php') ? 'style="color: #2563eb;"' : '';
        $(".nav-links").append(`<a href="mis_inscripciones.php" ${estiloInscripciones}>MIS INSCRIPCIONES</a>`);
    }
    
    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
    $("#searchFormGlobal").on("submit", function(event) { event.preventDefault(); const t = $("#searchInputGlobal").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; }});


    // --- LÓGICA DE LA PÁGINA "MIS CURSOS" ---
    $.ajax({
        // Llamamos a la API que creamos para obtener los cursos por instructor
        url: `../api/obtenerMisCursos.php?id_instructor=${datosUsuario.id_usuario}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            const grid = $("#cursos-grid");
            grid.empty();

            if(response.datos.length === 0) {
                grid.html("<p>Aún no has creado ningún curso.</p>");
                return;
            }

            response.datos.forEach(function(curso) {
                const cardHTML = `
                    <div class="curso-card">
                        <div class="card-icon">${getEmojiForCategory(curso.id_categoria)}</div>
                        <h3 class="card-title">${curso.titulo.toUpperCase()}</h3>
                        <div class="card-details">
                            <p>${curso.modalidad}</p>
                            <p>Cupos restantes: ${curso.cupo_disponible}</p>
                        </div>
                        <a href="gestion_alumnos.php?id=${curso.id_curso}" class="card-button">GESTIONAR CURSO</a>
                    </div>
                `;
                grid.append(cardHTML);
            });
        },
        error: function() {
            $("#cursos-grid").html("<p>Error al cargar tus cursos.</p>");
        }
    });

    function getEmojiForCategory(id) {
        const emojis = { 1: '💻', 2: '🎨', 3: '📈', 4: '💾', 5: '📱', 6: '📋' };
        return emojis[id] || '🎓';
    }
});
</script>
</body>
</html>