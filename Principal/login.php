<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRARSE</title>
    <link rel="stylesheet" href="../principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Estos estilos ya están en tu CSS principal, pero los dejo por si acaso */
        .required { color: #ef4444; margin-left: 4px; }
        .password-wrapper { position: relative; display: flex; align-items: center; }
        .password-wrapper input { padding-right: 40px !important; }
        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #6b7280; }
    </style>
</head>
<body>
    <main class="form-container">
        <div class="form-box">
            <h1>Crear una cuenta nueva</h1>
            <p>¿Ya estás registrado? <a href="inicio_sesion.php">Inicia sesión aquí</a>.</p>
            <div id="mensaje"></div>
            
            <form id="registroForm">
                <div class="form_grupo">
                    <label for="nombre">NOMBRE<span class="required">*</span></label>
                    <input type="text" id="nombre" name="nombre">
                </div>
                <div class="form_grupo">
                    <label for="email">CORREO ELECTRÓNICO<span class="required">*</span></label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form_grupo">
                    <label for="contrasena">CONTRASEÑA<span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="contrasena" name="contrasena">
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form_grupo">
                    <label for="rol">MI ROL ES:<span class="required">*</span></label>
                    <select id="rol" name="rol">
                        <option value="alumno" selected>Alumno</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>
                <div class="btn">
                    <input type="submit" value="REGISTRARME">
                </div>
            </form>
        </div>
    </main>

<script>
$(document).ready(function() {
    // LÓGICA PARA MOSTRAR/OCULTAR CONTRASEÑA
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        let input = $(this).prev("input");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    // LÓGICA PARA ENVIAR EL FORMULARIO
    $("#registroForm").on("submit", function(event) {
        event.preventDefault();
        $("#mensaje").empty().removeClass("error exito");
        
        let nombre = $("#nombre").val().trim();
        let email = $("#email").val().trim();
        let contrasena = $("#contrasena").val();
        let rol = $("#rol").val();

        // VALIDACIONES
        if (nombre === '' || email === '' || contrasena === '') {
            $("#mensaje").text("Por favor, completa todos los campos obligatorios (*).").addClass("error");
            return;
        }
        const dominiosPermitidos = ["@gmail.com", "@outlook.com", "@tecnm.mx"];
        if (!dominiosPermitidos.some(dominio => email.endsWith(dominio))) {
            $("#mensaje").text("El correo debe ser de dominio gmail.com, outlook.com o tecnm.mx.").addClass("error");
            return;
        }

        // ✅ INICIA EL CAMBIO AQUÍ
        const tieneNumero = /\d/.test(contrasena);
        const tieneMayuscula = /[A-Z]/.test(contrasena); // Nueva comprobación

        if (contrasena.length < 8 || !tieneNumero || !tieneMayuscula) {
            // Mensaje de error actualizado
            $("#mensaje").text("La contraseña debe tener al menos 8 caracteres, un número y una letra mayúscula.").addClass("error");
            return;
        }
        // ✅ FIN DEL CAMBIO

        // Si todo es correcto, se envía a la API
        let datos = { nombre, email, contrasena, rol };

        $.ajax({
            url: '../api/registrar.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(datos),
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