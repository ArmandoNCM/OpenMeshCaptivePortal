<?php
if ($open_external_browser){
	$destination = 'googlechrome://navigate?url=https://audi-client.sundevs.cloud/#/' . $phone;
} else {
	$destination = 'https://audi-client.sundevs.cloud/#/' . $phone;
}
Logger::log("Using destination: $destination", 'info', __FILE__, __LINE__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Codigo de acceso rapido</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		body {
			text-align: center;
			font-family: Verdana, Geneva, sans-serif;
		}

		p {
			color: #999;
			line-height: 20px;
			font-size: 14px;
		}
	</style>
	<script>
		function openExternalBrowser(){
				document.getElementById("connect-button").disabled = true;
				fetch('<?php echo $signedUrl; ?>');
				window.open("<?php echo $destination; ?>");
		};
	</script>
</head>
<body>
	<h1>Fast pass Audi</h1>
	<img src="<?php echo $base64QrCode ?>">

	<p>
		Presenta este código para el ingreso a nuestro pabellón y disfruta de la gran experiencia AUDI que hemos preparado este año para ti. Descubre novedades y conoce más sobre nuestros vehīculos desde tu teléfono móvil.
    </p>
    
	<button id="connect-button" onclick="openExternalBrowser()">Acceder a Internet</button>
	
</body>
</html>