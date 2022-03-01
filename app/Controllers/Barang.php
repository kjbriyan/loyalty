<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BarangModel;

class Barang extends ResourceController
{
    use ResponseTrait;
    // get all Barang
    public function index()
    {

        $model = new BarangModel();
        $data = $model->findAll();
        $response = [
            'status'   => 200,
            'data' => $data
        ];
        return $this->respond($response, 200);
    }


    // get single Barang
    public function show($id = null)
    {
        $model = new BarangModel();
        $data = $model->getWhere(['id_barang' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    // create a Barang
    public function create()
    {
        // pake base64
        $input = $this->request->getRawInput();
        $fileGambar = base64_decode($input['img']);
        $namaGambar = md5(uniqid(rand(), true));
        $filename = $namaGambar . '.' . 'png';
        $path = "img/";
        file_put_contents($path . $filename, $fileGambar);
        
        // $data = $this->request->getPost();
        $model = new BarangModel();
        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'jumlah_barang' => $this->request->getPost('jumlah_barang'),
            'img' => $filename
        ];
        // $data['nama_barang'] = $this->request->getPost('nama_barang');
        // $data['jumlah_barang'] = $this->request->getPost('jumlah_barang');
        // $data['img'] = "h";
        // $data['g'] = "h";
        
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Created'
            ]
        ];

        return $this->respondCreated($response, 201);
    }

    // update Barang
    public function update($id = null)
    {

        $model = new BarangModel();
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                'nama_barang' => $json->nama_barang,
                'jumlah_barang' => $json->jumlah_barang,
                'img' => $json->img
            ];
        } else {
            // pake file url encode image
            // $input = $this->request->getRawInput();

            // $fileGambar = $this->request->getFile('img');
            // $namaGambar = $fileGambar->getRandomName();
            // $fileGambar->move('img', $namaGambar);

            // $data = [
            //     'nama_barang' => $input['nama_barang'],
            //     'jumlah_barang' => $input['jumlah_barang'],
            //     'img' => $namaGambar,
            // ];

            // pake base64
            $input = $this->request->getRawInput();
            $fileGambar = base64_decode($input['img']);
            $namaGambar = md5(uniqid(rand(), true));
            $filename = $namaGambar . '.' . 'png';
            $path = "img/";
            file_put_contents($path . $filename, $fileGambar);

            $data = [
                'nama_barang' => $input['nama_barang'],
                'jumlah_barang' => $input['jumlah_barang'],
                'img' => $filename,
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
    
      public function updatestock($id = null)
    {

        $model = new BarangModel();
        $json = $this->request->getJSON();
        if ($json) {
            $data = [
                
                'jumlah_barang' => $json->jumlah_barang
               
            ];
        } else {
  

            // pake base64
            $input = $this->request->getRawInput();
        

            $data = [
                
                'jumlah_barang' => $input['jumlah_barang']
                
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

    // delete Barang
    public function delete($id = null)
    {
        $model = new BarangModel();
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
