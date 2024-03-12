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
        $sql_todays_work = "SELECT 
                    created_at as start_time
                    , (SELECT MAX(created_at) FROM user_activities ua2 WHERE ua2.user_id = ? AND created_at >= CURRENT_DATE) as end_time
                    , (SELECT SUM(ua2.activity_slot) FROM user_activities ua2 WHERE ua2.user_id = ? AND created_at >= CURRENT_DATE) as work_hour
                FROM user_activities ua WHERE 
                    created_at >= CURRENT_DATE
                AND ua.user_id = ?
                LIMIT 1";

        $sql_weekly_work = "SELECT date(created_at) day_date, SUM(ua.activity_slot) total_activity  FROM user_activities ua 
                WHERE created_at >= CURRENT_DATE - 7
                AND user_id = ?
                GROUP BY date(created_at)";

        $result_weekly = $this->db->query($sql_weekly_work, array($user_id));
        $result_todays_work = $this->db->query($sql_todays_work, array($user_id, $user_id, $user_id));

        $result_todays_work = $result_todays_work->getResult('array');
        $start_time = null;
        $end_time = null;
        $work_hour = null;
        $per_day_work_limit = 8;

        if (count($result_todays_work) > 0) {
            $start_time = $result_todays_work[0]['start_time'];
            $end_time = $result_todays_work[0]['end_time'];
            $work_hour = $result_todays_work[0]['work_hour'];
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
