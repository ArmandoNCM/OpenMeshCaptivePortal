<?php
require_once(dirname(__FILE__) . '/../constants.php');
$html_form_process_url = '/splash-page/form_process.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    
    <form action="<?php echo $html_form_process_url; ?>" method="post">

        <?php
        foreach ($html_form_hidden_fields_array as $key => $value) {
            echo "<input type='hidden' name='$key' id='hfv-$key' value='$value' />";
        }
        ?>

        <input type="submit" value="Conectar" class="btn-send">
    </form>

</body>
</html>