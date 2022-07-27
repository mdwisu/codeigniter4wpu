<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        // $komik = $this->komikModel->findAll();

        $data = [
            'title' => 'Daftar Komik | Web Dwi',
            'komik' => $this->komikModel->getKomik(),
        ];
        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Komik',
            'komik' =>  $this->komikModel->getKomik($slug),
        ];

        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' tidak ditemukan');
        }
        return view('komik/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];
        return view('komik/create', $data);
    }


    public function save()
    {
        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ],
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|mime_in[sampul,image/png,image/jpg,image/jpeg]|is_image[sampul]',
                'errors' => [
                    'max_size' => 'ukuran gambar terlalu besar',
                    'mime_in' => 'extension gambar tidak sesuai',
                    'is_image' => 'file yang diupload bukan gambar'
                ]
            ]
        ])) {
            return redirect()->to('komik/create')->withInput();
        }
        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // apakah tida ada gambar
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.jpg';
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file img
            $fileSampul->move('img', $namaSampul);
        }
        // input
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil ditambahkan');
        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);
        // cek apabila gambar default jan dihapus
        if ($komik['sampul'] != 'default.jpg') {
            // hapus gambar
            unlink('img/'. $komik['sampul']);
        }
        // delete data
        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug),
        ];
        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // cek judul untuk mengatasi is_unique versi pa dika
        // $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        // if ($komikLama['judul'] == $this->request->getVar('judul')) {
        //     $rule_judul = 'required';
        // }else{
        //     $rule_judul = 'required|is_unique[komik.judul,slug,{slug}]';
        // }
        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul,slug,{slug}]',
                'errors' => [
                    'required' => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ],
                'sampul' => [
                    'rules' => 'max_size[sampul,1024]|mime_in[sampul,image/png,image/jpg,image/jpeg]|is_image[sampul]',
                    'errors' => [
                        'max_size' => 'ukuran gambar terlalu besar',
                        'mime_in' => 'extension gambar tidak sesuai',
                        'is_image' => 'file yang diupload bukan gambar'
                    ]
                ]
            ]
        ])) {
            return redirect()->to('komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');
        // cek gambar, apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama file random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file lama
            unlink('img/' . $this->request->getVar('sampulLama'));
        }
        // update
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil diubah');
        return redirect()->to('/komik');
    }
}
