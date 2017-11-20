<?php

require_once (__DIR__."/_framework/bootloader.php");

//Constructeur
new _Core\Session;
_Core\Router::Dispatch();


//Controllers\Page::Page();


//$user = new Models\Utilisateur();
//$ControllerUser = new Controllers\Utilisateur();

