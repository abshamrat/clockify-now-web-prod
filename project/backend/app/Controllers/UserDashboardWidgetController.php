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

    public function summary() {
        $user_id = $this->request->auth_user->id;
        $todays_work_summary = $this->request->getVar('today') == '1';
        $weekly_work_summary = $this->request->getVar('weekly') == '1';
        $leave_summary = $this->request->getVar('leave') == '1';

        $data = array();

        if($todays_work_summary) {
            $data['todays_work_summary'] = $this->todaysWorkSummary($user_id);
        }

        if($weekly_work_summary) {
            $data['weekly_work_summary'] = $this->weeklyWorkSummary($user_id);
        }

        if($leave_summary) {
            $data['leave_summary'] = $this->leaveSummary($user_id);
        }

        $response = array(
            'success' => true,
            'data'    => $data,
        );

        return $this->respond($response);
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
            'start_time' => $start_time,
            'end_time' => $end_time,
            'work_hour' => $work_hour,
            'per_day_work_limit' => $per_day_work_limit,
        );

        return $data;
    }

    public function weeklyWorkSummary($user_id)
    {
        $sql_weekly_work = "SELECT date(created_at) day_date, SUM(ua.activity_slot) total_activity  FROM user_activities ua 
                WHERE created_at >= CURRENT_DATE - 7
                AND user_id = ?
                GROUP BY date(created_at)";

        $result_weekly = $this->db->query($sql_weekly_work, array($user_id));

        return $result_weekly->getResult('array');
    }

    public function leaveSummary($user_id)
    {
        $sql_leave_summary = "SELECT 
                                MAX(created_at) as last_leave_taken_at
                                , (SELECT COUNT(id) FROM employee_leaves el2 WHERE el2.user_id = 2 and el2.leave_type = 'absent') as total_absent
                                , (SELECT COUNT(id) FROM employee_leaves el2 WHERE el2.user_id = 2 and el2.leave_type <> 'absent' AND el2.status = 'pending') as total_pending
                            FROM employee_leaves el 
                            WHERE el.user_id = 2
                            LIMIT 1";

        $result = $this->db->query($sql_leave_summary, array($user_id));
        $result = $result->getResult('array');

        if (count($result) > 0) {
            $result = $result[0];
        }

        return $result;
    }

    public function userLastActivityLogs($user_id)
    {
        $sql_activity_logs = "SELECT activity_id, memo, min(created_at) start_time, max(created_at) end_time, SUM(activity_slot) total_hour_worked  FROM user_activities ua
                            WHERE ua.user_id = ?
                            GROUP BY activity_id, memo
                            LIMIT 10";

        $result = $this->db->query($sql_activity_logs, array($user_id));

        $data = array(
            'success' => true,
            'data' => $result->getResult('array'),
        );

        return $this->respond($data);
    }
}
