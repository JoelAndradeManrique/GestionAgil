<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="../principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Estilos específicos para este formulario */
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
            <div class="form-header">EDITAR CURSO</div>
            <form class="form-content" id="editarCursoForm"> 
                <div id="mensaje"></div>
                <div class="form-group">
                    <label for="titulo">TÍTULO DEL CURSO*</label>
                    <input type="text" id="titulo" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">DESCRIPCIÓN*</label>
                    <textarea id="descripcion" rows="4" required></textarea>
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
                        <input type="number" id="cupo" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">PRECIO (MXN)*</label>
                        <input type="number" id="precio" step="0.01" min="0" required>
                    </div>
                </div>
                 <div class="form-group">
                    <label for="estado">ESTADO*</label>
                    <select id="estado" required>
                        <option value="publicado">Publicado</option>
                        <option value="borrador">Borrador</option>
                        <option value="cerrado">Cerrado</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-agregar">ACTUALIZAR CURSO</button>
                </div>
            </form>
        </div>
    </main>
    
<script>
$(document).ready(function() {
    // --- LÓGICA DE LA CABECERA (la de siempre) ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario || (datosUsuario.rol !== 'instructor' && datosUsuario.rol !== 'admin')) {
        alert("Acceso denegado."); window.location.href = 'dashboard.php'; return;
    }
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    // ... (resto de la lógica de la cabecera)

    // --- LÓGICA DE LA PÁGINA "EDITAR CURSO" ---
    const urlParams = new URLSearchParams(window.location.search);
    const idCurso = urlParams.get('id');

    if (!idCurso) {
        alert("No se especificó un curso para editar.");
        window.location.href = 'mis-cursos.php';
        return;
    }

    // 1. CARGAR CATEGORÍAS
    $.ajax({
        url: '../api/obtenerCategorias.php',
        method: 'GET',
        success: function(categorias) {
            const selectCategoria = $("#categoria");
            selectCategoria.append('<option value="">-- Selecciona --</option>');
            categorias.forEach(cat => {
                selectCategoria.append(`<option value="${cat.id_categoria}">${cat.nombre}</option>`);
            });
        }
    }).done(function() {
        // 2. DESPUÉS DE CARGAR CATEGORÍAS, CARGAMOS LOS DATOS DEL CURSO
        $.ajax({
            url: `../api/obtenerCurso.php?id=${idCurso}`,
            method: 'GET',
            success: function(response) {
                const curso = response.datos;
                // 3. RELLENAMOS EL FORMULARIO
                $("#titulo").val(curso.titulo);
                $("#descripcion").val(curso.descripcion);
                $("#categoria").val(curso.id_categoria);
                $("#fecha_inicio").val(curso.fecha_inicio);
                $("#fecha_fin").val(curso.fecha_fin);
                $("#cupo").val(curso.cupo_disponible);
                $("#precio").val(curso.precio);
                $("#modalidad").val(curso.modalidad);
                $("#estado").val(curso.estado);
            },
            error: function() { alert("Error al cargar los datos del curso."); }
        });
    });

    // 4. LÓGICA PARA ENVIAR LA ACTUALIZACIÓN
    $("#editarCursoForm").on("submit", function(event) {
        event.preventDefault();
        
        let datosCursoActualizado = {
            id_curso: idCurso,
            titulo: $("#titulo").val(),
            descripcion: $("#descripcion").val(),
            id_categoria: $("#categoria").val(),
            fecha_inicio: $("#fecha_inicio").val(),
            fecha_fin: $("#fecha_fin").val(),
            cupo_disponible: $("#cupo").val(),
            precio: $("#precio").val(),
            modalidad: $("#modalidad").val(),
            estado: $("#estado").val(),
            id_instructor: datosUsuario.id_usuario
        };

        $.ajax({
            url: '../api/editarCurso.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datosCursoActualizado),
            success: function(response) {
                alert(response.mensaje);
                window.location.href = `gestion_alumnos.php?id=${idCurso}`; // Vuelve a la página de gestión
            },
            error: function(jqXHR) {
                alert(jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Error desconocido.");
            }
        });
    });
});
</script>

</body>
</html>