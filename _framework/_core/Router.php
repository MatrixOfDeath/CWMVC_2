<?php

namespace _Core;

class Router
{
	public static $route;
	public static $context;
	public static $_isAction;
	public static $_isView;
	public static $action;
	public static function Dispatch(){
		self::$action = $_GET['route'];
		self::$route ='/'.self::$action;
		self::$route = $_GET['route'] ? $_GET['route'] : "/";

		//je controle dans ma route si la fin de mon url j'ai un slash
		self::$context = (substr(self::$route, -1) !== "/" && ( 
			(array_key_exists('extension',pathinfo(self::$route)) && !pathinfo(self::$route)['extension']) 
			|| !array_key_exists('extension',pathinfo(self::$route))
		)
		) ? 'action' : 'view';

		self::$_isAction = self::$context === 'action' ? true : false;
		self::$_isView = self::$context === 'view' ? true : false;


		if(self::$_isView)
		{
		  \Controllers\Page::load(self::$route);
		}

		if(self::$_isAction)
		{
			\Controllers\Action::call(self::$action);
		}

	}
}

