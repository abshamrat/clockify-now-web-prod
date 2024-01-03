<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

  
class UsersController extends BaseController
{
    use ResponseTrait;

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
        $data = [
            'user_profile' => [
                'id'                    => 123,
                'user_full_name'        => 'Abu Bakar Siddique',
                'designation'           => 'Engineering Manager',
                'profile_image_url'     => 'https://placehold.co/128x128?font=roboto'
            ],
            'settings_link'         => 'https://placehold.co/128x128?font=roboto',
            'tracker'               => [
                'activity_types'    => [
                    1 => 'Development',
                    2 => 'Meeting',
                    3 => 'Admin',
                ]
            ],
            'app_version'           => "1.0"
        ];
        return $this->response->setJSON($data);
    }

    public function register() 
    {
        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];
            
  
        if($this->validate($rules)){
            $model = new UserModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role_id' => 1
            ];
            $model->save($data);
             
            return $this->respond(['message' => 'Registered Successfully'], 200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->respond(['message' => 'Failed'], 409);
        }
    }
}
