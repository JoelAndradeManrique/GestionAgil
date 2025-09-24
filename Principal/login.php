<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>REGISTRARSE</title>
  <link rel="stylesheet" href="../Principal/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    #mensaje { margin-top: 15px; padding: 10px; border-radius: 5px; font-weight: bold; display: none; }
    #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
    #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
    /* Estilo opcional para que el select se parezca a los inputs */
    .mi-select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
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
          <label for="nombre">NOMBRE</label><br>
          <input type="text" id="nombre" name="nombre">
        </div>
        <div class="form_grupo">
          <label for="email">CORREO ELECTRÓNICO</label><br>
          <input type="email" id="email" name="email">
        </div>
        <div class="form_grupo">
          <label for="contrasena">CONTRASEÑA</label><br>
          <input type="password" id="contrasena" name="contrasena">
        </div>
        <div class="form_grupo">
          <label for="rol">MI ROL ES:</label><br>
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
    $("#registroForm").on("submit", function(event) {
      event.preventDefault();
      $("#mensaje").empty().removeClass("error exito");
      let nombre = $("#nombre").val().trim();
      let email = $("#email").val().trim();
      let contrasena = $("#contrasena").val().trim();
      let rol = $("#rol").val(); // Se obtiene el rol seleccionado
      if (nombre === '' || email === '' || contrasena === '') {
        $("#mensaje").text("Por favor, completa todos los campos.").addClass("error");
        return;
      }
      $.ajax({
        url: '../api/registrar.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          nombre: nombre,
          email: email,
          contrasena: contrasena,
          rol: rol // Se envía el rol a la API
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