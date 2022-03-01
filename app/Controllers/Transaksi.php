<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TransaksiModel;

class Transaksi extends ResourceController
{
    use ResponseTrait;
    // get all Transaksi
    public function index()
    {

        $model = new TransaksiModel();
        $data = $model->findAll();
        $response = [
            'status'   => 200,
            'data' => $data
        ];
        return $this->respond($response, 200);
    }
    
       // get all Transaksi
    public function filter($id)
    {
        $model = new TransaksiModel();
        
        $db = \Config\Database::connect();
        $db = db_connect();

        $sql = "
        SELECT * FROM transaksi join user on user.id_user = transaksi.id_user join barang on barang.id_barang = transaksi.id_barang 
        where user.id_user = '$id'
        ";
        $data = $db->query($sql)->getResult();
        $response = [
            'status'   => 200,
            'data' => $data
        ];
        return $this->respond($response, 200);
    }

    // get single Transaksi
    public function show($id = null)
    {
        $model = new TransaksiModel();
        $data = $model->getWhere(['id_transaksi' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }
    
        // get single Transaksi where status 
    public function showtrans($status = null, $statuss = null)
    {
        $model = new TransaksiModel();
        $model->select('*');
        $model->join('user', 'user.id_user = transaksi.id_user');
        $model->join('barang', 'barang.id_barang = transaksi.id_barang');
        
        //filter status
        $model->where('status', $status);
        $model->orWhere('status', $statuss);
        
        $data = $model->find();
        
        // $data = $model->getWhere(['status' => $status])->getResult();
          $response = [
            'status'   => 200,
            'data' => $data
        ];
        if ($data) {
            return $this->respond($response,200);
        } else {
            return $this->failNotFound('No Data Found with id ' . $status);
        }
    }

    // create a Transaksi
    public function create()
    {
        $model = new TransaksiModel();
     
        $data = [
            'id_barang' => $this->request->getPost('id_barang'),
            'id_user' => $this->request->getPost('id_user'),
            'jumlah' => $this->request->getPost('jumlah')
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => $data
            ]
        ];
        return $this->respondCreated($response);
    }

    // update Transaksi
    public function update($id = null)
    {

        $model = new TransaksiModel();
        
        $input = $this->request->getVar();
        $data = [
            'status' => $this->request->getVar('sta')
        ];
            
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

    // delete Transaksi
    public function delete($id = null)
    {
        $model = new TransaksiModel();
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
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }
}
