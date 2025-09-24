<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RESTABLECER CONTRASEÑA</title>
  <link rel="stylesheet" href="../Principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    /* Puedes añadir los estilos .exito y .error que te di antes */
  </style>
</head>
<body>
  <?php // include("arriba.php"); ?>
  <div class="contenedor2">
    <div class="contenedor3">
      <h1>Crea tu nueva contraseña</h1>
      <p>Asegúrate de que sea segura.</p>
      
      <div id="mensaje"></div>
      
      <form id="resetForm">
        <div class="form_grupo">
          <label for="nueva_contrasena">NUEVA CONTRASEÑA</label><br>
          <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
        </div>
        <div class="form_grupo">
          <label for="confirmar_contrasena">CONFIRMAR CONTRASEÑA</label><br>
          <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
        </div>
        <div class="btn">
          <input type="submit" value="GUARDAR CONTRASEÑA">
        </div>
      </form>
    </div>
  </div>

  <script>
  $(document).ready(function() {
    // 1. Extraer el token de la URL al cargar la página
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');

    if (!token) {
      $("#mensaje").text("Token no encontrado. Asegúrate de usar el enlace correcto.").css('color', 'red');
      $("form").hide(); // Ocultar el formulario si no hay token
    }

    // 2. Manejar el envío del formulario
    $("#resetForm").on("submit", function(event) {
      event.preventDefault();
      $("#mensaje").empty();

      let nueva = $("#nueva_contrasena").val();
      let confirmar = $("#confirmar_contrasena").val();

      if (nueva === '' || confirmar === '') {
        $("#mensaje").text("Por favor, completa ambos campos.").css('color', 'red');
        return;
      }
      if (nueva !== confirmar) {
        $("#mensaje").text("Las contraseñas no coinciden.").css('color', 'red');
        return;
      }

      $.ajax({
        url: '../api/resetearContrasena.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          token: token, // El token que extrajimos de la URL
          nueva_contrasena: nueva
        }),
        
        success: function(response) {
          $("#mensaje").text(response.mensaje + " Serás redirigido al login.").css('color', 'green');
          setTimeout(() => window.location.href = 'iniciar_sesion.php', 3000);
        },
        
        error: function(jqXHR) {
          let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Error desconocido.";
          $("#mensaje").text(errorMsg).css('color', 'red');
        }
      });
    });
  });
  </script>
</body>
</html>