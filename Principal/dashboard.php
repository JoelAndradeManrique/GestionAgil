<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BIENVENIDO</title>
  <link rel="stylesheet" href="../Principal/estilos.css">
  <link rel="stylesheet" href="../css/estilos.css"> <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
  <?php
    // include("arriba.php");
  ?>

  <div class="contenedor2">
    <div class="contenedor3" style="text-align: center;">
      <h1 id="mensaje-bienvenida"></h1>
      <p>Has iniciado sesión correctamente.</p>
      <br>
      <div class="btn">
        <a href="#" id="btn-cerrar-sesion" style="text-decoration: none; color: white; padding: 10px 20px; background-color: #dc3545; border-radius: 5px;">Cerrar Sesión</a>
      </div>
    </div>
  </div>

<script>
$(document).ready(function() {
  // --- LÓGICA DE LA PÁGINA ---

  // 1. Revisar si hay datos de usuario guardados en el navegador
  const datosUsuarioString = localStorage.getItem('usuario');

  // 2. Si NO hay datos, el usuario no ha iniciado sesión. Lo redirigimos.
  if (!datosUsuarioString) {
    // Redirigir a la página de login si no hay sesión
    window.location.href = 'login.php'; 
  } else {
    // 3. Si SÍ hay datos, los convertimos de texto a objeto
    const usuario = JSON.parse(datosUsuarioString);

    // 4. Creamos el mensaje de bienvenida usando el rol y el nombre
    let mensaje = "";
    switch (usuario.rol) {
      case 'alumno':
        mensaje = "Bienvenido Alumno, " + usuario.nombre;
        break;
      case 'instructor':
        mensaje = "Bienvenido Maestro, " + usuario.nombre;
        break;
      case 'admin':
        mensaje = "Bienvenido Administrador, " + usuario.nombre;
        break;
      default:
        mensaje = "Bienvenido, " + usuario.nombre;
    }

    // 5. Mostramos el mensaje en el H1
    $("#mensaje-bienvenida").text(mensaje);
  }

  // 6. Lógica para el botón de "Cerrar Sesión"
  $("#btn-cerrar-sesion").on("click", function(event) {
    event.preventDefault(); // Evita que el enlace haga algo raro
    
    // Borramos los datos del usuario del almacenamiento local
    localStorage.removeItem('usuario');
    
    // Informamos al usuario y lo redirigimos al login
    alert("Has cerrado la sesión.");
    window.location.href = 'inicio_sesion.php';
  });

});
</script>

</body>
</html>