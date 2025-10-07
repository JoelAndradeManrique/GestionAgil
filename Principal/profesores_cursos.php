<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores y Cursos - Tarjetas</title>
    <link rel="stylesheet" href="../css/profesores_cursos.css">
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
        <div class="header-and-search">
            <h1 class="page-title">Profesores y Cursos</h1>
            <div class="search-bar-wide">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar curso...">
            </div>
        </div>

        <div class="professor-section">
            <div class="professor-info">
                <img src="avatar_joel.jpg" alt="Avatar de Joel Andrade" class="professor-avatar">
                <span class="professor-name-title">Joel Andrade</span>
            </div>

            <div class="course-cards-container">
                
                <div class="course-card">
                    <a href="#" class="card-course-title">Curso de PHP</a>
                    <p class="card-course-category">Desarrollo Backend</p>
                </div>
                
                <div class="course-card">
                    <a href="#" class="card-course-title">Curso de JavaScript Avanzado</a>
                    <p class="card-course-category">Desarrollo Frontend</p>
                </div>

                </div>
        </div>
        
        <div class="professor-section">
            <div class="professor-info">
                <img src="avatar_ana.jpg" alt="Avatar de Ana Martínez" class="professor-avatar">
                <span class="professor-name-title">Ana Martínez</span>
            </div>

            <div class="course-cards-container">
                
                <div class="course-card">
                    <a href="#" class="card-course-title">Diseño UI/UX con Figma</a>
                    <p class="card-course-category">Diseño de Interfaces</p>
                </div>
                
            </div>
        </div>

    </div>
</body>
</html>