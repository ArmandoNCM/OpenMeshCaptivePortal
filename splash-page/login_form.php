<?php
require_once(dirname(__FILE__) . '/../constants.php');
$html_form_process_url = '/splash-page/form_process.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/ExtremeNetworksCaptivePortal/splash-page/assets/css/styles.css">
	<title>Conexión Audi</title>
</head>
<body>
	<div id="header">
		<img src="/ExtremeNetworksCaptivePortal/splash-page/assets/images/logo-audi.png">
		<p style="color: white; position: relative; bottom: -50%; text-align: center;">Bienvenido a la nueva era de Audi.</p>
	</div>
	<div id="information">
		<span>Red Wi-Fi gratuita</span>
		<span>Completa el formulario y recibe contenido</span>
		<span>exclusivo de nuestras marcas</span>
	</div>

	<div id="form-container">
		<form action="<?php echo $html_form_process_url ?>" method="post">
			<div class="form-group">
				<span>Primer Nombre</span>
				<input name="first_name" type="text" class="form-control" required>
			</div>
			<div class="form-group">
				<span>Apellido</span>
				<input name="last_name" type="text" class="form-control" required>
			</div>
			<div class="form-group">
				<span>Correo electrónico</span>
				<input name="email" type="email" class="form-control" required>
			</div>
			<div class="form-group">
				<span>Teléfono</span>
				<input  name="phone" type="tel" class="form-control" required>
			</div>
			<div class="form-group">
				<span>Ciudad</span>
				<select name="city" name="" id="" class="select-cities">
					<option value="Bogotá">Bogotá</option>
					<option value="Medellín">Medellín</option>
				</select>
			</div>

			<?php
			foreach ($hidden_fields_array as $key => $value) {
				echo "<input type='hidden' name='$key' id='hfv-$key' value='$value' />";
			}
			?>

			<input type="submit" value="Conectarme" class="btn btn-red">
		</form>
	</div>
    <p id="terms">Al registrarte, aceptas nuestros <a href="#tos">Términos y condiciones</a></p>
    
    <p id="tos">

        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ornare orci et purus dignissim finibus. Mauris facilisis eros quam, quis laoreet nibh porta eu. Nullam lacinia mattis sollicitudin. Ut lobortis tellus ultricies iaculis suscipit. Sed nec scelerisque turpis, vitae mollis neque. Sed dictum mauris quis quam congue ullamcorper. Aliquam vitae vehicula dui. Quisque nec molestie dui, nec rhoncus purus.
        <br>
        <br>
        Vestibulum sit amet volutpat odio. Vivamus vestibulum lectus fermentum, scelerisque nulla ut, volutpat ante. Nunc accumsan finibus orci at auctor. Aliquam erat volutpat. Aenean in metus eget lectus vulputate maximus id nec velit. Praesent faucibus nisl sit amet eros suscipit tempor. Duis molestie scelerisque augue nec faucibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec hendrerit augue placerat odio commodo, nec dictum orci fermentum. Sed id leo pharetra, volutpat nunc eu, auctor ligula.
        <br>
        <br>
        Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In diam libero, scelerisque placerat vulputate in, mollis eget nisi. Nunc laoreet ante est, et porta sapien volutpat ut. Nunc eu purus maximus, finibus eros sed, dignissim erat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque eget finibus elit. Ut in dolor sit amet lectus consequat faucibus vitae at elit. Donec laoreet ante vitae augue imperdiet fermentum. Etiam consequat lorem a nisi tincidunt, sed vehicula enim tempus.
    </p>
</body>
</html>