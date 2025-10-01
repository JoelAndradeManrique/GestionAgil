<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESTABLECER CONTRASEÑA</title>
    <link rel="stylesheet" href="../principal/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ✅ AÑADIDO: Estilos para el ojo y el asterisco */
        .required { color: #ef4444; margin-left: 4px; }
        .password-wrapper { position: relative; display: flex; align-items: center; }
        .password-wrapper input { padding-right: 40px !important; }
        .toggle-password { position: absolute; right: 15px; cursor: pointer; color: #6b7280; }
    </style>
</head>
<body>
    <main class="form-container">
        <div class="form-box">
            <h1>Crea tu nueva contraseña</h1>
            <p>Asegúrate de que sea segura y que la recuerdes.</p>
            
            <div id="mensaje"></div>
            
            <form id="resetForm">
                <div class="form_grupo">
                    <label for="nueva_contrasena">NUEVA CONTRASEÑA<span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form_grupo">
                    <label for="confirmar_contrasena">CONFIRMAR CONTRASEÑA<span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
                        <i class="fa-solid fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="btn">
                    <input type="submit" value="GUARDAR CONTRASEÑA">
                </div>
            </form>
        </div>
    </main>

    <script>
    $(document).ready(function() {
        // ✅ AÑADIDO: Lógica para mostrar/ocultar contraseña
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            let input = $(this).closest(".password-wrapper").find("input");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        // --- El resto de tu script se queda igual ---

        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');

        if (!token) {
            $("#mensaje").text("Token no encontrado o inválido.").addClass("error");
            $("form").hide();
        }

        $("#resetForm").on("submit", function(event) {
            event.preventDefault();
            $("#mensaje").empty().removeClass("error exito");

            let nueva = $("#nueva_contrasena").val();
            let confirmar = $("#confirmar_contrasena").val();

            if (nueva === '' || confirmar === '') {
                $("#mensaje").text("Por favor, completa ambos campos.").addClass("error");
                return;
            }
            if (nueva !== confirmar) {
                $("#mensaje").text("Las contraseñas no coinciden.").addClass("error");
                return;
            }
            
            const tieneNumero = /\d/.test(nueva);
            const tieneMayuscula = /[A-Z]/.test(nueva);
            if (nueva.length < 8 || !tieneNumero || !tieneMayuscula) {
                $("#mensaje").text("La contraseña debe tener al menos 8 caracteres, un número y una letra mayúscula.").addClass("error");
                return;
            }

            $.ajax({
                url: '../api/resetearContrasena.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    token: token,
                    nueva_contrasena: nueva
                }),
                
                success: function(response) {
                    $(".form_grupo, .btn").hide();
                    $("#mensaje").text(response.mensaje + " Ahora serás redirigido para iniciar sesión.").addClass("exito");
                    setTimeout(() => window.location.href = 'inicio_sesion.php', 3000);
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