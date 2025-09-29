<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="../css/gestion_alumnos.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-title">
                <i class="fas fa-info-circle"></i>
                <span>GESTIÓN DE ALUMNOS</span>
            </div>
            <button class="new-record-btn">
                <i class="fas fa-plus"></i>
                Nuevo Registro
            </button>
        </header>

        <main class="content-box">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar" onkeyup="filterTable()">
            </div>

            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Alumno</th>
                            <th>Correo electrónico</th>
                            <th>Curso</th>
                            <th>Modalidad</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01</td>
                            <td>Ana López</td>
                            <td>ana@example.com</td>
                            <td>En línea</td>
                            <td>En línea</td>
                            <td class="options-cell">
                                <button class="btn btn-red" title="Eliminar" onclick="confirmAction('eliminar', 'Ana López')"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green" title="Ver" onclick="confirmAction('ver', 'Ana López')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue" title="Editar" onclick="confirmAction('editar', 'Ana López')"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>Luis Pérez</td>
                            <td>luiso</td>
                            <td>En ñaña</td>
                            <td>Presencial</td>
                            <td class="options-cell">
                                <button class="btn btn-red" onclick="confirmAction('eliminar', 'Luis Pérez')"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green" onclick="confirmAction('ver', 'Luis Pérez')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue" onclick="confirmAction('editar', 'Luis Pérez')"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td>Luis Pérez</td>
                            <td>luis@example.com</td>
                            <td>En ñaña</td>
                            <td>Presencial</td>
                            <td class="options-cell">
                                <button class="btn btn-red" onclick="confirmAction('eliminar', 'Luis Pérez (ID 03)')"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green" onclick="confirmAction('ver', 'Luis Pérez (ID 03)')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue" onclick="confirmAction('editar', 'Luis Pérez (ID 03)')"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td>Luis Pérez</td>
                            <td>luitesemenee</td>
                            <td>En línea</td>
                            <td>En línea</td>
                            <td class="options-cell">
                                <button class="btn btn-red" onclick="confirmAction('eliminar', 'Luis Pérez (ID 04)')"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green" onclick="confirmAction('ver', 'Luis Pérez (ID 04)')"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue" onclick="confirmAction('editar', 'Luis Pérez (ID 04)')"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
        /**
         * Función que muestra una ventana de confirmación antes de realizar una acción.
         * ... (código interno de confirmAction es correcto) ...
         */
        function confirmAction(actionType, recordName) {
            let message = ""; 
            let actionConfirmed = false; 

            switch (actionType) {
                case 'eliminar':
                    message = "¿Estás seguro de que deseas eliminar el registro de " + recordName + "? Esta acción no se puede deshacer.";
                    break;
                case 'ver':
                    message = "¿Deseas ver los detalles completos del registro de " + recordName + "?";
                    break;
                case 'editar':
                    message = "¿Estás seguro de que quieres actualizar el registro de " + recordName + "? Se abrirá el formulario de edición.";
                    break;
                default:
                    message = "¿Estás seguro de realizar esta acción en el registro de " + recordName + "?";
                    break;
            }

            actionConfirmed = confirm(message);

            if (actionConfirmed) {
                console.log("Acción '" + actionType.toUpperCase() + "' confirmada para: " + recordName);
                // ... (lógica de acción real) ...
            } else {
                console.log("Acción '" + actionType.toUpperCase() + "' cancelada por el usuario para: " + recordName);
            }

            return actionConfirmed;
        }

        /**
         * Función que filtra las filas de la tabla en tiempo real.
         * ... (código interno de filterTable es correcto) ...
         */
        function filterTable() {
            // 1. Obtener el valor de búsqueda (funciona gracias al ID añadido arriba)
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            
            // 2. Obtener todas las filas
            const tableBody = document.querySelector('.data-table tbody');
            if (!tableBody) return; 
            
            const rows = tableBody.getElementsByTagName('tr');

            // 3. Iterar y buscar
            for (let i = 0; i < rows.length; i++) {
                let row = rows[i];
                let cells = row.getElementsByTagName('td');
                
                // Las posiciones de las celdas para la búsqueda son correctas: 1, 2 y 3.
                let nombre = cells[1] ? cells[1].textContent.toLowerCase() : '';
                let correo = cells[2] ? cells[2].textContent.toLowerCase() : '';
                let curso = cells[3] ? cells[3].textContent.toLowerCase() : '';

                // 4. Verificar coincidencia (lógica correcta)
                if (nombre.includes(searchValue) || correo.includes(searchValue) || curso.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }
    </script> 
</body>
</html>