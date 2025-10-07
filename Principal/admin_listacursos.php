<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso de PHP - Lista de Inscritos</title>
    <link rel="stylesheet" href="../css/admin_listacursos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">GestionAgil</div>
        <nav>
            <a href="#">MIS INSCRIPCIONES</a>
            <span class="user-name">Jair Canul</span>
            <div class="user-avatar">JC</div>
        </nav>
    </header>

    <div class="main-content-wrapper">
        <div class="course-details-block">
            
            <a href="#" class="back-link">
                <i class="fas fa-arrow-left"></i> Volver a la lista de cursos
            </a>

            <h1 class="course-title">Curso de PHP</h1>
            <p class="course-subtitle">Lista de estudiantes inscritos</p>

            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>NOMBRE DEL ESTUDIANTE</th>
                            <th>CORREO ELECTRÓNICO</th>
                            <th class="text-right">FECHA DE INSCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Carlos Sanchez</td>
                            <td>carlos.sanchez@example.com</td>
                            <td class="text-right">15 de Septiembre, 2023</td>
                        </tr>
                        <tr>
                            <td>Maria Rodriguez</td>
                            <td>maria.rodriguez@example.com</td>
                            <td class="text-right">16 de Septiembre, 2023</td>
                        </tr>
                        <tr>
                            <td>Luis Gomez</td>
                            <td>luis.gomez@example.com</td>
                            <td class="text-right">17 de Septiembre, 2023</td>
                        </tr>
                        <tr>
                            <td>Laura Fernandez</td>
                            <td>laura.fernandez@example.com</td>
                            <td class="text-right">18 de Septiembre, 2023</td>
                        </tr>
                        </tbody>
                </table>
            </div>
            
            <div class="warning-note">
                <span class="note-label">Nota:</span> Este curso ya no tiene cupos disponibles. La lista de estudiantes está cerrada.
            </div>

        </div>
    </div>
</body>
</html>