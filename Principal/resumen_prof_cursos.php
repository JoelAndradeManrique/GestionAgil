<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Profesores y Cursos - GestionAgil</title>
    <link rel="stylesheet" href="../css/resumen_prof_cursos.css">
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
        <h1 class="page-title">Resumen de profesores y cursos</h1>

        <div class="professor-block">
            <div class="professor-header">
                <img src="avatar_joel.jpg" alt="Avatar de Joel Andrade" class="professor-avatar">
                <div>
                    <h2 class="professor-name">Joel Andrade</h2>
                    <p class="professor-title">Profesor de Desarrollo Web</p>
                </div>
            </div>

            <div class="search-bar-small">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar curso asignado..." onkeyup="filterCourses('joel-courses', this.value)">
            </div>

            <div class="courses-table">
                <table id="joel-courses">
                    <thead>
                        <tr>
                            <th>NOMBRE DEL CURSO</th>
                            <th>CATEGORÍA</th>
                            <th class="text-right">ESTUDIANTES INSCRITOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="#">Curso de PHP</a></td>
                            <td>Desarrollo Backend</td>
                            <td class="text-right">25</td>
                        </tr>
                        <tr>
                            <td><a href="#">Curso de JavaScript Avanzado</a></td>
                            <td>Desarrollo Frontend</td>
                            <td class="text-right">18</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
        
        <div class="professor-block">
            <div class="professor-header">
                <img src="avatar_ana.jpg" alt="Avatar de Ana Martínez" class="professor-avatar">
                <div>
                    <h2 class="professor-name">Ana Martínez</h2>
                    <p class="professor-title">Profesora de Diseño</p>
                </div>
            </div>

            <div class="search-bar-small">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar curso asignado..." onkeyup="filterCourses('ana-courses', this.value)">
            </div>

            <div class="courses-table">
                <table id="ana-courses">
                    <thead>
                        <tr>
                            <th>NOMBRE DEL CURSO</th>
                            <th>CATEGORÍA</th>
                            <th class="text-right">ESTUDIANTES INSCRITOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="#">Diseño UI/UX con Figma</a></td>
                            <td>Diseño de Interfaces</td>
                            <td class="text-right">32</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <script src="resumen_profesores.js"></script>
</body>
</html>