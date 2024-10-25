<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;

// require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class TimesheetController extends BaseController
{
    use ResponseTrait;

    protected $helpers = ['form'];

    function __construct() {
        $this->db = db_connect();   
    }

    public function getActivityTypes() {
        $sql = "SELECT id, name FROM activity_types WHERE organization_id = ?;";
        $result = $this->db->query($sql, array($this->request->auth_user->organization_id));
        $data = array(
            'success' => true,
            'data' => $result->getResult('array'),
        );

        return $this->respond($data);
    }

    public function addManualTime() {
        $data = [
            "success" => true,
            "message" => "Files successfully uploaded."
        ];
        // Define validation rules
        $validationRule = [
            'attachments' => [
                'label' => 'File',
                'rules' => [
                    'uploaded[attachments]',
                    'mime_in[attachments,image/jpg,image/jpeg,image/gif,image/png,image/webp,application/pdf]',
                    'max_size[attachments,5088]',
                ],
            ],
        ];

        $manual_time_data = [
            'user_id' => $this->request->auth_user->id,
            'date' => $this->request->getVar('date') ?: 0,
            'activity_id' => $this->request->getVar('activity_id') ?: 0,
            'start_time' => $this->request->getVar('start_time') ?: 0,
            'end_time' => $this->request->getVar('end_time') ?: 0,
            'memo' => $this->request->getVar('memo') ?: '',
            'attachment_links' => '' //will be added
        ];

        // Validate each file in the array
        $files = $this->request->getFiles();

        if ($files) {
            foreach ($files['attachments'] as $file) {
                if (!$file->isValid()) {

                    $data["success"] = false;
                    $data["message"] =  $file->getErrorString();
                    return $this->response->setJSON($data);
                }

                if (!$file->hasMoved()) {
                    // Perform file validation based on rules
                    if (!$this->validate($validationRule)) {
                        $data["success"] = false;
                        $data["message"] =  $this->validator->getErrors();
                        return $this->response->setJSON($data);
                    }

                    // Move file to desired location if validation passed
                    $file->move(WRITEPATH . 'uploads');
                    $filepath = WRITEPATH . 'uploads/' . $file->store();
                    $file_name_array = explode("/", $filepath);
                    $file_name = "tracker/attachments/".$file_name_array[count($file_name_array) - 1];
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
                        unlink($filepath);
                       
                    } catch (\Exception $ex) {
                        $data = [
                            'uploaded_fileinfo' => $filepath,
                            "ex" => $ex->getMessage()
                        ];
                    }
                }
            }

            return $this->response->setJSON($data);
        }
        $data["success"] = false;
        $data["message"] = 'No files were uploaded.';
        return $this->response->setJSON($data);
    }
}
