<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Inscripciones</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
    /* Estilos generales */
    body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; color: #333; }
    a { color: #2563eb; font-weight: 700; text-decoration: none; }
    a:hover { text-decoration: underline; }

    /* Estilos de la Cabecera (sin cambios) */
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
    .search-bar { flex-grow: 1; margin: 0 2rem; }
    .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
    .nav-links { margin-right: 2rem; }
    .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
    .user-profile { display: flex; align-items: center; }
    .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
    
    /* Estilos del Catálogo */
    .catalogo-container { max-width: 1200px; margin: auto; padding: 2rem; }
    .catalogo-container h1 { font-size: 2.5rem; font-weight: 700; text-align: center; margin-bottom: 2rem; }
    
    /* ✅ AQUÍ ESTÁ LA CORRECCIÓN */
    .filtros-container { display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; }

    /* Le decimos a la barra de búsqueda que crezca y ocupe el espacio sobrante */
    .filtros-container .search-bar-local { flex-grow: 1; }

    /* Y le quitamos el width: 100% a todos para que no peleen */
    .filtros-container input, .filtros-container select {
        padding: 0.8rem 1rem;
        font-size: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
    }
    .filtros-container select {
        min-width: 200px; /* Les damos un ancho mínimo para que no se aplasten */
    }
    /* FIN DE LA CORRECCIÓN */

    .cursos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .curso-card { background-color: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); overflow: hidden; text-align: center; padding: 1.5rem; display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .curso-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .curso-card .card-icon { font-size: 3rem; margin-bottom: 1rem; }
    .curso-card .card-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
    .curso-card .card-details { color: #666; margin-bottom: 1.5rem; flex-grow: 1; }
    .curso-card .card-button { display: block; padding: 0.75rem; background-color: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 700; }
</style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo"><a href="dashboard.php">GestionAgil</a></div>
        <div class="search-bar">
            <form id="searchFormGlobal">
                <input type="text" id="searchInputGlobal" placeholder="Buscar en todo el catálogo...">
            </form>
        </div>
        <nav class="nav-links"></nav>
        <div class="user-profile">
            <span id="user-name"></span>
            <div id="user-initials" class="user-initials"></div>
        </div>
    </header>

    <main class="catalogo-container">
        <h1>Mis Inscripciones</h1>
        <div class="filtros-container">
            <div class="search-bar-local">
                <input type="text" id="filtro-q" placeholder="Buscar en mis cursos...">
            </div>
            <select id="filtro-categoria"><option value="">Toda categoría</option></select>
            <select id="filtro-modalidad"><option value="">Toda modalidad</option><option value="en_linea">En línea</option><option value="presencial">Presencial</option><option value="hibrido">Híbrido</option></select>
        </div>
        <div id="cursos-grid" class="cursos-grid"></div>
    </main>

<script>
$(document).ready(function() {
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario) { window.location.href = 'inicio_sesion.php'; return; }

    // --- LÓGICA DE LA CABECERA ---
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    if(datosUsuario.rol === 'alumno') {
        $(".nav-links").append('<a href="mis_inscripciones.php" style="color: #2563eb;">MIS INSCRIPCIONES</a>');
    }
    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; }});
    
    // ✅ Lógica para la barra de búsqueda GLOBAL (en la cabecera)
    $("#searchFormGlobal").on("submit", function(event) {
        event.preventDefault();
        const termino = $("#searchInputGlobal").val();
        if (termino.trim() !== '') {
            window.location.href = `dashboard.php?q=${termino}`;
        }
    });

    // --- LÓGICA DE LA PÁGINA "MIS INSCRIPCIONES" ---
    
    // Llenar el filtro de categorías
    $.ajax({
        url: '../api/obtenerCategorias.php',
        method: 'GET',
        success: function(categorias) {
            categorias.forEach(cat => $("#filtro-categoria").append(`<option value="${cat.id_categoria}">${cat.nombre}</option>`));
        }
    });

    // Función para aplicar filtros y buscar SÓLO EN MIS INSCRIPCIONES
    function aplicarFiltrosLocales() {
        let filtros = {
            id_usuario: datosUsuario.id_usuario, // Filtro base siempre activo
            q: $("#filtro-q").val(),
            categoria: $("#filtro-categoria").val(),
            modalidad: $("#filtro-modalidad").val(),
        };
        $.ajax({
            url: '../api/obtenerMisInscripciones.php',
            method: 'GET',
            data: filtros,
            success: function(cursos) { renderCursos(cursos); }
        });
    }

    function renderCursos(cursos) {
        const grid = $("#cursos-grid");
        grid.empty();
        if(cursos.length === 0) { grid.html("<p>No tienes inscripciones que coincidan con estos filtros.</p>"); return; }
        cursos.forEach(function(curso) {
            const cardHTML = `
                <div class="curso-card">
                    <div class="card-icon">${getEmojiForCategory(curso.id_categoria)}</div>
                    <h3 class="card-title">${curso.titulo}</h3>
                    <div class="card-details">
                        <p>${curso.nombre_instructor}</p>
                        <p>${curso.modalidad}</p>
                    </div>
                    <a href="detalle_curso.php?id=${curso.id_curso}" class="card-button">Ver detalles</a>
                </div>
            `;
            grid.append(cardHTML);
        });
    }

    // ✅ Asignar los eventos a los filtros LOCALES
    let debounce;
    $("#filtro-q").on('keyup', function() { clearTimeout(debounce); debounce = setTimeout(aplicarFiltrosLocales, 500); });
    $("#filtro-categoria, #filtro-modalidad").on('change', aplicarFiltrosLocales);

    // Carga inicial
    aplicarFiltrosLocales();
    
    function getEmojiForCategory(id) {
        const emojis = { 1: '💻', 2: '🎨', 3: '📈', 4: '💾', 5: '📱', 6: '📋' };
        return emojis[id] || '🎓';
    }
});
</script>
</body>
</html>