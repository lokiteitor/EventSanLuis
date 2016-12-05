<?php 
/**
 * @file Gestiona la capa de autentificacion
 */

require_once 'config.php';
require_once 'Url.php';
require_once 'DB.php';


/**
* @class Auth
* @brief Validar las credenciales de usuario 
*/
class Auth extends DB
{
    public $conexion;
    public $table = "USUARIO";
    private $userfield = "USERNAME";
    private $passfield = "PASSWORD";
    private $idfield = "ID_USUARIO";

    private $username = null;
    private $password = null;
    private $userid = null;

    /**
     *@brief Esta clase crea y verifica que el usuario se ha autentificado
     */

    function __construct()
    { 
        $this->conexion = self::conectar();

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION['username'])) {
            $this->username = $_SESSION['username'];
        }
        if (isset($_SESSION['uid'])) {
            $this->userid = $_SESSION['uid'];        
        }

    }

    /**
     * @brief Verificar en la tabla de usuario las credenciales que proporciona
     * @param array - Arreglo con las credenciales que el usuario proporciono
     * 
     */
    public function setAuth($userData)
    {
        global $site;

        // validar si es administrador

        if ($userData['username'] == 'Administrador' && $userData['password'] == 'password') {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();    
            }                
            
            $_SESSION['uid'] = 0;
            $_SESSION['username'] = 'Administrador';
            $_SESSION['isauth']  = 'true';
            $_SESSION['isadmin'] = 'true';

            $this->username = $_SESSION['username'];
            $this->userid = $_SESSION['uid'];       
            return;     
        }

        // obtener los datos del usuario
        // los ? se remplazan por los datos a escapar
        // asi evitamos problemas como sql injection
        $sql = 'SELECT * FROM '. $this->table . ' WHERE '.$this->userfield.'=?';

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s',$userData['username']);
        $stmt->execute();

        // la consulta fallo
        if ($stmt->errno) {
            die('Error al consultar a la base de datos ' . $stmt->error);          
        }
        else{
            // la consulta no fallo
            $result = $stmt->get_result();
            if (!$result) {
                die('Error durante la consulta');
            }

            if ($result->num_rows == 0) {
                // no existe el usuario retornar a la pagina de inicio
                header("Location: ".getUrl('/login'));
            }
            // verificar la contraseña 
            $query = $result->fetch_array(MYSQLI_ASSOC);


            if (password_verify($userData['password'],$query[$this->passfield])) {
                // crear una session
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();    
                }                
                
                $_SESSION['uid'] = $query[$this->idfield];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['isauth']  = 'true';

                $this->username = $_SESSION['username'];
                $this->userid = $_SESSION['uid'];
            }
            else{
                // la contraseña no coincide       
                header("Location: ".getUrl('/login'));
            }
            $result->free();
        }        
        $stmt->close();
    }
    /**
     * @brief Verifica que el usuario esta autentificado       
     */
    public function isAuth()
    {   
        $login = false;
        // verificar que la session este autentificada
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();        
        }

        if (isset($_SESSION['uid']) && ($_SESSION['isauth'] == 'true')) {
            $login = true;
        }        

        return $login;
    }

    /**
     * @brief Verifica que el usuario loguedo es administrador
     * @return retorna un boolean, true si esta autentificado , false en caso
     *  contrario
     */

    public function isAdmin()
    {   
        $login = false;
        // verificar que la session este autentificada
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();        
        }

        if (isset($_SESSION['uid']) && ($_SESSION['isauth'] == 'true')) {
            if (isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 'true') {
                $login = true;
            }
        }        

        return $login;
    }

    /**
     * @brief permite la peticion si el usuario esta autentificado
     * como administrador
     */
    public function filterAdmin(){
        global $site;
        // abrir la session
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['isauth']) || ($_SESSION['isauth'] != 'true')) {
            header("Location: ".getUrl('/login'));
        }

        if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 'true') {
            header("Location: ".getUrl('/login'));   
        }                    
        return;
    }

    /**
     * @brief permite la peticion si el usuario esta logueado
     */
    public function filterLogin()
    {
        global $site;
        // abrir la session
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['isauth']) || ($_SESSION['isauth'] != 'true')) {
            header("Location: ".getUrl('/login'));
        }
        return;
    }

    /**
     * @brief cierra la session destruyendo las cookies y la session
     */
    public function logout()
    {
        global $site;
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
        session_destroy();
        header("Location: ".getUrl('/')); 
    }

    /**
     * @brief obtiene el nombre del usuario autentificado   
     * @return retorna una cadena con el nombre de usuario
     */
    public function getUsername()
    {
        $username = false;
        if ($this->isAuth) {
            $username = $this->username;
        }
        return $username;
    }

    /**
     * @brief obtiene el ID del usuario logueado
     * @return retorna un string con el ID del usuario
     */
    public function getID()
    {
        $ID = false;
        if ($this->isAuth()) {
            $ID = $this->userid;
        }
        return $ID;
    } 
    function __destruct()
    {
        $this->conexion->close();

    }
}



 ?>