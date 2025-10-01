<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RECUPERAR CONTRASEÑA</title>
  <link rel="stylesheet" href="../principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    /* Estilos para los mensajes */
    #mensaje { margin-top: 15px; padding: 10px; border-radius: 8px; font-weight: bold; display: none; text-align: center; }
    #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
    #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
    #mensaje a { color: #0d6efd; font-weight: bold; text-decoration: underline; }
  </style>
</head>
<body>
    <main class="form-container">
        <div class="form-box">
            <h1>Recuperar Contraseña</h1>
            <p>Ingresa tu correo electrónico y te mostraremos un enlace para restablecer tu contraseña.</p>
            
            <div id="mensaje"></div>
            
            <form id="solicitarForm">
                <div class="form_grupo">
                    <label for="email">CORREO ELECTRÓNICO</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="btn">
                    <input type="submit" value="ENVIAR ENLACE">
                </div>
            </form>
            <p style="font-size: 0.9rem; margin-top: 1rem;"><a href="inicio_sesion.php">Volver a Iniciar Sesión</a></p>
        </div>
    </main>

  <script>
  $(document).ready(function() {
    $("#solicitarForm").on("submit", function(event) {
      event.preventDefault();
      $("#mensaje").empty().removeClass("error exito");

      let email = $("#email").val().trim();
      if (email === '') {
        $("#mensaje").text("Por favor, ingresa tu correo.").addClass("error");
        return;
      }

      $.ajax({
        url: '../api/solicitarRecuperacion.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ email: email }),
        
        success: function(response) {
          // Mostramos el enlace simulado directamente en pantalla
          let mensaje = response.mensaje + "<br><br><strong>Enlace simulado:</strong><br>";
          let enlace = $('<a>', {
            // Extraemos solo la URL de la respuesta
            href: response.simulacion_email.split(': ')[1],
            text: 'Haz clic aquí para restablecer tu contraseña',
            target: '_blank'
          });
          $("#mensaje").html(mensaje).append(enlace).addClass("exito");
        },
        
        error: function(jqXHR) {
          let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Ocurrió un error. Intenta de nuevo.";
          $("#mensaje").text(errorMsg).addClass("error");
        }
      });
    });
  });
  </script>
</body>
</html>