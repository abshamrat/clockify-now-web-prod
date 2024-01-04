<?php 
namespace App\Controllers;
 
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;

  
class TrackersController extends BaseController
{
    use ResponseTrait;

    protected $helpers = ['form'];

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

        $img = $this->request->getFile('screenshot');

        if (! $img->hasMoved()) {
            $filepath = WRITEPATH . 'uploads/' . $img->store();

            $data = ['uploaded_fileinfo' => $filepath];

            return $this->response->setJSON($data);
        }

        $data = ['errors' => 'The file has already been moved.'];

        return $this->response->setJSON($data);
    }
}
