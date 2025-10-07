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

        <div id="professors-list-container">
            <p style="text-align: center; color: #4a90e2;">Cargando resumen de profesores...</p>
        </div>
    </div>
    
    <script>
    // =======================================================================
    // CONFIGURACIÓN CLAVE (AJUSTA ESTAS RUTAS)
    // =======================================================================
    // ⚠️ ADVERTENCIA: Se mantiene adminDashboard.php, pero debes asegurar que devuelva 
    // la estructura JSON agrupada.
    const API_URL = '../api/adminDashboard.php'; 
    const API_DETALLES_CURSO_URL = 'detalle_curso.html?id='; 
    const CONTAINER_ID = 'professors-list-container';

    // Se llama a la carga cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', loadProfessorData);

    // =======================================================================
    // 3. FUNCIONALIDAD DE FILTRADO (GLOBAL) 🎯
    // Esta función DEBE ser global para que onkeyup pueda acceder a ella.
    // =======================================================================

    function filterCourses(tableId, searchValue) {
        const filter = searchValue.toLowerCase();
        const table = document.getElementById(tableId);
        if (!table) return;

        const rows = table.getElementsByTagName('tr'); 
        for (let i = 1; i < rows.length; i++) { // Empieza en 1 para saltar el THEAD
            let cells = rows[i].getElementsByTagName('td');
            let found = false;
            
            // Buscar en Nombre (cells[0]) y Categoría (cells[1])
            if (cells[0] && cells[0].textContent.toLowerCase().includes(filter)) {
                found = true;
            } else if (cells[1] && cells[1].textContent.toLowerCase().includes(filter)) {
                found = true;
            }

            rows[i].style.display = found ? "" : "none";
        }
    }


    // =======================================================================
    // 1. CARGA DE DATOS (ASYNC)
    // =======================================================================

    async function loadProfessorData() {
        const container = document.getElementById(CONTAINER_ID);
        if (!container) return;

        try {
            const response = await fetch(API_URL);
            const respuestaApi = await response.json(); 
            // Accedemos a la clave 'datos' que devuelve tu controlador PHP
            const profesores = respuestaApi.datos; 

            if (!response.ok || !Array.isArray(profesores) || profesores.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: red;">No se encontraron profesores ni cursos para mostrar.</p>';
                return;
            }
            
            container.innerHTML = ''; // Limpiar el mensaje de carga
            
            // Renderizar los datos
            profesores.forEach(profesor => {
                const block = createProfessorBlock(profesor);
                container.appendChild(block);
            });
            
        } catch (error) {
            container.innerHTML = '<p style="text-align: center; color: red;">Error de conexión con el servidor. Verifica la URL de la API.</p>';
            console.error("Error de FETCH:", error);
            return;
        }
    }

    // =======================================================================
    // 2. RENDEREADO DINÁMICO DE BLOQUES
    // =======================================================================

    function createProfessorBlock(profesor) {
        const block = document.createElement('div');
        block.className = 'professor-block';

        // Usa el nombre del instructor (CLAVE DEL MODELO) para el ID
        const uniqueId = `table-${profesor.nombre_instructor.replace(/\s/g, '-')}`; 

        // Crea un placeholder para el avatar usando las iniciales del nombre
        const avatarPlaceholderText = profesor.nombre_instructor.substring(0, 2);
        
        // El JS debe manejar el caso de que tu SQL no devuelva estas claves
        const profesorTitulo = profesor.titulo || 'Profesor Asignado'; 
        const avatarSrc = profesor.avatar_url || `https://via.placeholder.com/50/6a1b9a/ffffff?text=${avatarPlaceholderText}`;

        block.innerHTML = `
            <div class="professor-header">
                <img src="${avatarSrc}" 
                    alt="Avatar de ${profesor.nombre_instructor}" 
                    class="professor-avatar"
                    onerror="this.onerror=null; this.src='https://via.placeholder.com/50/CCCCCC/888888?text=NO'"
                >
                <div>
                    <h2 class="professor-name">${profesor.nombre_instructor}</h2>
                    <p class="professor-title">${profesorTitulo}</p> 
                </div>
            </div>

            <div class="search-bar-small">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar curso asignado..." onkeyup="filterCourses('${uniqueId}', this.value)">
            </div>

            <div class="courses-table">
                <table id="${uniqueId}">
                    <thead>
                        <tr>
                            <th>NOMBRE DEL CURSO</th>
                            <th>CATEGORÍA</th>
                            <th class="text-right">ESTUDIANTES INSCRITOS</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generateCourseRows(profesor.cursos)}
                    </tbody>
                </table>
            </div>
        `;

        return block;
    }

    function generateCourseRows(cursos) {
        if (!cursos || cursos.length === 0) {
            return '<tr><td colspan="3" style="text-align: center; color: #aaa;">No hay cursos asignados.</td></tr>';
        }
        
        return cursos.map(curso => {
            // CLAVE: Contamos el tamaño del array de alumnos para los inscritos
            const totalInscritos = curso.alumnos_inscritos ? curso.alumnos_inscritos.length : 0;
            
            // CLAVE: Usamos la categoría si viene, sino un valor por defecto
            const categoria = curso.categoria || 'Sin Categoría'; 
            const cursoNombre = curso.nombre_curso; 
            const cursoId = curso.id_curso;

            return `
                <tr>
                    <td><a href="${API_DETALLES_CURSO_URL}${cursoId}">${cursoNombre}</a></td>
                    <td>${categoria}</td>
                    <td class="text-right">${totalInscritos}</td>
                </tr>
            `;
        }).join(''); 
    }
    </script>
</body>
</html>