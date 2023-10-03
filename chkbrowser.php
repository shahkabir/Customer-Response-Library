<?php
echo $br = $_SERVER['HTTP_USER_AGENT'] . "\n\n";

$browser = get_browser(null, true);

echo '<pre>';
print_r($_SERVER);
echo '<pre>';

echo 'strpos:'.strpos($br,'.NET');


?>