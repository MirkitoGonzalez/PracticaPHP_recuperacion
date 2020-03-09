<?php 

class modelo
{
    private $conexion;
    private $host = "localhost";
    private $user = "root";
    private $pass = '';
    private $db = "dblibros";

    public function __construct()
    {
        $this->conectar();
    }

    public function conectar()
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $hola = $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Para saber si nos hemos conectado a la db
            echo '<div class="alert alert-success">' .
                "Conectado a la Base de Datos de usuarios!! :)" . '</div>';
            $return = TRUE;
        } catch (PDOException $ex) {
            echo '<div class="alert alert-danger">' .
                "No se ha conectado a la base de datos :(" . '</div>';
            $return = $ex->getMessage();
        }
        return $return;
    }

    public function registro($usuario,$password,$email)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
   
        try
        {
            $sql = "INSERT INTO usuarios (`id`,`usuario`,`password`,`email`,`rol`,`fecha`) VALUES (null, :usuario, :password, :email, 'registrado', current_timestamp );";
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['usuario' => $usuario,'password' => $password ,'email' => $email]);

            if ($query)
            { $return['correcto'] = true; }

        }
        catch (PDOException $ex) {

            $return['error'] = $ex->getMessage();
        }
        return $return;
    }
    

    public function login($usuario,$password)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT * FROM usuarios WHERE (`usuario`=:usuario AND `password`=:password);"; // 2 punto y coma por finalizacion de instruccion
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['usuario' => $usuario,'password' => $password]);

            if ($query)
            { 
                $return['correcto'] = true; 
                $return['datos'] = $query->fetch(PDO::FETCH_OBJ);
            }


        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;
    }

    // PASSWORD

    public function recuperarPassword($id, $usuario, $email, $password)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
        try {  
            // Cambio de contraseña
            $sql = "UPDATE usuarios SET password=:password WHERE (id=:id AND usuario=:usuario AND email=:email)";
            $resultsquery = $this->conexion->prepare($sql);
            $resultsquery->execute(['id' => $id, 'usuario' => $usuario, 'email' => $email, 'password' => $password]);

            // Seleccionamos el registro
            $sql="SELECT * FROM usuarios WHERE id=:id AND usuario=:usuario AND email=:email AND password=:password";
            $resultado=$this->conexion->prepare($sql);
            $resultado->execute(['id' => $id, 'email' => $email, 'usuario' => $usuario, 'password' => $password]);

            // Comprobamos que existe el registro
            $resultado = $resultado->fetch(PDO::FETCH_OBJ);
            //Si existe el registro
            if ($resultado) :
                $return["correcto"] = TRUE;
            endif; // o no :(
        } catch (PDOException $ex) {
            $return["error"] = $ex->getMessage();
        }
        return $return;
    }

    public function listadoUsuarios($inicio,$regsxpag)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM usuarios LIMIT $inicio, $regsxpag"; // calculas rows encontradas
            $resultadoQuery = $this->conexion->query($sql); // standards

            // Para saber el número de páginas que hay
            $totalRegistros = $this->conexion->query("SELECT FOUND_ROWS() as total");
            $totalRegistros = $totalRegistros->fetch()["total"];
            $numeroPaginas = ceil($totalRegistros / $regsxpag);

            if ($resultadoQuery)
            { 
                $return['correcto'] = true; 
                 /* $return['datos'] = $resultadoQuery->fetchAll(PDO::FETCH_ASSOC); */
                 $return['datos'] = $resultadoQuery->fetchAll(PDO::FETCH_OBJ);
                $return['datosPaginacion'] = $numeroPaginas;
            }
        }
        catch (PDOException $ex)
        { $return['error'] = $ex->getMessage(); }

        return $return;
    }

    public function seleccionarUsuario($id)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT * FROM usuarios WHERE (`id`=:id);"; // 2 punto y coma por finalizacion de instruccion
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['id' => $id]);

            if ($query)
            { 
                $return['correcto'] = true; 
                $return['datos'] = $query->fetch(PDO::FETCH_OBJ);
            }


        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;

    }

    public function edicionUsuario($id,$usuario, $password, $email, $rol)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "UPDATE usuarios SET usuario=:usuario, password=:password, email=:email, rol=:rol WHERE (`id`=:id);";
            $query1 = $this->conexion->prepare($sql); // standards
            $query1->execute(['id' => $id, 'usuario' => $usuario, 'password' => $password, 'email' => $email , 'rol' => $rol]);

            $sql = "SELECT * FROM usuarios WHERE (`id`=:id);";
            $query2 = $this->conexion->prepare($sql); // standards
            $query2->execute(['id' => $id]); //tantas como ':' haya

            if ($query1 && $query2)
            { 
                $return['correcto'] = true;
                $return['datos'] = $query2->fetch(PDO::FETCH_OBJ);
            }


        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;
    }

    public function borrarUsuario($id)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
        try {
            $sql = "DELETE FROM usuarios WHERE id=:id";
            $query = $this->conexion->prepare($sql);
            $query->execute(['id' => $id]);
            if ($query) {
                $return["correcto"] = TRUE;
            }
        } catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;
    }

    public function logEditar($id_usuario){
        $sql="INSERT INTO logs (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES (null, :id, 'Ha editado un usuario', CURRENT_TIMESTAMP);";
        $query = $this->conexion->prepare($sql);
        $query->execute(["id"=>$id_usuario]);
        if($query){
            $resultado=true;
        }else{
            $resultado=false;
        }
        return $resultado;
    }
    public function logLogin($id_usuario){
        $sql="INSERT INTO logs (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES (null, :id, 'Ha iniciado sesion', CURRENT_TIMESTAMP);";
        $query = $this->conexion->prepare($sql);
        $query->execute(["id"=>$id_usuario]);
        if($query){
            $resultado=true;
        }else{
            $resultado=false;
        }
        return $resultado;
    }
    public function logEliminarUsuario($id_usuario){
        $sql="INSERT INTO logs (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES (null, :id, 'Ha eliminado un usuario', CURRENT_TIMESTAMP);";
        $query = $this->conexion->prepare($sql);
        $query->execute(["id"=>$id_usuario]);
        if($query){
            $resultado=true;
        }else{
            $resultado=false;
        }
        return $resultado;
    }

// LOGS / ACCIONES    

public function listadoLogs($inicio,$regsxpag)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM logs LIMIT $inicio, $regsxpag"; // calculas rows encontradas
            $resultadoQuery = $this->conexion->query($sql); // standards

            // Para saber el número de páginas que hay
            $totalRegistros = $this->conexion->query("SELECT FOUND_ROWS() as total");
            $totalRegistros = $totalRegistros->fetch()["total"];
            $numeroPaginas = ceil($totalRegistros / $regsxpag);

            if ($resultadoQuery)
            { 
                $return['correcto'] = true; 
                 $return['datos'] = $resultadoQuery->fetchAll(PDO::FETCH_OBJ);
                $return['datosPaginacion'] = $numeroPaginas;
            }
        }
        catch (PDOException $ex)
        { $return['error'] = $ex->getMessage(); }

        return $return;
    }

    public function seleccionarLogs($id)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT * FROM logs WHERE (`id`=:id);";
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['id' => $id]);

            if ($query)
            { 
                $return['correcto'] = true; 
                $return['datos'] = $query->fetch(PDO::FETCH_OBJ);
            }
        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;

    }

// SECCION LIBROS LO QUE SEA

    public function registroLibros($cod_libro,$nombre,$descripcion,$titulo,$autor,$pvp)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
   
        try
        {
            $sql = "INSERT INTO `libros`(`id`, `cod_libro`, `nombre`, `descripcion`, `titulo`, `autor`, `pvp`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])) VALUES (null, :usuario, :password, :email, 'registrado', current_timestamp );";
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['cod_libro' => $cod_libro,'nombre' => $nombre ,'descripcion' => $descripcion,'titulo' => $titulo,'autor' => $autor,'pvp' => $pvp ]);

            if ($query)
            { $return['correcto'] = true; }

        }
        catch (PDOException $ex) {

            $return['error'] = $ex->getMessage();
        }
        return $return;
    }

    public function listadoLibros($inicio,$regsxpag)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "datosPaginacion" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM libros LIMIT $inicio, $regsxpag"; // calculas rows encontradas
            $resultadoQuery = $this->conexion->query($sql); // standards

            // Para saber el número de páginas que hay
            $totalRegistros = $this->conexion->query("SELECT FOUND_ROWS() as total");
            $totalRegistros = $totalRegistros->fetch()["total"];
            $numeroPaginas = ceil($totalRegistros / $regsxpag);

            if ($resultadoQuery)
            { 
                $return['correcto'] = true; 
                $return['datos'] = $resultadoQuery->fetchAll(PDO::FETCH_OBJ);
                $return['datosPaginacion'] = $numeroPaginas;
            }
        }
        catch (PDOException $ex)
        { $return['error'] = $ex->getMessage(); }

        return $return;
    }

    public function seleccionarLibro($id)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "SELECT * FROM libros WHERE (`id`=:id);"; // 2 punto y coma por finalizacion de instruccion
            $query = $this->conexion->prepare($sql); // standards
            $query->execute(['id' => $id]);

            if ($query)
            { 
                $return['correcto'] = true; 
                $return['datos'] = $query->fetch(PDO::FETCH_OBJ);
            }
        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;

    }

    public function edicionLibro($id,$cod_libro, $nombre, $descripcion, $titulo,$autor,$pvp)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];

        try
        {
            $sql = "UPDATE libros SET id=:id, cod_libro=:cod_libro, nombre=:nombre, descripcion=:descripcion, titulo=:titulo, autor=:autor, pvp=:pvp WHERE (`id`=:id);";
            $query1 = $this->conexion->prepare($sql); // standards
            $query1->execute(['id' => $id, 'cod_libro' => $cod_libro, 'nombre' => $nombre, 'descripcion' => $descripcion, 'titulo' => $titulo, 'autor' => $autor , 'pvp' => $pvp]);

           
var_dump($query1);

            if ($query1)
            { 
                $return['correcto'] = true;
            }


        }
        catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        
        return $return;
    }

    public function borrarLibro($id)
    {
        $return = [
            "correcto" => FALSE,
            "datos" => NULL,
            "error" => NULL
        ];
        try {
            $sql = "DELETE FROM libros WHERE id=:id";
            $query = $this->conexion->prepare($sql);
            $query->execute(['id' => $id]);
            if ($query) {
                $return["correcto"] = TRUE;
            }
        } catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
        }
        return $return;
    }

// llamar al editar
    public function logEditarLibro($id_usuario){
        $sql="INSERT INTO logs (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES (null, :id, 'Ha editado un libro', CURRENT_TIMESTAMP);";
        $query = $this->conexion->prepare($sql);
        $query->execute(["id"=>$id_usuario]);
        if($query){
            $resultado=true;
        }else{
            $resultado=false;
        }
        return $resultado;
    }

    // llamar al borrar
    public function logEliminarLibro($id_usuario){
        $sql="INSERT INTO logs (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES (null, :id, 'Ha eliminado un Libro', CURRENT_TIMESTAMP);";
        $query = $this->conexion->prepare($sql);
        $query->execute(["id"=>$id_usuario]);
        if($query){
            $resultado=true;
        }else{
            $resultado=false;
        }
        return $resultado;
    }

// SECCION GENERACION PDF
    public function listadoPdf($tipo)
    {
        try {
            if ($tipo == "1") {
                $sql = "SELECT * FROM usuarios WHERE 1";
            }
            $query = $this->conexion->query($sql);

            //Supervisamos si la consulta se hizo correctamente
            if (isset($query)) {
                $listado = $query->fetchAll(PDO::FETCH_ASSOC);
                $contenido = "";
                $contenido .= '<h1>Listado de xXx</h1><br>';

                $contenido .= '<table cellspacing="1" border="1">';
                $contenido .= '<tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha</th>
            </tr>';
                foreach ($listado as $u)
                {
                $contenido .= '<tr class="table-info">
                <td>' . $u["usuario"] . '</td>
                <td>' . $u["email"] . '</td>
                <td>' . $u["rol"] . '</td>
                <td>' . $u["fecha"] . '</td>';
                $contenido .= '</tr>';
                }
                $contenido .= '</table>';
            } else
                $contenido = false;
        } catch (PDOException $ex) {
            $contenido = $ex->getMessage();
        }
        return $contenido;
    }
}
?>