<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="../principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
        <div class="form-box" style="max-width: 650px;">
            <h1>Editar Curso</h1>
            <p>Actualiza la información de tu curso.</p>
            <div id="mensaje"></div>

            <form id="editarCursoForm">
                <div class="form_grupo">
                    <label for="titulo">Título del Curso</label>
                    <input type="text" id="titulo" required>
                </div>

                <div class="form_grupo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" rows="4" required></textarea>
                </div>
                
                <div class="form_grupo">
                    <label for="categoria">Categoría</label>
                    <select id="categoria" required></select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <div class="form_grupo" style="flex: 1;">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" id="fecha_inicio" required>
                    </div>
                    <div class="form_grupo" style="flex: 1;">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" id="fecha_fin" required>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <div class="form_grupo" style="flex: 1;">
                        <label for="cupo">Cupos Disponibles</label>
                        <input type="number" id="cupo" required>
                    </div>
                    <div class="form_grupo" style="flex: 1;">
                        <label for="precio">Precio (MXN)</label>
                        <input type="number" id="precio" step="0.01" required>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <div class="form_grupo" style="flex: 1;">
                        <label for="modalidad">Modalidad</label>
                        <select id="modalidad" required>
                            <option value="en_linea">En línea</option>
                            <option value="presencial">Presencial</option>
                            <option value="hibrido">Híbrido</option>
                        </select>
                    </div>
                    <div class="form_grupo" style="flex: 1;">
                        <label for="estado">Estado</label>
                        <select id="estado" required>
                            <option value="publicado">Publicado</option>
                            <option value="borrador">Borrador</option>
                            <option value="cerrado">Cerrado</option>
                        </select>
                    </div>
                </div>

                <div class="btn">
                    <input type="submit" value="ACTUALIZAR CURSO">
                </div>
            </form>
        </div>
    </main>

<script>
$(document).ready(function() {
    // --- LÓGICA DE LA CABECERA (la de siempre) ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    if (!datosUsuario || (datosUsuario.rol !== 'instructor' && datosUsuario.rol !== 'admin')) {
        alert("Acceso denegado.");
        window.location.href = 'dashboard.php';
        return;
    }
    // (Aquí va el resto del script de la cabecera)
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    // ... etc ...

    const urlParams = new URLSearchParams(window.location.search);
    const idCurso = urlParams.get('id');

    if (!idCurso) {
        alert("No se especificó un curso para editar.");
        window.location.href = 'mis-cursos.php';
        return;
    }

    // --- LÓGICA DE LA PÁGINA "EDITAR CURSO" ---

    // 1. CARGAR CATEGORÍAS (igual que en crear)
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
    }).done(function() {
        // 2. UNA VEZ CARGADAS LAS CATEGORÍAS, PEDIMOS LOS DATOS DEL CURSO A EDITAR
        $.ajax({
            url: `../api/obtenerCurso.php?id=${idCurso}`,
            method: 'GET',
            success: function(response) {
                const curso = response.datos;

                // 3. RELLENAMOS EL FORMULARIO CON LOS DATOS OBTENIDOS
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
            error: function() {
                alert("Error al cargar los datos del curso.");
            }
        });
    });

    // 4. LÓGICA PARA ENVIAR EL FORMULARIO ACTUALIZADO
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
                window.location.href = `gestion_alumnos.php?id=${idCurso}`;
            },
            error: function(jqXHR) {
                alert(jqXHR.responseJSON.mensaje);
            }
        });
    });
});
</script>

</body>
</html>