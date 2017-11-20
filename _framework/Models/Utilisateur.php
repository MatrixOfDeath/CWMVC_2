<?php 
namespace Models;
use \_Core\Database as Database;

class Utilisateur
{
	public $id 		= -1;
	public $nom 	= null;
	public $prenom	= null;
	public $email	= null;
	public $passwd	= null;
    public $img     = null;

	function __Construct($arg = false)
	{
		if($arg){
			foreach ($this as $key => $value) {
				if (isset($arg[$key]))
				{
				$this->$key = $arg[$key];
				}
			}
		}

	}

    /**
     * @param $pass
     * @param string $passphrase
     * @return string hash du mot de passe
     */
    public static function enCrypt($pass, $passphrase='karim$2a$%13$boubrit'){
        $salt = openssl_random_pseudo_bytes(22);
        $salt = $passphrase . strtr(base64_encode($salt), array('_' => '.', '~' => '/'));

        return crypt($pass, $salt);
    }

	function save(){
		if ($this->id == -1 )
		{
			$this->id = Database::instance()->insert(__CLASS__,$this);
		}
		else
		{
            if(is_null($this->img)){
                unset($this->img);
            }
			Database::instance()->update(__CLASS__,$this);
		}
	}

	public function delete(){
		Database::instance()->delete(__CLASS__,$this);
	}

	public function update($data){
        Database::instance()->update(__CLASS__, $this);
    }

	public static function FindByID($id)
	{
		return Database::instance()->select(__CLASS__,["id"=>$id]);
	}

	public static function userLogin($email, $passwd)
	{
		$data = array("email" => $email , "passwd" => $passwd);

		return Database::instance()->select(__CLASS__, $data);
	}
	

}