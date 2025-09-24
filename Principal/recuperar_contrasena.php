<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RECUPERAR CONTRASEÑA</title>
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
  <!-- panel principal -->
  <div class="contenedor2">
    <!-- menu principal -->
  <div class="contenedor3">
    <h1>¿Has olvidado la <br><br><br>contraseña?</h1>
    <br><br><br>
      <!-- actualizar usuario -->
      <form action="#" method="POST"> <!--form para verificar datos en la base-->
        <!-- verificar email -->
         <div class="form_grupo">
         <label for="email">CORREO <br> ELECTRÓNICO</label>
         <br>
         <input type="email" id="email" name="email">
         </div>
         <!-- verificar contrasena -->
          <div class="form_grupo">
          <label for="contrasena_hash">NUEVA <br>CONTRASEÑA</label>
          <br>
          <input type="password" id="contrasena_hash" name="contrasena_hash">
          </div>
          <!-- registro para verificar usuario-->
           <div class="btn">
          <input type="submit" id="btn_actualizar" value="Ingresar">
          </div>
      </form>
  </div>
  <br><br><br><br><br>
  </div>
</body>
</html>