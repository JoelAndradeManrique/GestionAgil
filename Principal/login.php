<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>REGISTRARSE</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/estilos.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
  <!-- include parte inferior del banner -->
  <?php
  include("arriba.php")
  ?>
  <!-- panel principal login -->
  <div class="contenedor2">
    <!-- menu principal login -->
  <div class="contenedor3">
    <h1>Crear una cuenta nueva</h1>
    <p>¿Ya estás registrado? Inicia sesión
     <br><br> aquí.</p>
      <!-- registro del usuario -->
      <form action="si.php" method="POST"> <!--form para enviar datos a la base-->
        <!-- registro nombre -->
        <div class="form_grupo">
        <label for="nombre">NOMBRE</label>
        <br>
        <input type="text" id="nombre" name="nombre">
        </div>
        <!-- registro correo -->
         <div class="form_grupo">
         <label for="email">CORREO <br> ELECTRÓNICO</label>
         <br>
         <input type="email" id="email" name="email">
         </div>
         <!-- registro contrasena -->
          <div class="form_grupo">
          <label for="contrasena_hash">CONTRASEÑA</label>
          <br>
          <input type="password" id="contrasena_hash" name="contrasena_hash">
          </div>
          <!-- registro para envio -->
           <div class="btn">
          <input type="submit" id="btn_validar" value="REGISTRARME">
          </div>
      </form>
  </div>
  <br><br><br><br><br>
  </div>

  <!--validacion-->
  <script>
    $("#btn_validar").click(function (event) {

      //vallidacion del nombre
      if ($("#nombre").val().trim() == '') {
        alert("ESCRIBE tu NOMBRE");
        $("#nombre").focus();
        event.preventDefault();
        return 0;
      }

      // Validación del email
     if ($("#email").val().trim() == '') {
        alert("ESCRIBE tu EMAIL");
        $("#email").focus();
        event.preventDefault();
        return 0;
      }

      // Validación de la contraseña
      if ($("#contrasena_hash").val().trim() == '') {
        alert("ESCRIBE tu CONTRASEÑA");
        $("#contrasena_hash").focus();
        event.preventDefault();
        return 0;
      }
    });
  </script>
</body>
</html>