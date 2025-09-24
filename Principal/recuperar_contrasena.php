<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RECUPERAR CONTRASEÑA</title>
  <link rel="stylesheet" href="../Principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    #mensaje a { color: #007bff; text-decoration: underline; }
    /* Puedes añadir los estilos .exito y .error que te di antes */
  </style>
</head>
<body>
  <?php // include("arriba.php"); ?>
  <div class="contenedor2">
    <div class="contenedor3">
      <h1>Recuperar Contraseña</h1>
      <p>Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
      
      <div id="mensaje"></div>
      
      <form id="solicitarForm">
        <div class="form_grupo">
          <label for="email">CORREO ELECTRÓNICO</label><br>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="btn">
          <input type="submit" value="ENVIAR ENLACE">
        </div>
      </form>
    </div>
  </div>

  <script>
  $(document).ready(function() {
    $("#solicitarForm").on("submit", function(event) {
      event.preventDefault();
      $("#mensaje").empty();

      let email = $("#email").val().trim();
      if (email === '') {
        $("#mensaje").text("Por favor, ingresa tu correo.").css('color', 'red');
        return;
      }

      $.ajax({
        url: '../api/solicitarRecuperacion.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ email: email }),
        
        success: function(response) {
          // Para la práctica, mostramos el enlace directamente
          let mensaje = response.mensaje + "<br><br><strong>Enlace simulado:</strong><br>";
          let enlace = $('<a>', {
            href: response.simulacion_email.split(': ')[1], // Extraemos solo la URL
            text: 'Haz clic aquí para restablecer tu contraseña',
            target: '_blank' // Abrir en una nueva pestaña
          });
          $("#mensaje").html(mensaje).append(enlace).css('color', 'green');
        },
        
        error: function() {
          $("#mensaje").text("Ocurrió un error. Intenta de nuevo.").css('color', 'red');
        }
      });
    });
  });
  </script>
</body>
</html>