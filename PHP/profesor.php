<?php
    require_once "db.php";
    require_once "utils.php";
    require_once "paginator.php";
    class Profesor {
        private static string $sessionKey = "user";
        private static string $select = "SELECT username, nombre AS firstname, apellidos AS lastname, cedula AS ci, rif, fecha_nacimiento AS birthdate, telefono AS phone, correo AS email, direccion AS address, estado AS state, ciudad AS city from profesores AS p JOIN datos_personales AS dp ON personales_id = dp.id JOIN datos_de_contacto AS dc ON contacto_id = dc.id JOIN datos_de_direccion AS dd ON direccion_id = dd.id";
        private static array $fields = [
            "username",
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
        private ?string $username;
        
        //datos_personales
        private ?string $firstname;
        private ?string $lastname;
        private ?string $ci;
        private ?string $rif;
        private ?string $birthdate;

        //datos_de_contacto
        private ?string $phone;
        private ?string $email;

        //datos_de_direccion
        private ?string $address;
        private ?string $state;
        private ?string $city;

        public function __construct(array $data) {
            foreach (self::$fields as $field) {
                $fdata = $data[$field] ?? null;
                $this->$field = VALIDATE::$field($fdata) ? $fdata : null;
            }
        }
        public function save(){
            $datos_de_direccion = "INSERT INTO datos_de_direccion (direccion, estado, ciudad) VALUES (?, ?, ?)";
            $datos_de_contacto = "INSERT INTO datos_de_contacto (telefono, correo) VALUES (?, ?)";
            $datos_personales = "INSERT INTO datos_personales (direccion_id, contacto_id, nombre, apellidos, cedula, rif, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $db = DB::getInstance();
            $db->beginTransaction();
            //$db->execute();
            $db->commit();
        }
        public function new(string $username, string $password){
            $profesores = "INSERT INTO profesores (username, hash, salt) VALUES (?, UNHEX(?), UNHEX(?))";
            if(!VALIDATE::username($username) or !VALIDATE::password($password)) return;
            $hash = null;
            $salt = null;
            SC::password($password, $hash, $salt);
            $db = DB::getInstance();
            $db->beginTransaction();
            $db->execute($profesores, $username, $hash, $salt);
            if($db->rowCount()){
                $db->execute("SELECT id FROM profesores ORDER BY id DESC LIMIT 1 OFFSET 0");
                $this->id = $db->fetch()["id"];
                $db->commit();
            }else{
                $db->rollback();
            }
        }
        public static function getById(int $id) : null|Profesor {
            if(!VALIDATE::id($id)) return null;
            $db = DB::getInstance();
            $db->execute(self::$select . " WHERE p.id = ?", $id);
            if(!($data = $db->fetch())) return null;
            $profesor = new Profesor($data);
            $profesor->setId($id);
            return $profesor;
        }
        public static function getAll() : Paginator {
            return new Paginator(self::$select);
        }
        private function setId(int $id){
            $this->id = $id;
        }
        private function setUsername(string $username){
            $this->username = $username;
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
            if(!$_SESSION[self::$sessionKey] ?? "") throw new Error("La sesion ya esta cerrada");
            unset($_SESSION[self::$sessionKey]);
        }
        public function serialize() : string {
            $arr = [];
            if($this->id) $arr["id"] = $this->id;
            foreach (self::$fields as $field) {
                $arr[$field] = $this->$field;
            }
            return json_encode($arr);
        }
    }
    $aaa = Profesor::getById(1);
    echo $aaa->serialize();
    $pag = $aaa->getAll();
    echo HTML::matrix2table($pag->items);
    echo $pag->navigator;
    Profesor::login(["username"=>"MatiusB","password"=>"aaaa"]);
?>