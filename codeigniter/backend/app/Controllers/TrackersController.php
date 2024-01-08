<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;

// require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class TrackersController extends BaseController
{
    use ResponseTrait;

    protected $helpers = ['form'];

    function __construct() {
        $this->db = db_connect();   
    }

    public function track()
    {
        $validationRule = [
            'screenshot' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[screenshot]',
                    'is_image[screenshot]',
                    'mime_in[screenshot,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[screenshot,5088]',
                    // 'max_dims[screenshot,1024,768]',
                ],
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];

            return $this->response->setJSON($data);
        }

        $tracked_data = [
            'user_id' => $this->request->auth_user->id,
            'mouse_click' => $this->request->getVar('mouse_click') ?: 0,
            'mouse_scroll' => $this->request->getVar('mouse_scroll') ?: 0,
            'keyboard_activities' => $this->request->getVar('keyboard_activity') ?: 0,
            'activity_per_slot' => $this->request->getVar('active_min') ?: 0,
            'activity_slot' => $this->request->getVar('active_min') ?: 0,
            'activity_id' => $this->request->getVar('activity_id') ?: 0,
            'timestamp' => $this->request->getVar('timestamp'),
            'memo' => $this->request->getVar('memo') ?: '',
        ];

        $img = $this->request->getFile('screenshot');
        // $mouse_click = $this->request->getVar('mouse_click');
        // $mouse_scroll = $this->request->getVar('mouse_scroll');
        // $keyboard_activity = $this->request->getVar('keyboard_activity');
        // $activity_per_slot = $this->request->getVar('active_min');
        // $activity_slot = $this->request->getVar('out_of_min');
        // $activity_id = $this->request->getVar('activity_id');
        // $timestamp = $this->request->getVar('timestamp');
        // $memo = $this->request->getVar('memo');

        $file_name = "none.png";



        if (! $img->hasMoved()) {
            $filepath = WRITEPATH . 'uploads/' . $img->store();

            $file_name_array = explode("/", $filepath);
            $file_name = "tracker/".$file_name_array[count($file_name_array) - 1];
            $data = [
                'uploaded_fileinfo' => $filepath,
            ];
            try {
                $s3Client = new S3Client([
                    "credentials" => [
                        "key" => "cqRfLgY656Lo7CZs",
                        "secret" => "ZItqeNkAhrO7UjSV2PX7oKf1rgnnFNMoW6Vv1q3I"
                    ],
                    "endpoint" => "https://s3.tebi.io",
                    "region" => "de",
                    "version" => "2006-03-01"
                ]);
                $result = $s3Client->putObject([
                    'Bucket' => "clockify",
                    'Key' => $file_name,
                    'SourceFile' => $filepath,
                ]);
                $data = [
                    'uploaded_fileinfo' => $filepath,
                    "effectiveUri" => $result["@metadata"]["effectiveUri"]
                ];
                $tracked_data["screenshot_link"] = $result["@metadata"]["effectiveUri"];
                $this->db->table('user_activities')->insert($tracked_data);
            } catch (\Exception $ex) {
                $data = [
                    'uploaded_fileinfo' => $filepath,
                    "ex" => $ex->getMessage()
                ];
            }

            return $this->response->setJSON($data);
        }

        $data = ['errors' => 'The file has already been moved.'];

        return $this->response->setJSON($data);
    }
}
