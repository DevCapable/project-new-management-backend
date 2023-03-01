<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


     use Importable;

    public function model(array $row)
    {
      
    }
}
       

       