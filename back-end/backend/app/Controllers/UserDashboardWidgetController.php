<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;


class UserDashboardWidgetController extends BaseController
{
    use ResponseTrait;

    function __construct(){
        $this->db = db_connect();
    }

    public function todaysWorkSummary($user_id)
    {
        $sql = "SELECT 
                    created_at as start_time
                    , (SELECT MAX(created_at) FROM user_activities ua2 WHERE ua2.user_id = ? AND created_at >= CURRENT_DATE) as end_time
                    , (SELECT SUM(ua2.activity_slot) FROM user_activities ua2 WHERE ua2.user_id = ? AND created_at >= CURRENT_DATE) as work_hour
                FROM user_activities ua WHERE 
                    created_at >= CURRENT_DATE
                AND ua.user_id = ?
                LIMIT 1";

        $result = $this->db->query($sql, array($user_id, $user_id, $user_id));
        $result = $result->getResult('array');
        $start_time = null;
        $end_time = null;
        $work_hour = null;
        $per_day_work_limit = 8;

        if (count($result) > 0) {
            $start_time = $result[0]['start_time'];
            $end_time = $result[0]['end_time'];
            $work_hour = $result[0]['work_hour'];
        }

        $data = array(
            'success' => true,
            'data' => array(
                'start_time' => $start_time,
                'end_time' => $end_time,
                'work_hour' => $work_hour,
                'per_day_work_limit' => $per_day_work_limit,
            ),
        );
        return $this->respond($data);
    }
}
