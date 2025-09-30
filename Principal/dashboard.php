<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Cursos</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Estos estilos ya deberían estar en tu archivo principal estilos.css */
        /* Los dejo aquí por si acaso, pero lo ideal es tenerlos separados */
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links { margin-right: 2rem; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
        .cursos-container { padding: 2rem; max-width: 1200px; margin: auto; }
        .cursos-container h1 { margin-bottom: 2rem; text-align: center; color: #1e3a8a;}
        .cursos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; }
        .curso-card { background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
        .curso-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }
        .curso-card .card-img { height: 150px; background-color: #e2e8f0; display: flex; justify-content: center; align-items: center; font-size: 3rem; color: #4a5568;}
        .curso-card .card-content { padding: 1rem; flex-grow: 1; display: flex; flex-direction: column; }
        .curso-card .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: #1e3a8a;}
        .curso-card .card-details { font-size: 0.9rem; color: #666; margin-bottom: 0.5rem; line-height: 1.4; }
        .curso-card .card-button { display: block; text-align: center; padding: 0.75rem; margin-top: auto; background-color: #2563eb; color: white; text-decoration: none; border-radius: 5px; font-weight: 700; transition: background-color 0.2s; }
        .curso-card .card-button:hover { background-color: #1e40af; text-decoration: none;}
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo"><a href="dashboard.php" style="text-decoration:none; color: #1e3a8a;">GestionAgil</a></div>
        <div class="search-bar">
            <form id="searchForm">
                <input type="text" id="searchInput" placeholder="&#128269; Buscar curso...">
            </form>
        </div>
        <nav class="nav-links"></nav>
        <div class="user-profile">
            <span id="user-name"></span>
            <div id="user-initials" class="user-initials"></div>
        </div>
    </header>

    <main class="cursos-container">
        <h1>CURSOS DESTACADOS</h1>
        <div id="cursos-grid" class="cursos-grid">
            </div>
    </main>

<script>
$(document).ready(function() {
   const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario) {
        // Si no hay sesión, redirige al login.
        window.location.href = 'inicio_sesion.php'; 
        return; // Detiene la ejecución para no mostrar nada
    }

    // 2. PERSONALIZAR NOMBRE E INICIALES
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);

    // 3. LÓGICA PARA MOSTRAR Y RESALTAR ENLACES DE NAVEGÁCIÓN
    const paginaActual = window.location.pathname.split('/').pop();

    // Preparamos los estilos para resaltar la página activa
    let estiloInscripciones = (paginaActual === 'mis_inscripciones.php') ? 'style="color: #2563eb;"' : '';
    let estiloCursos = (paginaActual === 'mis_cursos.php') ? 'style="color: #2563eb;"' : '';

    // El enlace "Mis Inscripciones" siempre se muestra
    $(".nav-links").append(`<a href="mis_inscripciones.php" ${estiloInscripciones}>MIS INSCRIPCIONES</a>`);

    // Si el usuario es instructor o admin, añadimos el enlace a "Mis Cursos"
    if (datosUsuario.rol === 'instructor' || datosUsuario.rol === 'admin') {
        $(".nav-links").append(`<a href="mis_cursos.php" ${estiloCursos}>MIS CURSOS</a>`);
    }

    // 4. LÓGICA DE CERRAR SESIÓN Y BÚSQUEDA GLOBAL
    $("#user-initials").on("click", function() {
        if (confirm("¿Deseas cerrar la sesión?")) {
            localStorage.removeItem('usuario');
            window.location.href = 'inicio_sesion.php';
        }
    });

    $("#searchFormGlobal").on("submit", function(event) {
        event.preventDefault();
        const termino = $("#searchInputGlobal").val();
        if (termino.trim() !== '') {
            // La búsqueda global siempre te lleva al dashboard principal
            window.location.href = `dashboard.php?q=${termino}`;
        }
    });

    function renderCursos(cursos) {
        const grid = $("#cursos-grid");
        grid.empty();
        if(cursos.length === 0) {
            grid.html("<p>No se encontraron cursos que coincidan con tu búsqueda.</p>");
            return;
        }
        cursos.forEach(function(curso) {
            const fechaInicio = new Date(curso.fecha_inicio).toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
            const fechaFin = new Date(curso.fecha_fin).toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
            const cardHTML = `
                <div class="curso-card">
                    <div class="card-img">${getEmojiForCategory(curso.id_categoria)}</div>
                    <div class="card-content">
                        <h3 class="card-title">${curso.titulo.toUpperCase()}</h3>
                        <p class="card-details">${curso.modalidad.toUpperCase()}</p>
                        <p class="card-details">${fechaInicio} - ${fechaFin}</p>
                        <a href="detalle_curso.php?id=${curso.id_curso}" class="card-button">VER DETALLES</a>
                    </div>
                </div>
            `;
            grid.append(cardHTML);
        });
    }

    function cargarTodosLosCursos() {
        $.ajax({
            url: '../api/obtenerCursos.php',
            method: 'GET',
            dataType: 'json',
            success: function(cursos) {
                renderCursos(cursos);
            },
            error: function() {
                $("#cursos-grid").html("<p>Error al cargar los cursos.</p>");
            }
        });
    }

    let debounceTimeout;
    $("#searchInput").on("keyup", function() {
        clearTimeout(debounceTimeout);
        const terminoBusqueda = $(this).val();
        debounceTimeout = setTimeout(function() {
            if (terminoBusqueda.length > 1) {
                $.ajax({
                    url: '../api/buscarCursos.php',
                    method: 'GET',
                    data: { q: terminoBusqueda },
                    dataType: 'json',
                    success: function(response) {
                        renderCursos(response.datos);
                    },
                    error: function() {
                        $("#cursos-grid").html("<p>Error al realizar la búsqueda.</p>");
                    }
                });
            } else if (terminoBusqueda.length === 0) {
                cargarTodosLosCursos();
            }
        }, 300);
    });

    function getEmojiForCategory(id_categoria) {
        const emojis = { 1: '💻', 2: '🎨', 3: '📈', 4: '💾', 5: '📱', 6: '📋' };
        return emojis[id_categoria] || '🎓';
    }
    
    $("#user-initials").on("click", function() {
        if (confirm("¿Deseas cerrar la sesión?")) {
            localStorage.removeItem('usuario');
            window.location.href = 'inicio_sesion.php';
        }
    });

    const urlParams = new URLSearchParams(window.location.search);
    const busquedaInicial = urlParams.get('q');

    if (busquedaInicial) {
        $("#searchInput").val(busquedaInicial);
        $("#searchInput").trigger('keyup');
    } else {
        cargarTodosLosCursos();
    }
});
</script>

</body>
</html>