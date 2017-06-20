<?php
$file = "resources\\credentials.json";
$data = array();
if (file_exists($file)) {
    $credentials = file_get_contents($file, FILE_USE_INCLUDE_PATH);
    $data = json_decode($credentials, true);
}
else{
    echo "Veuillez acc&eacute;der &agrave; <a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/install.php' > l'installation.</a>";
    exit(1);
}

global $config;
$config = array(
    "db" => array(
        "dbname" => $data['dbname'],
        "username" => $data['username'],
        "password" => $data['password'],
        "host" => $data['dbhost']
),
    "default_rank" => "1",
    "max_object_user" => 25,
    "max_object_assoc" => 200
);

define("TEMPLATES_PATH", realpath(dirname(__FILE__). '/templates'));
define("CLASS_PATH",  realpath(dirname(__FILE__). '/classes'));
define("ERROR_PATH",  realpath(dirname(__FILE__). '/errors'));

//Autoloader si on instancie une classe non déclarée
function autoloader($class)
{
    include CLASS_PATH.'/'.$class.'.php';
}

spl_autoload_register('autoloader');
?>