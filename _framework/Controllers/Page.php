<?php 
namespace Controllers;
class Page {

	public static $_directoryIndex ="index.phtml";

	public static function load($route){

		if (substr($route, -1)==="/"){$route .=self::$_directoryIndex;}
		if (file_exists('_framework/Views/'.$route) && !preg_match('/__fragments/', $route)) {
			require_once('_framework/Views/'.$route);
		}
		else{
			require_once('_framework/Views/404.phtml');
		}
		
	}

	 public static function loadFragments($name)
	 {
	 	if (file_exists(('_framework/Views/__fragments/'.$name.".phtml")))
	 	{
	 		require_once '_framework/Views/__fragments/'.$name.".phtml";
	 	}
	 	else{echo 'Pas de fichier';}
	 }
	public static function Page($route)
	{
		

		if($route == '/')
		{
			require_once('_framework/Views/index.phtml');
		}
		else {

		if($route != '/' && file_exists('_framework/Views/'.$route)){
			require_once('_framework/Views/'.$route);
		}
		else 
		{
			echo 'Page not found ! Error 404';
		}
	}

	}
}