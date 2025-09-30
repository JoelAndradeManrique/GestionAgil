<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pago</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; margin: 0; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-bottom: 1px solid #e5e7eb; }
        .search-bar { flex-grow: 1; margin: 0 2rem; }
        .search-bar input { width: 100%; max-width: 400px; padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 20px; }
        .nav-links { margin-right: 2rem; }
        .nav-links a { margin-left: 1.5rem; text-decoration: none; color: #333; font-weight: 700; }
        .user-profile { display: flex; align-items: center; }
        .user-initials { width: 40px; height: 40px; border-radius: 50%; background-color: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-weight: 700; margin-left: 1rem; cursor: pointer; }
        .pago-container { display: flex; justify-content: center; align-items: flex-start; gap: 2rem; padding: 3rem 2rem; }
        .pago-box { width: 100%; max-width: 700px; background-color: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); }
        .pago-box h1 { font-size: 1.8rem; font-weight: 700; color: #333; margin-bottom: 2.5rem; text-align: left; }
        .pago-wrapper { display: flex; align-items: center; gap: 2rem; }
        .formulario-pago { flex: 1; }
        .campos-flex { display: flex; gap: 1.5rem; }
        .tarjeta-visual { flex-basis: 250px; height: 160px; background: linear-gradient(45deg, #1e3a8a, #2563eb); border-radius: 15px; padding: 1rem; color: white; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3); }
        .tarjeta-visual .chip { width: 40px; height: 30px; background-color: #cbced2; border-radius: 5px; }
        .tarjeta-visual .numero-tarjeta { font-family: monospace; font-size: 1.1rem; letter-spacing: 2px; margin: auto 0; }
        .tarjeta-visual .tarjeta-footer { display: flex; justify-content: space-between; font-size: 0.8rem; text-transform: uppercase; opacity: 0.8; }
        .resumen-box { width: 100%; max-width: 350px; background-color: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); }
        .resumen-box h2 { font-size: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .resumen-box .detalle { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 1rem; }
        .resumen-box .total { display: flex; justify-content: space-between; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; font-size: 1.2rem; font-weight: 700; }
        #mensaje { margin-top: 15px; padding: 10px; border-radius: 8px; font-weight: bold; display: none; text-align: center; }
        #mensaje.exito { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; display: block; }
        #mensaje.error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; display: block; }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo"><a href="dashboard.php" style="text-decoration:none; color: #1e3a8a;">GestionAgil</a></div>
        <div class="search-bar">
            <form id="searchForm">
                <input type="text" id="searchInput" placeholder="&#128269; Buscar curso...">
            </form>
        </div>
        <nav class="nav-links"></nav>
        <div class="user-profile">
            <span id="user-name"></span>
            <div id="user-initials" class="user-initials"></div>
        </div>
    </header>

    <main class="pago-container">
        <div class="pago-box">
            <h1>Asociar una nueva tarjeta de crédito</h1>
            <div id="mensaje"></div>
            <div id="pago-content">
                <div class="pago-wrapper">
                    <div class="formulario-pago">
                        <form id="pagoForm">
                            <div class="form_grupo">
                                <label for="numero_tarjeta">Número</label>
                                <input type="text" id="numero_tarjeta" required inputmode="numeric" placeholder="0000 0000 0000 0000">
                            </div>
                            <div class="form_grupo">
                                <label for="nombre_tarjeta">Nombre y apellido</label>
                                <input type="text" id="nombre_tarjeta" placeholder="Tal como aparece en la tarjeta" required>
                            </div>
                            <div class="campos-flex">
                                <div class="form_grupo" style="flex: 1;">
                                    <label for="fecha_vencimiento">Vencimiento</label>
                                    <input type="text" id="fecha_vencimiento" placeholder="MM / AA" required>
                                </div>
                                <div class="form_grupo" style="flex: 1;">
                                    <label for="cvv">Código de seguridad</label>
                                    <input type="password" id="cvv" required maxlength="3" inputmode="numeric" placeholder="···">
                                </div>
                            </div>
                            <div class="btn">
                                <input type="submit" id="btn-pagar" value="PAGAR AHORA">
                            </div>
                        </form>
                    </div>
                    <div class="tarjeta-visual">
                        <div class="chip"></div>
                        <div class="numero-tarjeta">**** **** **** ****</div>
                        <div class="tarjeta-footer">
                            <span>MM/AA</span>
                            <span>NOMBRE APELLIDO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="resumen-box">
            <h2>Detalles de la compra</h2>
            <div id="resumen-curso">
                <div class="detalle">
                    <span>Curso:</span>
                    <strong id="resumen-titulo">Cargando...</strong>
                </div>
                <div class="detalle">
                    <span>Instructor:</span>
                    <strong id="resumen-instructor">Cargando...</strong>
                </div>
                <div class="total">
                    <span>Total:</span>
                    <strong id="resumen-precio">Cargando...</strong>
                </div>
            </div>
        </div>
    </main>

<script>
$(document).ready(function() {
    // --- LÓGICA DE LA PÁGINA ---
    const datosUsuario = JSON.parse(localStorage.getItem('usuario'));
    const urlParams = new URLSearchParams(window.location.search);
    const idCurso = urlParams.get('id_curso');
    
    if (!datosUsuario || !idCurso) {
        alert("Error: Sesión o curso no encontrado. Serás redirigido.");
        window.location.href = 'dashboard.php';
        return;
    }

    // Lógica de la cabecera
    $("#user-name").text(datosUsuario.nombre);
    const iniciales = datosUsuario.nombre.split(' ').map(n => n[0]).join('');
    $("#user-initials").text(iniciales);
    if(datosUsuario.rol === 'instructor' || datosUsuario.rol === 'admin') { $(".nav-links").append('<a href="#">MIS CURSOS</a>'); } else { $(".nav-links").append('<a href="#">MIS INSCRIPCIONES</a>'); }
    $("#user-initials").on("click", function() { if (confirm("¿Deseas cerrar la sesión?")) { localStorage.removeItem('usuario'); window.location.href = 'inicio_sesion.php'; } });
    $("#searchForm").on("submit", function(event) { event.preventDefault(); const t = $("#searchInput").val(); if (t.trim() !== '') { window.location.href = `dashboard.php?q=${t}`; } });

    // Aplicar máscaras a los inputs
    const cardMask = IMask(document.getElementById('numero_tarjeta'), { mask: '0000 0000 0000 0000' });
    const dateMask = IMask(document.getElementById('fecha_vencimiento'), { mask: 'MM{/}YY', blocks: { MM: { mask: IMask.MaskedRange, from: 1, to: 12 }, YY: { mask: IMask.MaskedRange, from: 25, to: 99 } } });
    const cvvMask = IMask(document.getElementById('cvv'), { mask: '000' });

    // Cargar resumen del curso
    $.ajax({
        url: `../api/obtenerCurso.php?id=${idCurso}`,
        method: 'GET',
        success: function(response) {
            const curso = response.datos;
            $("#btn-pagar").val(`PAGAR AHORA ($${curso.precio} MXN)`);
            $("#resumen-titulo").text(curso.titulo);
            $("#resumen-instructor").text(curso.nombre_instructor);
            $("#resumen-precio").text(`$${curso.precio} MXN`);
        }
    });

    // Lógica del formulario de pago
    $("#pagoForm").on("submit", function(event) {
        event.preventDefault();
        $("#mensaje").empty().removeClass("error exito");

        let numero_tarjeta = cardMask.unmaskedValue;
        let fecha_vencimiento = dateMask.value;
        let cvv = cvvMask.unmaskedValue;

        if (numero_tarjeta.length < 16 || fecha_vencimiento.length < 5 || cvv.length < 3) {
            $("#mensaje").text("Por favor, completa todos los datos de pago.").addClass("error");
            return;
        }

        if (confirm("¿Estás seguro de que quieres realizar este pago?")) {
            $.ajax({
                url: '../api/inscribirCurso.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    id_usuario: datosUsuario.id_usuario,
                    id_curso: idCurso,
                    numero_tarjeta: numero_tarjeta,
                    fecha_vencimiento: fecha_vencimiento,
                    cvv: cvv
                }),
                success: function(response) {
                    $("#pago-content").hide();
                    $("#resumen-curso").hide();
                    let msg = $("<p>").text(response.mensaje + " ¡Felicidades!");
                    let link = $('<a>', { href: '../' + response.url_voucher, text: 'Descargar Voucher en PDF', target: '_blank' });
                    $("#mensaje").empty().append(msg).append("<br><br>").append(link).addClass("exito");
                    const downloadLink = document.createElement('a');
                    downloadLink.href = '../' + response.url_voucher;
                    downloadLink.download = response.url_voucher.split('/').pop();
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                },
                error: function(jqXHR) {
                    let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON.mensaje : "Ocurrió un error inesperado.";
                    $("#mensaje").text(errorMsg).addClass("error");
                }
            });
        }
    });
});
</script>
</body>
</html>