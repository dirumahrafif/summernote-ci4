<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBerita extends Model
{
    protected $table      = 'berita';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['judul', 'isi'];

    protected $validationRules = [
        'judul' => 'required',
        'isi' => 'required'
    ];
    protected $validationMessages = [
        'judul' => [
            'required' => 'Silakan masukkan judul'
        ],
        'isi' => [
            'required' => 'Silakan masukkan isi',
        ]
    ];
}
