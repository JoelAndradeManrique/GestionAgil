<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Curso</title>
    <link rel="stylesheet" href="../css/registro_curso.css"> 
    </head>
<body>
    <div class="main-container">
        <div class="course-form-box">
            
            <div class="form-header">
                *AÑADIR NUEVO CURSO*
            </div>

            <form class="form-content" id="formNuevoCurso"> 
                
                <div class="form-group">
                    <label for="nombreCurso">NOMBRE DEL CURSO*</label>
                    <input type="text" id="nombreCurso" name="nombreCurso" placeholder="EJEM.INGLES" >
                </div>
                
                <div class="form-group">
                    <label for="descripcion">DESCRIPCIÓN*</label>
                    <textarea id="descripcion" name="descripcion" rows="4" placeholder="EJEM.CURSO ENFOCADO EN DESARROLLAR HABILIDADES DE LECTURA, ESCRITURA Y CONVERSACIÓN EN INGLÉS." ></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categoria">CATEGORÍA*</label>
                        <input type="text" id="categoria" name="categoria" placeholder="EJEM.IDIOMAS" >
                    </div>
                    <div class="form-group">
                        <label for="cupos">CUPOS DISPONIBLES*</label>
                        <input type="number" id="cupos" name="cupos" placeholder="EJEM.120 DISPONIBLES"  min="1" max="120">
                    </div>
                </div>

                <div class="form-group">
                    <label for="instructor">NOMBRE DEL INSTRUCTOR*</label>
                    <input type="text" id="instructor" name="instructor" placeholder="EJEM.MARIA SANCHEZ">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-agregar">AGREGAR</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formNuevoCurso');
            
            // Lista de todos los campos obligatorios para facilitar el recorrido
            const camposObligatorios = [
                { id: 'nombreCurso', nombre: 'Nombre del Curso' },
                { id: 'descripcion', nombre: 'Descripción' },
                { id: 'categoria', nombre: 'Categoría' },
                { id: 'cupos', nombre: 'Cupos Disponibles' },
                { id: 'instructor', nombre: 'Nombre del Instructor' }
            ];

            form.addEventListener('submit', function(event) {
                // Detiene el envío del formulario inmediatamente
                event.preventDefault(); 
                
                const camposFaltantes = [];
                let primerCampoVacio = null;

                // Itera sobre la lista de campos para validar
                camposObligatorios.forEach(campo => {
                    const inputElement = document.getElementById(campo.id);
                    let valor = inputElement.value.trim();

                    let esInvalido = false;
                    
                    // Lógica específica para el campo de cupos
                    if (campo.id === 'cupos') {
                        // Verifica si está vacío, no es un número, o es menor o igual a 0
                        if (valor === '' || isNaN(valor) || Number(valor) <= 0) {
                            esInvalido = true;
                        }
                    } else if (valor === '') {
                        // Lógica estándar para campos de texto
                        esInvalido = true;
                    }

                    if (esInvalido) {
                        camposFaltantes.push(campo.nombre);
                        // Guarda la referencia al primer campo vacío para enfocarlo más tarde
                        if (!primerCampoVacio) {
                            primerCampoVacio = inputElement;
                        }
                    }
                });

                // Lógica de Alerta y Resultado
                if (camposFaltantes.length > 0) {
                    // Si hay campos faltantes, muestra la alerta
                    const mensajeAlerta = "¡Por favor, completa todos los campos obligatorios (*)! Faltan los siguientes campos:\n\n- " + camposFaltantes.join('\n- ');
                    alert(mensajeAlerta);
                    
                    // Enfoca el primer campo que esté vacío
                    if (primerCampoVacio) {
                        primerCampoVacio.focus();
                    }

                } else {
                    // Todos los campos están llenos. Procede con el envío de datos.
                    alert('✅ ¡Formulario completo! Los datos serán enviados al servidor.');
                    
                    // Aquí es donde harías la llamada a tu API con fetch
                    // Ejemplo: enviarDatosAlAPI(new FormData(form));
                }
            });
        });
    </script>
</body>
</html>