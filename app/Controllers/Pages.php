<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $faker = \Faker\Factory::create();
        $data = [
            'title' => 'Home | Web Dwi'
        ];
        return view('pages/home', $data);
    }
    public function about()
    {
        $data = [
            'title' => 'about | Web Dwi'
        ];
        return view('pages/about', $data);
    }
    public function contact()
    {
        $data = [
            'title' => 'contact | Web Dwi',
            'alamat' => [
                [
                    'tipe' => 'rumah',
                    'alamat' => 'Jl. abc No. 123',
                    'kota' => 'bandung'
                ],
                [
                    'tipe' => 'kantor',
                    'alamat' => 'Jl. cba No. 321',
                    'kota' => 'bogor'
                ],
            ],
        ];
        return view('pages/contact', $data);
    }
}
