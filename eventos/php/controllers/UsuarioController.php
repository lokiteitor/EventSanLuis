<?php 
require_once 'php/database/Usuario.php';
require_once 'php/include/Auth.php';

use HybridLogic\Validation\Validator;
use HybridLogic\Validation\Rule;


class UsuarioController
{

    public function registrar()
    {
        // clase para validar los datos
        $validator = new Validator();

        $validator
        ->add_rule('username',new Rule\MinLength(3))
        ->add_rule('username',new Rule\MaxLength(12))
        ->add_rule('username',new Rule\AlphaNumeric())
        ->add_rule('password',new Rule\AlphaNumeric())
        ->add_rule('email',new Rule\Email());



        $user = new Usuario();
        $success = true;

        if (isset($_POST['username'])) {
            // TODO :revisar que es unico            
            $user->USERNAME = $_POST['username'];
        }
        else{
            $success = false;
        }


        if (isset($_POST['password']) && isset($_POST['cfpassword'])) {
            if ($_POST['password'] == $_POST['cfpassword']) {
                // encriptar la contraseña
                $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $user->PASSWORD = $password;
            }
            else
                $success = false;
        }
        else
            $success = false;

        if (isset($_POST['email'])) {
            $user->EMAIL = $_POST['email'];
        }
        else
            $success = false;


        if ($validator->is_valid($_POST) && $success) {
            $user->ACTIVO = true;
            $user->save();

            $auth = new Auth();
            $auth->setAuth(array(
                'username'=>$user->USERNAME,
                'password'=>$_POST['password']));
        }else{         
            setcookie('formerror',json_encode($validator->get_errors()),time()+1000);
            header("Location: ".getUrl('/registro'));
        }

        return $success;
    }

    public function perfil($id)
    {
        $user = Usuario::find($id);
        $validator = new Validator();

        $validator
        ->add_rule('nombre',new Rule\AlphaNumeric())
        ->add_rule('email',new Rule\Email());


        if (isset($_POST['nombre'])) {
            $user->NOMBRE = $_POST['nombre'];
        }

        if (isset($_POST['apellido'])) {
            $user->APELLIDOS = $_POST['apellido'];
        }

        if (isset($_POST['sexo'])) {
            $user->SEXO = $_POST['sexo'];
        }

        if (isset($_POST['nacimiento'])) {
            $user->FECHA_NAC = $_POST['nacimiento'];
        }

        if (isset($_POST['rfc'])) {
            $user->RFC = $_POST['rfc'];
        }

        if (isset($_POST['email'])) {
            $user->EMAIL = $_POST['email'];
        }

        if (isset($_POST['telefono'])) {
            $user->TELEFONO = $_POST['telefono'];
        }

        if (isset($_POST['direccion'])) {
            $user->DIRECCION = $_POST['direccion'];
        }

        if ($validator->is_valid($_POST)) {
            $user->save();            
        }
        else{
            header("Location: ".getUrl('/user/settings'));
        }
        
    }

    public function setPassword($id)
    {
        $user = Usuario::find($id);

        if (isset($_POST['oldpass']) && password_verify($_POST['oldpass'],$user->PASSWORD)) {
            if (isset($_POST['newpass'])) {
                $newpass = password_hash($_POST['newpass'],PASSWORD_DEFAULT);
                $user->PASSWORD = $newpass;
            }
        }

        $user->save();
    }


}



 ?>