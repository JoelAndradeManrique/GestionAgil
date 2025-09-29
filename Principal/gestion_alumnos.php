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
                <input type="text" placeholder="Buscar">
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
                                <button class="btn btn-red" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green" title="Ver"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue" title="Editar"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td>Luis Pérez</td>
                            <td>luiso</td>
                            <td>En ñaña</td>
                            <td>Presencial</td>
                            <td class="options-cell">
                                <button class="btn btn-red"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td>Luis Pérez</td>
                            <td>luis@example.com</td>
                            <td>En ñaña</td>
                            <td>Presencial</td>
                            <td class="options-cell">
                                <button class="btn btn-red"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td>Luis Pérez</td>
                            <td>luitesemenee</td>
                            <td>En línea</td>
                            <td>En línea</td>
                            <td class="options-cell">
                                <button class="btn btn-red"><i class="fas fa-trash-alt"></i></button>
                                <button class="btn btn-green"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-blue"><i class="fas fa-pen"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>