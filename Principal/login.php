<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRARSE</title>
    <link rel="stylesheet" href="../Principal/estilos.css"> <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        #mensaje { margin-top: 15px; padding: 10px; border-radius: 5px; font-weight: bold; display: none; }
        #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
        #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
        
        /* ✅ AÑADIDO: Estilos para las nuevas funcionalidades */
        .required { color: #ef4444; margin-left: 4px; }
        .password-wrapper { position: relative; display: flex; align-items: center; }
        .password-wrapper input { padding-right: 40px !important; /* Espacio para el icono */ }
        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #6b7280; }
    </style>
</head>
<body>
    <div class="contenedor2">
        <div class="contenedor3">
            <h1>Crear una cuenta nueva</h1>
            <p>¿Ya estás registrado? <a href="inicio_sesion.php">Inicia sesión aquí</a>.</p>
            <div id="mensaje"></div>
            <form id="registroForm">
                <div class="form_grupo">
                    <label for="nombre">NOMBRE<span class="required">*</span></label><br>
                    <input type="text" id="nombre" name="nombre">
                </div>
                <div class="form_grupo">
                    <label for="email">CORREO ELECTRÓNICO<span class="required">*</span></label><br>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form_grupo">
                    <label for="contrasena">CONTRASEÑA<span class="required">*</span></label><br>
                    <div class="password-wrapper">
                        <input type="password" id="contrasena" name="contrasena">
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form_grupo">
                    <label for="rol">MI ROL ES:<span class="required">*</span></label><br>
                    <select id="rol" name="rol" class="mi-select">
                        <option value="alumno" selected>Alumno</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>
                <div class="btn">
                    <input type="submit" value="REGISTRARME">
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // ✅ AÑADIDO: Lógica para mostrar/ocultar contraseña
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            let input = $(this).prev("input");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $("#registroForm").on("submit", function(event) {
            event.preventDefault();
            $("#mensaje").empty().removeClass("error exito");
            
            let nombre = $("#nombre").val().trim();
            let email = $("#email").val().trim();
            let contrasena = $("#contrasena").val(); // No se usa trim() en contraseñas
            let rol = $("#rol").val();

            // ✅ AÑADIDO: Bloque de validaciones mejorado
            if (nombre === '' || email === '' || contrasena === '') {
                $("#mensaje").text("Por favor, completa todos los campos obligatorios (*).").addClass("error");
                return;
            }
            const dominiosPermitidos = ["@gmail.com", "@outlook.com", "@tecnm.mx"];
            if (!dominiosPermitidos.some(dominio => email.endsWith(dominio))) {
                $("#mensaje").text("El correo debe ser de dominio gmail.com, outlook.com o tecnm.mx.").addClass("error");
                return;
            }
            const tieneNumero = /\d/.test(contrasena);
            if (contrasena.length < 8 || !tieneNumero) {
                $("#mensaje").text("La contraseña debe tener al menos 8 caracteres y contener al menos un número.").addClass("error");
                return;
            }
            // FIN DE VALIDACIONES

            $.ajax({
                url: '../api/registrar.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    nombre: nombre,
                    email: email,
                    contrasena: contrasena,
                    rol: rol
                }),
                success: function(response) {
                    $("#mensaje").text(response.mensaje + " Serás redirigido para iniciar sesión.").addClass("exito");
                    $("#registroForm")[0].reset();
                    setTimeout(function() { window.location.href = 'inicio_sesion.php'; }, 3000);
                },
                error: function(jqXHR) {
                    let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Error desconocido.";
                    $("#mensaje").text(errorMsg).addClass("error");
                }
            });
        });
    });
    </script>
</body>
</html>