<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;

class AuthController extends BaseController
{
    use ResponseTrait;

    function __construct() {
        $this->db = db_connect();   
    }

    public function authenticate()
    {
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if(is_null($user)) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        $pwd_verify = password_verify($password, $user['password']);

        if(!$pwd_verify) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }

        $key = getenv('JWT_SECRET', 'clock1234');
        $iat = time(); // current timestamp value
        $exp = $iat + 365 * 24 * 3600;
 
        $payload = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $user['email'],
            "id" => $user['id'],
            "organization_id" => $user['organization_id'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $sql = "SELECT up.*, d.name as designation_name FROM user_profiles up 
        INNER JOIN designations d ON d.id = up.designation_id 
        WHERE up.user_id = ? LIMIT 1";

        $user_profile = $this->db->query($sql, array($user['id']));

        $response = [
            'message' => 'Login successful!',
            'token' => $token,
            'profile' => $user_profile->getResult('array')[0]
        ];

        return $this->respond($response, 200);
    }
}