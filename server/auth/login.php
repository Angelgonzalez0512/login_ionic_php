<?php
require_once("../config/database.php");
require_once("../config/utils.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers:*");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] = "POST") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "login") {
            $input_data = json_decode(file_get_contents("php://input"));
            if (isset($input_data->correo) && isset($input_data->password)) {
                $objlog = new Login();
                $response = $objlog->loginWithToken($input_data->correo, $input_data->password);
                echo json_encode($response);
            } else {

                echo json_encode(["success" => false, "message" => "Entrada invalida"]);
            }
        } else {
            
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = $headers['Authorization'];
                $objlog = new Login();
                $response2 = $objlog->autenticated($headers["Authorization"]);
                echo json_encode($response2);
            } else {
                echo json_encode(["success" => false, "message" => "No token"]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Entrada invalida"]);
    }
}
class Login
{
    public function iniciar_session($user, $passwor)
    {
        try {
            $con = Conex::getInstance();
            $sql = "SELECT *FROM tb_usuarios
            WHERE usuario=:email and USUARIO=:email ";
            $rs = $con->db->prepare($sql);
            $rs->bindValue(':email', $user);
            $rs->bindValue(':dni', $passwor);
            $rs->execute();
            $salida = $rs->fetchAll(PDO::FETCH_ASSOC);
            $response = null;
            if (count($salida) ? $response = $salida[0] : $response = null);
            return $response;
        } catch (Exception $ex) {
            return null;
        }
    }
    public function get_user($id)
    {
        try {
            $con = Conex::getInstance();
            $sql = "SELECT *FROM tb_usuarios
            WHERE usuario=:id ";
            $rs = $con->db->prepare($sql);
            $rs->bindValue(':id', $id);
            $rs->execute();
            $salida = $rs->fetchAll(PDO::FETCH_ASSOC);
            $response = null;
            if (count($salida) ? $response = $salida[0] : $response = null);
            return $response;
        } catch (Exception $ex) {
            return null;
        }
    }

    public function autenticated($jwt)
    {
        try {
            $secret = Utils::$SECRET_KEY_IONIC;
            if ($jwt) {
                $tokenParts = explode('.', $jwt);
                $header = base64_decode($tokenParts[0]);
                $payload = base64_decode($tokenParts[1]);
                $signatureProvided = $tokenParts[2];
                $expiration = json_decode($payload)->exp;
                $base64UrlHeader = base64_encode($header);
                $base64UrlPayload = base64_encode($payload);
                $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
                $base64UrlSignature = base64_encode($signature);
                $signatureValid = ($base64UrlSignature === $signatureProvided);
                $header = json_decode($header);
                if ($signatureValid) {
                    $xtiempoactual = time();
                    if ($expiration > $xtiempoactual) {
                        $payload = json_decode($payload);
                        $user = $this->get_user($payload->user_id);
                        return ["success" => true, "data" =>  $user, "message" => "Bienvenido"];
                    } else {
                        return ["success" => false, "message" => "Su token a caducado"];
                    }
                }
            } else {
                return ["success" => false, "message" => "No se envio un token"];
            }
            return ["success" => false, "message" => "Token no valido"];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
    public function loginWithToken($usurio, $correo)
    {
        try {
            $secret = Utils::$SECRET_KEY_IONIC;
            $durationtoken = Utils::$TIMESTAMP_TOKEN_IONIC;
            $header = json_encode([
                'typ' => 'JWT',
                'alg' => 'HS256'
            ]);
            $usuarioatenticar = $this->iniciar_session($usurio, $correo);
            $tiempovencimiento = time() + $durationtoken;
            if ($usuarioatenticar) {
                $payload = json_encode([
                    'user_id' => $usuarioatenticar["usuario"],
                    'exp' => $tiempovencimiento
                ]);
                $base64UrlHeader = base64_encode($header);
                $base64UrlPayload = base64_encode($payload);
                $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
                $base64UrlSignature = base64_encode($signature);
                $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
                return ["jwt" => $jwt, "success" => true, "data" => $usuarioatenticar];
            } else {
                return ["success" => false, "message" => "Usuario incorrecto"];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}
