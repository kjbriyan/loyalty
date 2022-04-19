<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;
use App\Models\RegisModel;

class Auth extends ResourceController
{
    use ResponseTrait;
    // get all user
    public function index()
    {
        $model = new RegisModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    //login

    public function auth()
    {
        $uname = $this->request->getPost('email');
        $passwd = $this->request->getPost('pass');
        $model = new RegisModel();
        $data = $model->getWhere(['email' => $uname, 'password' => $passwd])->getResult();

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => 'ok',
            'data' => $data
        ];
        if ($data) {
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id ' . $uname);
        }
    }

    // get single user
    public function show($id = null)
    {
        $model = new RegisModel();
        $data = $model->getWhere(['id_user' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    // create a user
 public function create()
    {
        $model = new RegisModel();
        $data = $model->find($this->request->getPost('email'));
        if ($data) {
            $response = [
                'status'   => 404,
                'error'    => "eror",
                'messages' => [
                    'success' => 'Username terpakai'. $data
                ]
            ];
            return $this->respond($response);
        } else {
            $data = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'level' => '2'
            ];
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Saved'
                ]
            ];
            return $this->respondCreated($response);
        }
    }


    // update user
    public function update($id = null)
    {
        $model = new ProductModel();
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'name' => $json->name,
                'alamat' => $json->alamat
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['name'],
                'alamat' => $input['alamat']
            ];
        }
        // Insert to Database
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    // delete user
    public function delete($id = null)
    {
        $model = new ProductModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ' . $data);
        }
    }
}
