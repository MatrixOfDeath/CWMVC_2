<?php

namespace Controllers;

class Utilisateur
{
	public static function register()
	{
        // Vérif que le post register existe avec le bon submit
        if(isset($_POST['submitRegister'])) {
            $_POST['passwd'] = \Models\Utilisateur::enCrypt($_POST['passwd']);
            $User = new \Models\Utilisateur($_POST);

            $User->save();

            echo "<script>
            alert('Vous êtes bien enregistrer, vous allez être rediriger sur la page de login !');
            window.location.href='../login.phtml';
            </script>";
        }else{
            header("Location: ../register.phtml" );
        }
	}

	public static function profile(){
        // Vérification et provenance du bon formulaire profile
        if(isset($_POST['submitProfile'])){
            $tmpUser = \Models\Utilisateur::FindByID($_POST['id']);
            if($_POST['passwd'] == $tmpUser->passwd){
                //nothing
            }else{
                $_POST['passwd'] = \Models\Utilisateur::enCrypt($_POST['passwd']);
            }

            if(!isset($_FILES) || empty($_FILES['avatar']['name']) || $_FILES['avatar']['error'] != 0) {
                //TODO: No files or other issues maybe check les extensions, tailles en back
            }else{
                $target = "upload/";
                $file = time().$_FILES['avatar']['name'];
                $target = $target . basename( dirname('/').$file) ;
                if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target))
                {
                    if(!is_null($tmpUser->img)){
                        unlink('upload/' . basename( dirname('/').$tmpUser->img));
                    }
                        $_POST['img'] = $file;


                    $message = "The file ". basename(dirname('/').$_FILES['avatar']['name']). " has been uploaded";
                }
                else {
                    $message = "Sorry, there was a problem uploading your file.";
                }
            }

            $User = new \Models\Utilisateur($_POST);
            $User->save();

            echo "<script>alert('Vous avez bien enregistré vos informations !');
                  </script>";
        }
        // Affichage du formulaire après le post ou après
        if(isset($_SESSION['user'])) {
            $User = \Models\Utilisateur::FindByID($_SESSION['user']['id']);
            if ($User) {
                if (is_null($User->img) || !file_exists('upload/' . basename( dirname('/').$User->img))){
                    $src_img = "/img/noavatar.jpg";
                }else { $src_img = "/upload/".$User->img;
                }
                require_once('_framework/Views/profile.phtml');
            } else {
                echo "Erreur getting user infos";
                header("Location: ../index.phtml");
            }
        } else{
            echo "Erreur finding User session";
            header("Location: ../index.phtml");
        }
    }



	public static function login()
	{
        if(isset($_POST['submitLogin'])) {

            $User = \Models\Utilisateur::userLogin($_POST['email'], \Models\Utilisateur::enCrypt($_POST['passwd']));
            //  echo "Login";
            // if sha1 pwd.. = ..
            if ($User) {
                \_Core\Session::initUserSession($User);
                header("Location: ../index.phtml");
            }
            else {
                echo "<script>alert('Saisies incorrecte veuillez réessayer!');
                window.location.href='../login.phtml';
            </script>\";
                  </script>";
                header("Location: ../login.phtml" );
            }
        } else{
            echo "erreur login";
        }


	}

	public static function logout()
	{
        \_Core\Session::removeUserSession();

	}

	
	
	

}