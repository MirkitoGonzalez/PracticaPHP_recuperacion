<?php

require_once "modelo/modelo.php";

class controlador
{
    private $modelo;
    private $mensajes;

public function __construct()
    {
        $this->modelo = new modelo();
        $this->mensajes = [];
    }

    public function includes()
    {
        require_once 'vistas/includes/helpers.php';
    }

    // PASSWORD ONLY ADMIN
    public function recuperarPassword()
    {
        $parametros = ["tituloventana" => "Recuperar_contraseña"];
        $this->includes();
        include_once 'vistas/vistaRecuperarContraseña.php';
    }

    public function enviarRecuperarPassword()
    {
        $guardarLibro = $this->validarRecuperarPassword();
        if ($guardarLibro["valido"] == true) {
            $id = 1;
            $email = $_POST["email"];
            $usuario = $_POST["usuario"];
            $password = sha1($_POST["password"]);

            $resultado = $this->modelo->recuperarPassword($id, $usuario, $email, $password);
            if ($resultado["correcto"]) {
                echo "<div class='alert alert-success'>Se ha modificado la contraseña.</div>";
                $this->recuperarPassword();
            } else {
                $_SESSION["errores"]["recuperado"] = "Compruebe que los datos son correctos.";
                $this->recuperarPassword();
            }
        } else {
            $_SESSION["errores"]["recuperado"] = "Los datos introducidos no son válidos.";
            $this->recuperarPassword();
        }
    }

    public function validarRecuperarPassword()
    {
        if (isset($_POST)) {
            $errores = array();
            $email = !empty($_POST["email"]) ? $_POST["email"] : false;
            $nombreUsuario = !empty($_POST["usuario"]) ? $_POST["usuario"] : false;
            $password = !empty($_POST["password"]) ? $_POST["password"] : false;
            // Validamos los datos
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores["email"] = "El email introducido no es válido";
                $emailValido = true;
            } else {
                $emailValido = false;
            }
            if (!empty($password) && !strlen($password) < 8 && preg_match("/[a-zA-Z ]/", $password) && preg_match("/[0-9]/", $password) && preg_match("/[@#-_%&^+=!?.,<>]/", $password)) {
                $passwordValido = true;
            } else {
                $passwordValido = false;
                $errores["password"] = "La contraseña no es valida";
            }
            $guardarLibro = array();
            $guardarLibro["valido"] = false;
            if (count($errores) == 0) {
                $guardarLibro["valido"] = true;
            } else {
                $_SESSION["errores"] = $errores;
            }
        }
        return $guardarLibro;
    }

    // LOGS 




    //  EL RESTO
    public function vistaLogin()
    {
        $parametros = ["tituloventana" => "Login"];
        $this->includes();
        include_once 'vistas/vistaLogin.php';
    }

    public function vistaRegistro()
    {
        $parametros = ["tituloventana" => "Registro"];
        $this->includes();
        include_once 'vistas/vistaRegistro.php';
    }

    public function vistaInicio()
    {
        $parametros = ["tituloventana" => "Inicio"];
        $this->includes();
        include_once 'vistas/vistaInicio.php';
    }

    public function vistaUsuarios()
    {
        $parametros = [
            "tituloventana" => "ListadoUsuarios",
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "mensajes" => []
        ];

        //Establecemos el número de registros a mostrar por página,por defecto (1)5
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 1;
        //Establecemos el la página que vamos a mostrar, por página, por defecto la 1ª
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
        
        if ($pagina < 1 )
        {
            $pagina = 1;
        }

        //Definimos la variable $inicio que indique la posición del registro
        // desde el que se mostrarán los registros de una página dentro de la paginación.
        $inicio = ($pagina > 1) ? (($pagina * $regsxpag) - $regsxpag) : 0;

        $resultadoModelo = $this->modelo->listadoUsuarios($inicio,$regsxpag);

        if($resultadoModelo['correcto'])
        {
            // con esto los datos anteriores , los identificamos aqui para usarlos en las vistas
            $parametros['datos'] = $resultadoModelo['datos'];
            $parametros['datosPaginacion'] = $resultadoModelo['datosPaginacion'];
            $this->mensajes[]=[
                'tipo'=>'success',
                'mensajes'=>'Se ha realizado correctamente'
            ]; 
        }
        else
        {
            $this->mensajes[]=[
                'tipo'=>'danger',
                'mensajes'=>"El listado no pudo realizarse correctamente!! <br/>({$parametros["error"]})"
            ]; 
        }

        $parametros['mensajes'] = $this->mensajes;
        
        include_once 'vistas/vistaUsuarios.php';
    }

    public function vistaEditar()
    {
        $parametros = [
            "tituloventana" => "EditarUsuarios",
        ];

        $this->includes();

        $_SESSION['editarID'] = $_GET['id'];
        $id = $_GET['id'];

        $resultado = $this->modelo->seleccionarUsuario($id);
        if($resultado['correcto'] = true)
        {
            $_SESSION['editar'] = $resultado['datos'];
            include_once 'vistas/vistaEditar.php';
        }
        else
        {
            echo "Ha pasado algo editando";
            $this->vistaLogin();
            // No deberia de pasar jamás //
        }
    }

    public function enviarLogin()
    {
        $loginValido = $this->validarLogin();
        // al pulsar en boton acceder y es todo OK ↓
        if($loginValido)
        {
            $usuario = $_POST['usuario'];
            $password = sha1($_POST['password']);

            // creamos cookies
            if(isset($_POST['recuerdo']))
            {
                setcookie('usuario', $_POST['usuario'], (time() + (60*60*24*7*4))); // SET Tiempo de vida de la cookie a 1 mes
                setcookie('password', $_POST['password'], (time() + (60*60*24*7*4)));
            }

            // Aqui irían los datos necesarios por parametro (los que metemos en los campos input) 
            $return = $this->modelo->login($usuario, $password);

            if ($return['correcto'] == true)
            {
                if(($return['datos']->usuario == $usuario) && ($return['datos']->password == $password))
                {
                $_SESSION['activa'] = $return['datos'];

                echo "<div class='alert alert-success'>Enhorabuena, te has logueado correctamente.</div>";
                $this->logLogin();
                $this->vistaInicio();
                }
                else{
                    echo "<div class='alert alert-danger'>DATOS INCORRECTOS.</div>";
                    $this->vistaLogin();
                }
            }
            else // se queda en la misma pues no se pudo loguear
            {   
                echo "<div class='alert alert-danger'>Error en el logueo.</div>";
                $this->vistaLogin();  
            }
        }
        else
        {
            echo "<div class='alert alert-danger'>No iniciaste sesion.</div>";
            $this->vistaLogin();
        }
    }

    public function enviarUsuarioEditado()
    {
        $editValido = $this->validarRegistro();
        
        if($editValido) 
        {   // el id se pillaria de la sesion a excepcion de los anteriores
            $id = $_SESSION['editarID'];
            $rol = $_POST['rol'];
            $usuario = $_POST['usuario'];
            $password = sha1($_POST['password']);
            $email = $_POST['email'];
            
            // Aqui irían los datos necesarios por parametro (los que metemos en los campos input) 
            $return = $this->modelo->edicionUsuario($id,$usuario, $password, $email, $rol);

            if ($return['correcto'] == true)
            {   

                $_SESSION['activa'] = $return['datos'];
                echo "<div class='alert alert-success'>Enhorabuena, has editado correctamente.</div>";
                $this->logEditar();
                $this->vistaUsuarios();
            }
            else // se queda en la misma pues no se pudo registrar
            {
                echo "<div class='alert alert-danger'>Error al registrar en la BBDD.</div>";
                $this->vistaUsuarios();
            }
        }
        else
        {   echo "<div class='alert alert-danger'>Los campos que has introducido no son válidos.</div>";
            $this->vistaEditar();
        } // los campos rellenados no eran válidos para registrarse
    }

    public function enviarRegistro()
    {
        $registroValido = $this->validarRegistro();
        
        if($registroValido)
        {
            $usuario = $_POST['usuario'];
            $password = sha1($_POST['password']);
            $email = $_POST['email'];
            
            // Aqui irían los datos necesarios por parametro (los que metemos en los campos input) 
            $return = $this->modelo->registro($usuario, $password, $email);

            if ($return['correcto'] == true)
            {
                echo "<div class='alert alert-success'>Enhorabuena, te has registrado correctamente.</div>";
                $this->vistaLogin();
            }
            else // se queda en la misma pues no se pudo registrar
            { $this->vistaRegistro();  }
            
            
        }
        else
        {   echo "<div class='alert alert-danger'>Los campos que has introducido no son válidos.</div>";
            $this->vistaRegistro();
        } // los campos rellenados no eran válidos para registrarse
    }

    public function validarRegistro()
    {
        if(isset($_POST))
        {
            $errores = array();

            // coger datos del formulario para tratarlos
            $usuario = !empty($_POST['usuario'])? $_POST['usuario']: false;
            $password = !empty($_POST['password'])? $_POST['password']: false;
            $email = !empty($_POST['email'])? $_POST['email']: false;
            // ahora validaremos cada uno de los campos

            // Validar usuario
            if (!empty($usuario) && !is_numeric($usuario) && !preg_match("/[0-9]/", $usuario)) {
                $usuarioValido = true;
            }
            else
            {
                $usuarioValido = false;
                $errores["usuario"] = "El usuario introducido no es válido.";
            }

             // Validar Password
            if (!empty($password) && !strlen($password) < 8 && preg_match("/[a-zA-Z ]/", $password) && preg_match("/[0-9]/", $password) && preg_match("/[@#-_%&^+=!?.,<>]/", $password))
            {
                $passwordValido = true;
            }
            else
            {
                $passwordValido = false;
                $errores["password"] = "La contraseña no es valida";
            }

            // Validar Email
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $errores["email"] = "El email introducido no es válido";
                $emailValido = true;
            }
            else
            {
                $emailValido = false;
            }

            // registro valido es la variable a devolver, "starts in false"
            $registroValido = false;
            // si no se ha añadido ningun error estará a 0 asique seguirá palante
            if (count($errores) === 0)
            {
                $registroValido = true; // no errors
            }
            else
            {
                $_SESSION['errores'] = $errores; // se usarán más adelante pues se recogen en la Sesión(navg abierto)
            }
            return $registroValido;

        }
    }

    public function validarLogin()
    {
        if(isset($_POST))
        {
            $errores = array();

            // coger datos del formulario para tratarlos
            $usuario = !empty($_POST['usuario'])? $_POST['usuario']: false;
            $password = !empty($_POST['password'])? $_POST['password']: false;
            // ahora validaremos cada uno de los campos

            // Validar usuario
            if (!empty($usuario) && !is_numeric($usuario) && !preg_match("/[0-9]/", $usuario)) {
                $usuarioValido = true;
            }
            else
            {
                $usuarioValido = false;
                $errores["usuario"] = "El usuario introducido no es válido.";
            }

             // Validar Password
            if (!empty($password) && !strlen($password) < 8 && preg_match("/[a-zA-Z ]/", $password) && preg_match("/[0-9]/", $password) && preg_match("/[@#-_%&^+=!?.,<>]/", $password))
            {
                $passwordValido = true;
            }
            else
            {
                $passwordValido = false;
                $errores["password"] = "La contraseña no es valida";
            }


            // registro valido es la variable a devolver, "starts in false"
            $loginValido = false;
            // si no se ha añadido ningun error estará a 0 asique seguirá palante
            if (count($errores) === 0)
            {
                $loginValido = true; // no errors
            }
            else
            {
                $_SESSION['errores'] = $errores; // se usarán más adelante pues se recogen en la Sesión(navg abierto)
            }
            return $loginValido;

        }
    }

    public function borrarUsuario()
    {
        if (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_SESSION["activa"]->id == $_GET["id"])
        {
            echo "<div class='alert alert-danger'>No puede borrar su usuario.</div>";
            $this->vistaUsuarios();
        }
        
        elseif (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_SESSION["activa"]->rol == 'administrador')
        {
            $id = $_GET["id"];
            $resultadoModelo = $this->modelo->borrarUsuario($id);
            if ($resultadoModelo["correcto"]) {
                echo "<div class='alert alert-success'>Usuario borrado correctamente.</div>";
                $this->logEliminar();
                $this->vistaUsuarios();
            } else {
                echo "<div class='alert alert-danger'>Ha ocurrido un error.</div>";
                $this->vistaUsuarios();
            }
        } else {
            echo "<div class='alert alert-danger'>Ha ocurrido un error.</div>";
            $this->vistaUsuarios();
        }
    }

    public function cerrarSesion()
    {
        session_destroy();
        // destruimos la sesion y redirigimos al login 
        if (isset($_SESSION))
        {
            echo '<div class="alert alert-success">' . "Sesión cerrada correctamente :)" . '</div>';
        }
        $this->vistaLogin();
    }

    public function logEditar(){
        if(isset($_SESSION['activa']))
        {
            $id=$_SESSION["activa"]->id;
            $result=$this->modelo->logEditar($id);
        }

    }
    public function logLogin(){
        if(isset($_SESSION['activa']) && !empty($_SESSION['activa']))
        {
            $id=$_SESSION["activa"]->id;
            $result=$this->modelo->logLogin($id);
        }
    }
    public function logEliminar(){
        if(isset($_SESSION['activa']))
        {
        $id=$_SESSION["activa"]->id;
        $result=$this->modelo->logEliminarUsuario($id);
        }
    }

/* LIBROS O LO QUE SEA */

    public function vistaLibros()
    {
        $parametros = [
            "tituloventana" => "ListadoLibros",
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "mensajes" => []
        ];

        //Establecemos el número de registros a mostrar por página,por defecto 5
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 5;
        //Establecemos el la página que vamos a mostrar, por página, por defecto la 1ª
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
        
        if ($pagina < 1 )
        {
            $pagina = 1;
        }

        //Definimos la variable $inicio que indique la posición del registro
        // desde el que se mostrarán los registros de una página dentro de la paginación.
        $inicio = ($pagina > 1) ? (($pagina * $regsxpag) - $regsxpag) : 0;

        $resultadoModelo = $this->modelo->listadoLibros($inicio,$regsxpag);

        if($resultadoModelo['correcto'])
        {
            // con esto los datos anteriores , los identificamos aqui para usarlos en las vistas
            $parametros['datos'] = $resultadoModelo['datos'];
            $parametros['datosPaginacion'] = $resultadoModelo['datosPaginacion'];
            $this->mensajes[]=[
                'tipo'=>'success',
                'mensajes'=>'Se ha realizado correctamente'
            ]; 
        }
        else
        {
            $this->mensajes[]=[
                'tipo'=>'danger',
                'mensajes'=>"El listado no pudo realizarse correctamente!! <br/>({$parametros["error"]})"
            ]; 
        }

        $parametros['mensajes'] = $this->mensajes;
        
        include_once 'vistas/vistaLibros.php';
    }

    public function vistaLibrosNoPAG()
    {
        $parametros = [
            "tituloventana" => "ListadoLibros",
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "mensajes" => []
        ];

        //Establecemos el número de registros a mostrar por página,por defecto 5
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 999;
        //Establecemos el la página que vamos a mostrar, por página, por defecto la 1ª
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
        
        if ($pagina < 1 )
        {
            $pagina = 1;
        }

        //Definimos la variable $inicio que indique la posición del registro
        // desde el que se mostrarán los registros de una página dentro de la paginación.
        $inicio = ($pagina > 1) ? (($pagina * $regsxpag) - $regsxpag) : 0;

        $resultadoModelo = $this->modelo->listadoLibros($inicio,$regsxpag);

        if($resultadoModelo['correcto'])
        {
            // con esto los datos anteriores , los identificamos aqui para usarlos en las vistas
            $parametros['datos'] = $resultadoModelo['datos'];
            $parametros['datosPaginacion'] = $resultadoModelo['datosPaginacion'];
            $this->mensajes[]=[
                'tipo'=>'success',
                'mensajes'=>'Se ha realizado correctamente'
            ]; 
        }
        else
        {
            $this->mensajes[]=[
                'tipo'=>'danger',
                'mensajes'=>"El listado no pudo realizarse correctamente!! <br/>({$parametros["error"]})"
            ]; 
        }

        $parametros['mensajes'] = $this->mensajes;
        
        include_once 'vistas/vistaLibrosNoPAG.php';
    }
//editar loquesea
public function vistaEditarLibros()
{
    $parametros = [
        "tituloventana" => "EditarLibros",
    ];

    $this->includes();

    $_SESSION['editarID'] = $_GET['id'];
    $id = $_GET['id'];

    $resultado = $this->modelo->seleccionarLibro($id);
    if($resultado['correcto'] = true)
    {
        $_SESSION['editar'] = $resultado['datos'];
        include_once 'vistas/vistaEditarLibros.php';
    }
    else
    {
        echo "Ha pasado algo editando";
        $this->vistaLibros();
        // No deberia de pasar jamás //
    }
}
public function validarEditarLibro()
    {
        if (isset($_POST))
        {
            $errores = array();
            
            $cod_libro = !empty($_POST["cod_libro"]) ? $_POST["cod_libro"] : false;
            $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : false;
            $descripcion = !empty($_POST["descripcion"]) ? $_POST["descripcion"] : false;
            $titulo = !empty($_POST["email"]) ? $_POST["email"] : false;
            $autor = !empty($_POST["autor"]) ? $_POST["autor"] : false;
            $pvp = !empty($_POST["pvp"]) ? $_POST["pvp"] : false;

            //validar cod_libro /**/ */
            if (!empty($cod_libro) &&  preg_match('/[0-9]/', $cod_libro)) {
                $cod_libroValido = true;
            } else {
                $cod_libroValido = false;
                $errores["cod_libro"] = "El cod_libro introducido no es válido";
            }

            // Nombre
            if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)) {
                $nombreValido = true;
            } else {
                $nombreValido = false;
                $errores["nombre"] = "El nombre introducido no es válido.";
                }

                // descripcion
            if (!empty($descripcion) && !is_numeric($descripcion) && !preg_match("/[0-9]/", $descripcion)) {
                $descripcionValido = true;
            } else {
                $descripcionValido = false;
                $errores["descripcion"] = "La descripcion introducida no es válida.";
                }

                // titulo
            if (!empty($titulo) && !is_numeric($titulo) && !preg_match("/[0-9]/", $titulo)) {
                $tituloValido = true;
            } else {
                $tituloValido = false;
                $errores["titulo"] = "El titulo introducido no es válido.";
                }

                // autor
            if (!empty($autor) && !is_numeric($autor) && !preg_match("/[0-9]/", $autor)) {
                $autorValido = true;
            } else {
                $autorValido = false;
                $errores["autor"] = "El autor introducido no es válido.";
                }

                // PVP
            if (!empty($pvp) && is_numeric($pvp) && preg_match("/[0-9]/", $pvp)) {
                $pvpValido = true;
            } else {
                $pvpValido = false;
                $errores["pvp"] = "El PVP introducido no es válido.";
                }

            /*  TRASTEAR  */ /*
            // // Primer apellido 
            // if (!empty($apellido1) && !is_numeric($apellido1) && !preg_match("/[0-9]/", $apellido1)) {
            //     $apellido1Valido = true;
            // } else {
            //     $apellido1Valido = false;
            //     $errores["apellido1"] = "El primer apellido introducido no es válido.";
            // }
            // // Segundo apellido
            // if (!empty($apellido2) && !is_numeric($apellido2) && !preg_match("/[0-9]/", $apellido2)) {
            //     $apellido2Valido = true;
            // } else {
            //     $apellido2Valido = false;
            //     $errores["apellido2"] = "El segundo apellido introducido no es válido.";
            // }

            // // Email
            // if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

            //     $errores["email"] = "El email introducido no es válido";
            //     $emailValido = true;
            // } else {
            //     $emailValido = false;
            // }
            // // Nombre de usuario
            // if (!empty($nombreUsuario) && !is_numeric($nombreUsuario) && !preg_match("/[0-9]/", $nombreUsuario)) {
            //     $nombreUsuarioValido = true;
            // } else {
            //     $nombreUsuarioValido = false;
            //     $errores["nombreUsuario"] = "El nombre de usuario introducido no es válido.";
            // }
            // // Password
            // if (!empty($password) && !strlen($password) < 8 && preg_match("/[a-zA-Z ]/", $password) && preg_match("/[0-9]/", $password) && preg_match("/[@#-_%&^+=!?.,<>]/", $password)) {
            //     $passwordValido = true;
            // } else {
            //     $passwordValido = false;
            //     $errores["password"] = "La contraseña no es valida";
            // }
            // // Telefono móvil
            // if (!empty($telefonoMovil) && preg_match('/^[6-7][0-9]{8}$/', $telefonoMovil)) {
            //     $telefonoMovilValido = true;
            // } else {
            //     $telefonoMovilValido = false;
            //     $errores["telefonoMovil"] = "El teléfono movil introducido no es válido";
            // }
            // // Telefojo fijo
            // if (!empty($telefonoFijo) && preg_match('/^[9][0-9]{8}$/', $telefonoFijo)) {
            //     $telefonoFijoValido = true;
            // } else {
            //     $telefonoFijoValido = false;
            //     $errores["telefonoFijo"] = "El teléfono fijo introducido no es válido";
            // }
            */
            $guardarLibro = array();
            $guardarLibro["valido"] = false;
            if (count($errores) == 0) {
                $guardarLibro["valido"] = true;
            } else {
                $_SESSION["errores"] = $errores;
            }
        }
        return $guardarLibro;
    }

    public function enviarEditarLibros()
    {
      //  $guardarLibro = $this->validarEditar();
       // if ($guardarLibro["valido"] == true) {
            $id = $_SESSION["idEditar"];
            $cod_libro = $_POST["cod_libro"];
            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $pvp = $_POST["pvp"];
            $titulo = $_POST["titulo"];
            $autor = $_POST["autor"];

 
            $resultado = $this->modelo->edicionLibro($id,$cod_libro, $nombre, $descripcion, $titulo,$autor,$pvp);
           

            if ($resultado['correcto'] == true) {
                $this->logEditarLibro();
                $this->vistaLibros();
            } else {
                echo "Ha ocurrido un error editando :(";
                $this->vistaLibros();
            }
        // } else {
        //     $this->vistaLibros();
        // }
    }

    // borrarlo
    public function borrarLibro()
    {
        if (isset($_GET["id"]) && $_SESSION['activa']->rol == 'administrador')
        {
            $id = $_GET["id"];
            $resultModelo = $this->modelo->borrarLibro($id);

            if ($resultModelo["correcto"]) {
                $this->logEliminarLibro();
                echo "<div class='alert alert-success'>Libro borrado correctamente.</div>";
                $this->vistaLibros();
                
            } else {
                echo "<div class='alert alert-danger'>Ha ocurrido un error 1.</div>";
                $this->vistaLibros();
            }
        } else {
            echo "<div class='alert alert-danger'>Ha ocurrido un error 2.</div>";
            $this->vistaLibros();
        }
    }

    public function logEditarLibro(){
        if(isset($_SESSION['activa']))
        {
            $id=$_SESSION["activa"]->id;
            $result=$this->modelo->logEditarLibro($id);
        }
    }

    public function logEliminarLibro(){
        if(isset($_SESSION['activa']))
        {
        $id=$_SESSION["activa"]->id;
        $result=$this->modelo->logEliminarLibro($id);
        }
    }

    /* LOGS O ACCIONES */

    public function vistaLogs()
    {
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Listado de logs",
            "datos" => NULL,
            "datosPaginacion" => Null,
            "mensajes" => []
        ];

        //Establecemos el número de registros a mostrar por página,por defecto 10
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 10;
        //Establecemos el la página que vamos a mostrar, por página,por defecto la 1
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;

        //Definimos la variable $inicio que indique la posición del registro desde el que se
        // mostrarán los registros de una página dentro de la paginación.
        $inicio = ($pagina > 1) ? (($pagina * $regsxpag) - $regsxpag) : 0;


        // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
        $resultModelo = $this->modelo->listadoLogs($inicio, $regsxpag);
        // Si la consulta se realizó correctamente transferimos los datos obtenidos
        // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
        // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
        if ($resultModelo["correcto"]) :
            $parametros["datos"] = $resultModelo["datos"];
            $parametros["datosPaginacion"] = $resultModelo["datosPaginacion"];
            //Definimos el mensaje para el alert de la vista de que todo fue correctamente
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "El listado se realizó correctamente"
            ];
        else :
            //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$parametros["error"]})"
            ];
        endif;
        //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo 
        //'mensaje', que recoge cómo finalizó la operación:
        $parametros["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        include_once 'vistas/vistaLogs.php';
    }

    public function vistaLogsNoPAG()
    {
        // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
        $parametros = [
            "tituloventana" => "Listado logs sin paginar",
            "datos" => NULL,
            "datosPaginacion" => Null,
            "mensajes" => []
        ];

        //Establecemos el número de registros a mostrar por página,por defecto infinito
        $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 999;
        //Establecemos el la página que vamos a mostrar, por página,por defecto la 1
        $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;

        //Definimos la variable $inicio que indique la posición del registro desde el que se
        // mostrarán los registros de una página dentro de la paginación.
        $inicio = ($pagina > 1) ? (($pagina * $regsxpag) - $regsxpag) : 0;


        // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
        $resultModelo = $this->modelo->listadoLogs($inicio, $regsxpag);
        // Si la consulta se realizó correctamente transferimos los datos obtenidos
        // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
        // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
        if ($resultModelo["correcto"]) :
            $parametros["datos"] = $resultModelo["datos"];
            $parametros["datosPaginacion"] = $resultModelo["datosPaginacion"];
            //Definimos el mensaje para el alert de la vista de que todo fue correctamente
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "El listado se realizó correctamente"
            ];
        else :
            //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$parametros["error"]})"
            ];
        endif;
        //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo 
        //'mensaje', que recoge cómo finalizó la operación:
        $parametros["mensajes"] = $this->mensajes;
        // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
        include_once 'vistas/vistaLogsNoPAG.php';
    }

    /* P D F */ 
    public function vistaPDF()
    {   // tipica del pdf simple ^^
        $parametros = ["tituloventana" => "Generar PDF"];
        $this->includes();
        include_once 'vistas/vistaPdf.php';
    }
    public function listadoPdf()
    {
        require_once 'pdf/vendor/autoload.php';
        ob_start();
        $content = $this->modelo->listadoPdf($_GET['aceptado']);


        $html2pdf = new Spipu\Html2Pdf\Html2Pdf('L', 'A4', 'es', true, 'UTF-8'); // L para que sea horizontal, P para que sea vertical
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $path='Examen/';
        $html2pdf->output($_SERVER['DOCUMENT_ROOT'].'/'. $path .'listado.pdf', 'F');
        header("Location: ../listado.pdf");
    }

}

?>