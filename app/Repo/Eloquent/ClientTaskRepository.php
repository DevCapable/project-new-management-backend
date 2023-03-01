<?php

namespace App\Repo\Eloquent;

use App\Models\ClientProject;
use App\Models\Task;
use App\Repo\ClientTaskRepositoryInterface;

use Config;

class ClientTaskRepository extends AbstractRepository implements ClientTaskRepositoryInterface
{

    protected $search_term;

    protected $personnel_model;
    protected $personnel_position_model;

    public function __construct(
        Task $model
    )
    {
        $this->model = $model;

    }

    public function findForCompanyById($id, $company, $with = [])
    {
        $query = $this->model->with($with)
            ->where('id', $id);
        if (!empty($company)) {
            $query = $query->where('service_company_id', $company->id);
        }
        return $query->first();
    }

    public function create(array $data)
    {

        $model = $this->getNew($data);

        $model->save();

        return $model;
    }

    public function edit(Task $model, array $data)
    {

        $model = $model->fill($data);

        $model->save();

        return $model;
    }

    public function delete($id)
    {
        $model = $this->findById($id, $this->getColumnsWithoutFiles());
        $model->delete();
    }

}
