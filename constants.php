<?php
/* Debug */
define('DEBUG', TRUE);

/* Open Mesh Access Points Configuration */

/* Claves de cifrado */
//Codigo usado para firmar el usuario - Funciona entre Scripts
define('SHARED_SECRET', 'ThisIsASecret');

/* Parametros de seguridad de los scripts de validacion */
//Tiempo en el que se puede utilizar un token, no modificar
define('OPENMESH_TOKEN_VALID_DURATION', '120');

define('ROOT', dirname(__FILE__));

?>
