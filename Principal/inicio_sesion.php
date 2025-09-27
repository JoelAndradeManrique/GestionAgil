<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INICIAR SESION</title>
  <link rel="stylesheet" href="../Principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    #mensaje { margin-top: 15px; padding: 10px; border-radius: 5px; font-weight: bold; display: none; }
    #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
    #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
  </style>
</head>
<body>
  <div class="contenedor2">
    <div class="contenedor3">
      <h1>HOLA</h1>
      <p>Inicia sesión para continuar</p>
      <div id="mensaje"></div>
      <form id="loginForm">
        <div class="form_grupo">
          <label for="email">CORREO ELECTRÓNICO</label><br>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form_grupo">
          <label for="contrasena">CONTRASEÑA</label><br>
          <input type="password" id="contrasena" name="contrasena" required>
          
          <a href="recuperar_contrasena.php" class="enlace-olvido">¿Olvidaste tu contraseña?</a>

        </div>
        
        <div class="btn">
          <input type="submit" value="Ingresar">
        </div>
      </form>
      <p style="margin-top: 15px;">¿No tienes cuenta? <a href="login.php">Regístrate aquí</a>.</p>
    </div>
  </div>

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
          url: '../api/login.php', // Llama a la API de login
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