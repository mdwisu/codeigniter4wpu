<?php

namespace App\Controllers;

use App\Models\orangModel;

class Orang extends BaseController
{
    protected $orangModel;
    public function __construct()
    {
        $this->orangModel = new OrangModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : '1';

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $orang = $this->orangModel->search($keyword);
        } else {
            $orang = $this->orangModel;
        }
        
        $jmlBaris = 6;
        $data = [
            'title' => 'Daftar Orang | Web Dwi',
            'jmlBaris' => '6',
            // 'orang' => $this->orangModel->findAll(),
            'orang' => $orang->paginate($jmlBaris, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage,
            'jmlBaris' => $jmlBaris
        ];
        return view('orang/index', $data);
    }
}
