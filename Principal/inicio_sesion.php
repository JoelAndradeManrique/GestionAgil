<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INICIAR SESION</title>
   <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <!-- include parte inferior del banner -->
  <?php
  include("arriba.php")
  ?>
  <!-- panel principal inicio de sesion -->
  <div class="contenedor2">
    <!-- menu principal inicio de sesion -->
  <div class="contenedor3">
    <h1>HOLA</h1>
    <p>Incia sesión para continuar.</p>
      <!-- registro del usuario -->
      <form action="#" method="POST"> <!--form para verificar datos en la base-->
        <!-- verificar email -->
         <div class="form_grupo">
         <label for="email">CORREO <br> ELECTRÓNICO</label>
         <br>
         <input type="email" id="email" name="email">
         </div>
         <!-- verificar contrasena -->
          <div class="form_grupo">
          <label for="contrasena_hash">CONTRASEÑA</label>
          <br>
          <input type="password" id="contrasena_hash" name="contrasena_hash">
          </div>
          <!-- registro para verificar usuario-->
           <div class="registrar_btn">
          <input type="submit" id="btn_ingresar" value="Ingresar">
          </div>
      </form>
  </div>
  <br><br><br><br><br>
  </div>
</body>
</html>