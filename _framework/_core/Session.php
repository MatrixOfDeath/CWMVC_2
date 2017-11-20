<?php
namespace _Core;

class Session 
{

    function __construct()
    {
        session_start();
    }

    /**
     * @param null $data
     *
     * Instance Session de l'user après login
     */
	public static function initUserSession($data=null)
	{
        if(isset($_SESSION)) {
            if (isset($data) && !empty($data)) {

                $_SESSION['user'] = array(
                    'id' => $data->id,
                    'nom' => $data->nom,
                    'prenom' => $data->prenom,
                    'email' => $data->email,
                    'passwd' => $data->passwd,
                );
            } else {
                echo "Erreur !";
            }
        }else{
            echo "Session non intialisé";
        }
	}

	public static function removeUserSession()
	{
        unset ($_SESSION['user']);
        header("Location: ../index.phtml" );
        die();
	}

}



