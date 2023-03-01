<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class clientsImport implements ToModel
{
  
       use Importable;

    public function model(array $row)
    {
        
    }
}
