<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INICIAR SESION</title>
  <link rel="stylesheet" href="../principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    /* Estilos para el enlace de olvido de contraseña */
    .enlace-olvido {
      display: block;
      text-align: right;
      margin-top: -1rem; /* Sube un poco el enlace */
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
    <main class="form-container">
        <div class="form-box">
            <h1>HOLA</h1>
            <p>Inicia sesión para continuar</p>
            <div id="mensaje"></div>

            <form id="loginForm">
                <div class="form_grupo">
                    <label for="email">CORREO ELECTRÓNICO</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form_grupo">
                    <label for="contrasena">CONTRASEÑA</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <a href="recuperar_contrasena.php" class="enlace-olvido">¿Olvidaste tu contraseña?</a>
                
                <div class="btn">
                    <input type="submit" value="Ingresar">
                </div>
            </form>
            <p style="font-size: 1rem; margin-top: 1.5rem;">¿No tienes cuenta? <a href="login.php">Regístrate aquí</a>.</p>
        </div>
    </main>

  <script>
    $(document).ready(function() {
      $("#loginForm").on("submit", function(event) {
        event.preventDefault();
        $("#mensaje").empty().removeClass("error exito");
        let email = $("#email").val().trim();
        let contrasena = $("#contrasena").val().trim();
        if (email === '' || contrasena === '') {
          $("#mensaje").text("Por favor, completa todos los campos.").addClass("error");
          return;
        }
        $.ajax({
          url: '../api/login.php',
          method: 'POST',
          contentType: 'application/json',
          data: JSON.stringify({ email: email, contrasena: contrasena }),
          success: function(response) {
            $("#mensaje").text("¡Bienvenido, " + response.datos.nombre + "! Redirigiendo...").addClass("exito");
            localStorage.setItem('usuario', JSON.stringify(response.datos));
            setTimeout(function() { window.location.href = 'dashboard.php'; }, 2000);
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