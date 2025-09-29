<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos</title>
    <link rel="stylesheet" href="../Principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Estilos específicos para el Dashboard */
        body { background-color: #f8f9fa; }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials {
            width: 40px; height: 40px;
            border-radius: 50%;
            background-color: #2563eb;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            margin-left: 1rem;
            cursor: pointer;
        }
        .cursos-container { padding: 2rem; max-width: 1200px; margin: auto; }
        .cursos-container h1 { margin-bottom: 2rem; text-align: center; }
        .cursos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .curso-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .curso-card .card-img { height: 150px; background-color: #e0e0e0; display: flex; justify-content: center; align-items: center; font-size: 3rem; }
        .curso-card .card-content { padding: 1rem; flex-grow: 1; display: flex; flex-direction: column; }
        .curso-card .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .curso-card .card-details { font-size: 0.9rem; color: #666; margin-bottom: 1rem; }
        .curso-card .card-button {
            display: block; text-align: center;
            padding: 0.75rem;
            margin-top: auto; /* Empuja el botón al fondo */
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo">GestionAgil</div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="&#128269; Buscar curso...">
        </div>
        <nav class="nav-links">
            </nav>
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
    // --- LÓGICA DE LA PÁGINA ---

    // 1. Revisar si el usuario ha iniciado sesión (sin cambios)
    const datosUsuarioString = localStorage.getItem('usuario');
    if (!datosUsuarioString) {
        window.location.href = 'inicio_sesion.php';
        return;
    }
    const usuario = JSON.parse(datosUsuarioString);

    // 2. Personalizar la cabecera (sin cambios)
    $("#user-name").text(usuario.nombre);
    const iniciales = usuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    
    if(usuario.rol === 'instructor' || usuario.rol === 'admin') {
        $(".nav-links").append('<a href="#">CREAR CURSO</a>');
        $(".nav-links").append('<a href="#">MIS CURSOS</a>');
    } else {
        $(".nav-links").append('<a href="#">MIS INSCRIPCIONES</a>');
    }

    // --- LÓGICA MEJORADA PARA CARGAR Y BUSCAR CURSOS ---

    // 3. Función para "dibujar" las tarjetas de cursos en la pantalla
    // La creamos para no repetir código
    function renderCursos(cursos) {
        const grid = $("#cursos-grid");
        grid.empty(); // Limpiamos la parrilla antes de dibujar los nuevos cursos

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

    // 4. Función para cargar TODOS los cursos (se usa al iniciar la página)
    function cargarTodosLosCursos() {
        $.ajax({
            url: '../api/obtenerCursos.php',
            method: 'GET',
            dataType: 'json',
            success: function(cursos) {
                renderCursos(cursos); // Usamos la nueva función para dibujar
            },
            error: function() {
                $("#cursos-grid").html("<p>Error al cargar los cursos.</p>");
            }
        });
    }

    // 5. Lógica de la barra de búsqueda (LA PARTE NUEVA)
    let debounceTimeout;
    $("#searchInput").on("keyup", function() {
        clearTimeout(debounceTimeout); // Reinicia el temporizador cada vez que se presiona una tecla
        const terminoBusqueda = $(this).val();

        // Espera 300ms después de la última tecla presionada para buscar
        debounceTimeout = setTimeout(function() {
            if (terminoBusqueda.length > 1) {
                // Si hay algo escrito, llama a la API de búsqueda
                $.ajax({
                    url: '../api/buscarCursos.php',
                    method: 'GET',
                    data: { q: terminoBusqueda }, // Pasamos el término como parámetro ?q=...
                    dataType: 'json',
                    success: function(response) {
                        renderCursos(response.datos); // Dibuja los resultados de la búsqueda
                    },
                    error: function() {
                        $("#cursos-grid").html("<p>Error al realizar la búsqueda.</p>");
                    }
                });
            } else {
                // Si la barra de búsqueda está vacía, vuelve a cargar todos los cursos
                cargarTodosLosCursos();
            }
        }, 300);
    });


    // --- El resto del código se queda igual ---
    function getEmojiForCategory(id_categoria) {
        switch(id_categoria) {
            case 1: return '💻'; case 2: return '🎨'; case 3: return '📈';
            case 4: return '💾'; case 5: return '📱'; case 6: return '📋';
            default: return '🎓';
        }
    }
    
    $("#user-initials").on("click", function() {
        if (confirm("¿Deseas cerrar la sesión?")) {
            localStorage.removeItem('usuario');
            window.location.href = 'inicio_sesion.php';
        }
    });

    // Carga inicial de todos los cursos al entrar a la página
    cargarTodosLosCursos();
});
</script>

</body>
</html>