<?php

namespace App\Controllers;

use App\Models\ModelBerita;

class Berita extends BaseController
{
    function __construct()
    {
        $this->model = new ModelBerita();
    }
    public function index($aksi = null, $id = null)
    {
        $data = [
            'judul' => '',
            'isi' => ''
        ];

        if ($this->request->getVar('simpan')) {
            $dataPost = $this->request->getPost();
            if ($id != '') {
                $dataPost['id'] = $id;
            }
            if (!$this->model->save($dataPost)) {
                session()->setFlashdata("error", $this->model->errors());
                return redirect()->to('berita');
            } else {
                session()->setFlashdata("sukses", "Sukses memasukkan data baru");
                return redirect()->to('berita');
            }
        }
        if ($aksi == 'edit') {
            $dataEdit = $this->model->where("id", $id)->first();
            if ($dataEdit == '') {
                $error[] = "Data yang kamu akses tidak ditemukan";
                session()->setFlashdata("error", $error);
                return redirect()->to('berita');
            } else {
                $data = $dataEdit;
            }
        }

        if ($aksi == "hapus") {
            $this->model->delete($id);
            session()->setFlashdata("sukses", "Berhasil menghapus data");
            return redirect()->to('berita');
        }
        $data['isiBerita'] = $this->model->findAll();
        return view('berita_view', $data);
    }

    function uploadGambar()
    {
        if ($this->request->getFile('file')) {
            $dataFile = $this->request->getFile('file');
            $fileName = $dataFile->getRandomName();
            $dataFile->move("uploads/berkas/", $fileName);
            echo base_url("uploads/berkas/$fileName");
        }
    }

    function deleteGambar()
    {
        $src = $this->request->getVar('src');
        //--> uploads/berkas/1630368882_e4a62568c1b50887a8a5.png

        //https://localhost/ci4summernote/public
        if ($src) {
            $file_name = str_replace(base_url() . "/", "", $src);
            if (unlink($file_name)) {
                echo "Delete file berhasil";
            }
        }
    }
    function listGambar()
    {
        $files = array_filter(glob('uploads/berkas/*'), 'is_file');
        $response = [];
        foreach ($files as $file) {
            if (strpos($file, "index.html")) {
                continue;
            }
            $response[] = basename($file);
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        die();
    }
}
