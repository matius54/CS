<?php
    require_once "db.php";
    require_once "utils.php";
    require_once "paginator.php";

    //modelo (la clase)
    class Profesor {
        private static string $sessionKey = "user";
        private static string $select = "SELECT username, nombre AS firstname, apellidos AS lastname, cedula AS ci, rif, fecha_nacimiento AS birthdate, telefono AS phone, correo AS email, direccion AS address, estado AS state, ciudad AS city from profesores AS p LEFT JOIN datos_personales AS dp ON personales_id = dp.id LEFT JOIN datos_de_contacto AS dc ON contacto_id = dc.id LEFT JOIN datos_de_direccion AS dd ON direccion_id = dd.id";
        private static array $fields = [
            "firstname", 
            "lastname", 
            "ci", 
            "rif", 
            "birthdate", 
            "phone", 
            "email", 
            "address",
            "state",
            "city"
        ];
        //profesores
        private ?int $id = null;

        //datos_personales
        private ?string $firstname = null;
        private ?string $lastname = null;
        private ?string $ci = null;
        private ?string $rif = null;
        private ?string $birthdate = null;

        //datos_de_contacto
        private ?string $phone = null;
        private ?string $email = null;

        //datos_de_direccion
        private ?string $address = null;
        private ?string $state = null;
        private ?string $city = null;

        public function __construct(string $serializedData = null) {
            if($serializedData){
                $this->setData(json_decode($serializedData, true));
            }
        }

        public function setData(array $data){
            foreach (self::$fields as $field) {
                $fdata = $data[$field] ?? null;
                $this->$field = VALIDATE::$field($fdata) ? $fdata : null;
            }
        }
        public function load(){
            if(!isset($_SESSION)) session_start();
            if($id = $_SESSION[self::$sessionKey] ?? "") return self::getById($id);
        }
        public function save(){
            if(!isset($_SESSION)) session_start();
            if(!($id = $_SESSION[self::$sessionKey] ?? "")) throw new Error("inicia sesion primero");
            $datos_de_direccion = "INSERT INTO datos_de_direccion (direccion, estado, ciudad) VALUES (?, ?, ?)";
            $datos_de_contacto = "INSERT INTO datos_de_contacto (telefono, correo) VALUES (?, ?)";
            $datos_personales = "INSERT INTO datos_personales (direccion_id, contacto_id, nombre, apellidos, cedula, rif, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $profesores = "UPDATE profesores SET personales_id = ? WHERE id = ?";
            $db = DB::getInstance();
            $db->execute("SELECT personales_id FROM profesores WHERE id = ?", $id);
            //si ya tiene datos personlaes relacionados
            if($personales_id = $db->fetch()["personales_id"] ?? "") return $this->update($personales_id);
            $db->beginTransaction();
            $db->execute($datos_de_direccion, $this->address, $this->state, $this->city);
            if($db->rowCount()){
                $direccion_id = $db->getLastId("datos_de_direccion");
                $db->execute($datos_de_contacto, $this->phone, $this->email);
                if($db->rowCount()){
                    $contacto_id = $db->getLastId("datos_de_contacto");
                    $db->execute($datos_personales, $direccion_id, $contacto_id, $this->firstname, $this->lastname, $this->ci, $this->rif, $this->birthdate);
                    if($db->rowCount()){
                        $personales_id = $db->getLastId("datos_personales");
                        $db->execute($profesores, $personales_id, $id);
                        if($db->rowCount()){
                            $db->commit();
                            return;
                        }
                    }
                }
            }
            $db->rollback();
        }
        private function update(int $personales_id){
            $datos_de_direccion = "UPDATE datos_de_direccion SET direccion = ?, estado = ?, ciudad = ? WHERE id = ?";
            $datos_de_contacto = "UPDATE datos_de_contacto SET telefono = ?, correo = ? WHERE id = ?";
            $datos_personales = "UPDATE datos_personales SET nombre = ?, apellidos = ?, cedula = ?, rif = ?, fecha_nacimiento = ? WHERE id = ?";
            $db = DB::getInstance();
            $db->beginTransaction();
            $db->execute("SELECT direccion_id, contacto_id FROM datos_personales WHERE id = ?", $personales_id);
            $rowsAffected = 0;
            if($result = $db->fetch()){
                $direccion_id = $result["direccion_id"];
                $contacto_id = $result["contacto_id"];
                $db->execute($datos_de_direccion, $this->address, $this->state, $this->city, $direccion_id);
                $rowsAffected += $db->rowCount();
                $db->execute($datos_de_contacto, $this->phone, $this->email, $contacto_id);
                $rowsAffected += $db->rowCount();
                $db->execute($datos_personales, $this->firstname, $this->lastname, $this->ci, $this->rif, $this->birthdate, $personales_id);
                $rowsAffected += $db->rowCount();
                if($rowsAffected){
                    $db->commit();
                    return;
                }
            }
            $db->rollback();
        }
        public static function register(array $data) {
            $profesores = "INSERT INTO profesores (username, hash, salt) VALUES (?, UNHEX(?), UNHEX(?))";
            
            $username = $data["username"];
            $password = $data["password"];
            if(!VALIDATE::username($username) or !VALIDATE::password($password)) return null;
            $hash = null;
            $salt = null;
            SC::password($password, $hash, $salt);
            $db = DB::getInstance();
            $db->beginTransaction();
            $db->execute($profesores, $username, $hash, $salt);
            if($db->rowCount()){
                $db->commit();
                return $db->getLastId("profesores");
            }else{
                $db->rollback();
                return null;
            }
        }
        public static function getById(int $id) : null|Profesor {
            if(!VALIDATE::id($id)) return null;
            $db = DB::getInstance();
            $db->execute(self::$select . " WHERE p.id = ?", $id);
            if(!($data = $db->fetch())){
                var_dump($data);
                return null;
            }
            $profesor = new Profesor;
            $profesor->setData($data);
            $profesor->setId($id);
            return $profesor;
        }
        public static function getAll() : Paginator {
            return new Paginator(self::$select);
        }
        private function setId(int $id){
            $this->id = $id;
        }
        public static function login(array $data) : int {
            if(!isset($_SESSION)) session_start();
            if($_SESSION[self::$sessionKey] ?? "") throw new Error("La sesion ya esta iniciada");

            $username = $data["username"];
            $password = $data["password"];
            if(!VALIDATE::username($username) or !VALIDATE::password($password)) return null;

            $db = DB::getInstance();
            $db->execute("SELECT HEX(salt) AS salt FROM profesores WHERE username = ?", $username);
            $response = $db->fetch();
            if(!$response)throw new Error("Usuario no encontrado");

            $salt = $response["salt"];
            $hash = null;
            SC::password($password, $hash, $salt);

            $db->execute("SELECT id FROM profesores WHERE hash = UNHEX(?)", $hash);
            $response = $db->fetch();
            if(isset($response["id"])){
                $id = $response["id"];
                $_SESSION[self::$sessionKey] = $id;
                return $id;
            }
            throw new Error("Usuario y contraseña no coinciden");
        }
        public static function logout(){
            if(!isset($_SESSION)) session_start();
            if($_SESSION[self::$sessionKey] ?? ""){
                unset($_SESSION[self::$sessionKey]);
            }else{
                throw new Error("La sesion ya esta cerrada");
            }
        }
        public function __toString() : string {
            $arr = [];
            if($this->id) $arr["id"] = $this->id;
            foreach (self::$fields as $field) {
                $arr[$field] = $this->$field;
            }
            return json_encode($arr);
        }
    }
    
    //controlador
    $action = URL::decode("action",$_GET);
    $data = URL::decodeAll($_POST);
    try{
        switch($action){
            case "login":
                Profesor::login($data);
            break;
            case "logout":
                Profesor::logout();
            break;
            case "register":
                var_dump(Profesor::register($data));
                var_dump($data);
            break;
            case "unregister":
    
            break;
            case "setdata":
                $profesor = new Profesor;
                $profesor->setData($data);
                $profesor->save();
            break;
        }
        if($action === "paginate"){
            $profesors = Profesor::getAll();
            JSON::sendJson($profesors->toArray());
        }else{
            URL::redirect("../");
        }
        
    }catch(Error $e){
        echo $e->getMessage();
        echo "<br><a href=\"../\">volver</a>";
    }
?>