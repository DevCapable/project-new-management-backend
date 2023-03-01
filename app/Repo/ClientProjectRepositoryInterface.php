<?php

namespace App\Repo;



use App\Models\Project;

interface ClientProjectRepositoryInterface

{

    const A = 'A';
    const B = 'B';
    const C = 'C';
    const D = 'D';
    const NOT_FOUND = "NF";


    public function create(array $data);

    public function edit(Project $model, array $data);

    public function delete($id);

    public function findAll($orderColumn = 'created_at', $orderDir = 'desc');

    public function findById($id);



}
