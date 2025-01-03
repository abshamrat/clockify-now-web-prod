<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

  
class UsersController extends BaseController
{
    use ResponseTrait;

    function __construct(){
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'data' => 'value1',
            'data2' => 'value2',
        ];

        return $this->respond($data);
    }

    public function userProfile()
    {
        $data = [
            'id'                    => 123,
            'user_full_name'        => 'Abu Bakar Siddique',
            'designation'           => 'Engineering Manager',
            'profile_image_url'     => 'https://placehold.co/128x128?font=roboto',
        ];
        return $this->response->setJSON($data);
    }

    public function getUserTrackerConfig()
    {
        $user_profile = $this->db->query("
            SELECT
                up.user_id as id 
                , CONCAT(up.first_name,' ' ,up.last_name) as user_full_name
                , d.name as designation
                , up.profile_image_link
            FROM user_profiles up
            INNER JOIN designations d ON d.id = up.designation_id
            WHERE up.user_id = ? LIMIT 1;", [
                $this->request->auth_user->id
            ])->getRow();

        $user_this_week_worked = $this->db->query("
            SELECT IFNULL(SUM(activity_slot),0) as total_mins_this_week 
            FROM user_activities 
            WHERE week(timestamp)=week(now()) AND
            user_id = ?;", [
                $this->request->auth_user->id
            ])->getRow();

        $user_today_worked = $this->db->query("
            SELECT IFNULL(SUM(activity_slot),0) as total_mins_today 
            FROM user_activities 
            WHERE DATE(`timestamp`) = DATE(UTC_TIMESTAMP()) AND
            user_id = ?;", [
                $this->request->auth_user->id
            ])->getRow();

        $data = [
            'user_profile' => $user_profile,
            'settings_link'         => 'https://placehold.co/128x128?font=roboto',
            'tracker'               => [
                'activity_types'    => [
                    1 => 'Development',
                    2 => 'Meeting',
                    3 => 'R&D',
                    4 => 'Admin',
                ],
                "worked" => [
                    "today" => $user_today_worked->total_mins_today,
                    "this_week" => $user_this_week_worked->total_mins_this_week,
                ]
            ],
            'app_version'           => "1.0",
        ];

        return $this->response->setJSON($data);
    }

    public function register() 
    {
        $rules = [
            'first_name' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'last_name' => ['rules' => 'required|min_length[4]|max_length[255]'],
            'designation_id' => ['rules' => 'required|min_length[1]|max_length[3]'],
            'organization_id' => ['rules' => 'required|min_length[1]|max_length[3]'],
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];

        if($this->validate($rules)){
            // $model = new UserModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                "organization_id" => $this->request->getVar('organization_id'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role_id' => 1
            ];
            // var_dump($data);
            // $model->save($data);

            // $this->db->transBegin();
            $this->db->transException(true)->transStart();

            $this->db->table('users')->insert($data);
            $user_id = $this->db->insertID();
            
            $data = [
                "first_name" => $this->request->getVar('first_name'),
                "last_name" => $this->request->getVar('last_name'),
                "designation_id" => $this->request->getVar('designation_id'),
                "user_id" => $user_id,
            ];

            $this->db->table('user_profiles')->insert($data);
            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                log_message("error", "what is the error man!");
            }

            return $this->respond(['message' => 'Registered Successfully', "user_id" => $user_id], 200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->respond(['message' => 'Failed'], 409);
        }
    }
}
