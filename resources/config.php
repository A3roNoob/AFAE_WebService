<?php
/**
 * Created by IntelliJ IDEA.
 * User: Wellan
 * Date: 02/04/2017
 * Time: 23:14
 */



global $config;
$config = array(
    "db" => array(
        "dbname" => "afae",
        "username" => "afae",
        "password" => "afae",
        "host" => "localhost"
    ),
    "default_user" => "1",
    "max_object_user" => 15,
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