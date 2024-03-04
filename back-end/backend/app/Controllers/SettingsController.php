<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

  
class SettingsController extends BaseController
{
    use ResponseTrait;

    public function getTrackerConfig()
    {
        $data = [
            'settings_link'         => 'https://placehold.co/128x128?font=roboto',
            'tracker'               => [
                'activity_types'    => [
                    1 => 'Development',
                    2 => 'Meeting',
                    3 => 'Admin',
                ]
            ],
            'action_urls'           => [
                'send_tracked_activity' => [
                    'method'    => 'POST',
                    'endpoint'  => 'https://placehold.co'
                ],
                'check_version_update'  => [
                    'method'    => 'GET',
                    'endpoint'  => 'https://placehold.co'
                ], 
            ]
        ];
        return $this->response->setJSON($data);
    }
}
