<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

  
class LeaveController extends BaseController
{
    use ResponseTrait;

    function __construct(){
        $this->db = db_connect();
    }

    public function addLeaveRequest()
    {
        $leave = [
            'user_id' => $this->request->auth_user->id,
            'organization_id' => $this->request->auth_user->organization_id,
            'leave_type' => $this->request->getVar('typeOfLeave') ?: 1,
            'reason' => $this->request->getVar('reason') ?: '',
            'from_date' => $this->request->getVar('leaveFrom') ?: 0,
            'to_date' => $this->request->getVar('leaveTo') ?: 0,
        ];
        $this->db->table('user_leaves')->insert($leave);
        return $this->response->setJSON($leave);
    }

    public function getLeaveRequest()
    {   
        $user_id = $this->request->auth_user->id;
        $month = $this->request->getVar('month') ?: date('F');
        $sql_leave_summary = "SELECT *
            FROM user_leaves el 
            WHERE el.user_id = ?
            AND MONTH(el.created_at) = ?";

        $result = $this->db->query($sql_leave_summary, array($user_id, $month));

        return $this->response->setJSON(array(
            "success" => true,
            "data" => $result->getResult('array')
        ));
    }

    public function getOrgLeaveSettings()
    {   
        $organization_id = $this->request->auth_user->organization_id;
        $sql = "SELECT * FROM organization_leave_settings WHERE organization_id = ?";

        $result = $this->db->query($sql, array($organization_id));

        return $this->response->setJSON(array(
            "success" => true,
            "data" => $result->getResult('array')
        ));
    }

}
