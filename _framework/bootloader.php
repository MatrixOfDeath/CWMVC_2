<?php 

class Autoloader {

	public static function register()
	{
		spl_autoload_register(array(__CLASS__,'autoload'));
	}

	public static function autoload($class)
	{
		$path = __DIR__."/".str_replace("\\", "/", $class).".php";

		if(file_exists($path)){
			require_once $path;
		}
		else
			{
				die ("Impossible de charger la : ".$class);
			}

	}
}

Autoloader::register();