<?php

namespace App\Imports;

use App\Models\Projects;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class projectsImport implements ToModel
{
     use Importable;
    public function model(array $row)
    {
        
    }
}
