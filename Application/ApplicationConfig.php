<?php
//config constants.

define('PHP_TIMEZONE_STRING','UTC');	//set the timezone string as per your app timezone requirements. Make sure it matches with DB time zone below.
define('DB_TIMEZONE_STRING', '+0:00');

define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);	
define('SITE_URL', "http://" . $_SERVER['HTTP_HOST']);

define('VIEW_COMPILE_DIR', APPLICATION_PATH .'/extras/templates_c');
define('VIEW_CACHE_DIR', APPLICATION_PATH .'/extras/templates_cache');
define('VIEW_CONFIG_DIR', APPLICATION_PATH .'/extras/templates_config');

define('IMAGE_PATH', SITE_ROOT . '/images/');
define('IMAGE_URL', SITE_URL . '/images/');

define('SYSTEM_FROM_EMAIL','no-reply@findtrippartner.com');	//it could be your project name

define('APPLICATION_TITLE','FindTripPartner');	//it could be your project name



//Vehicle category, ac, non-ac
$vehicleCats= array('ac' => 'AC','non_ac' => 'NON-AC');
define('VEHICL_CATS', serialize($vehicleCats));


//Vehicle fuel type
$vehicleFuelType = array('petrol' => 'Petrol','diesel' => 'Diesel','gas'=>'Gas');
define('VEHICL_FUEL_TYPE', serialize($vehicleFuelType));


if ($_SERVER['HTTP_HOST'] == "findmycar.com") //Ravi 
{
	define('DB_HOST', 'localhost');
    define('DB_UNAME', 'root');
    define('DB_PWD', 'root');
    define('DB_NAME', 'findmycar');
    define('DEBUG_EMAIL', 'ravimoghariya@hotmail.com');       
    define('ENV', "development");      
    ini_set('display_errors',"On");
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);    
}
else	//production/live env block!!
{
	
	define('DB_HOST', 'localhost');
	define('DB_UNAME','soarwith_pmc');
	define('DB_PWD', 'w7rV,,#C%-ro');
	define('DB_NAME','soarwith_pmc');
	define('DEBUG_EMAIL','penuel@codetoon.com');
	
	define('ENV', "production");
	ini_set('display_errors',"Off");	
	error_reporting(0);		
}


?>