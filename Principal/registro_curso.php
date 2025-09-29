<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Curso</title>
    <link rel="stylesheet" href="styles/styles.css"> 
</head>
<body>
    <div class="container container-form">
        
        <header class="header header-form">
            <span class="form-title">*AÑADIR NUEVO CURSO*</span>
        </header>

        <main class="content-box form-box">
            <form action="#" method="POST" class="course-form">
                
                <label for="nombre-curso" class="form-label">NOMBRE DEL CURSO*</label>
                <input type="text" id="nombre-curso" name="nombre-curso" placeholder="EJEM.INGLES" required>

                <label for="descripcion" class="form-label">DESCRIPCIÓN*</label>
                <textarea id="descripcion" name="descripcion" rows="4" placeholder="EJEM.CURSO ENFOCADO EN DESARROLLAR HABILIDADES DE LECTURA, ESCRITURA Y CONVERSACIÓN EN INGLÉS." required></textarea>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria" class="form-label">CATEGORÍA*</label>
                        <input type="text" id="categoria" name="categoria" placeholder="EJEM.IDIOMAS" required>
                    </div>

                    <div class="form-group">
                        <label for="cupos" class="form-label">CUPOS DISPONIBLES*</label>
                        <input type="number" id="cupos" name="cupos" placeholder="EJEM.120 DISPONIBLES" required>
                    </div>
                </div>

                <label for="instructor" class="form-label">NOMBRE DEL INSTRUCTOR*</label>
                <input type="text" id="instructor" name="instructor" placeholder="EJEM.MARIA SANCHEZ" required>

                <button type="submit" class="btn btn-primary btn-full-width">AGREGAR</button>
            </form>
        </main>
    </div>
</body>
</html>