<?php

namespace App\Repo\Eloquent;

use App\Models\ClientProject;
use App\Models\Project;
use App\Repo\ClientProjectRepositoryInterface;

use Config;

class ClientProjectRepository extends AbstractRepository implements ClientProjectRepositoryInterface
{

    protected $search_term;

    protected $personnel_model;
    protected $personnel_position_model;

    public function __construct(
        ClientProject $model
//        NcrcApplicationPersonnel $personnel_model,
//        NcrcApplicationPersonnelPosition $personnel_position_model
    )
    {
        $this->model = $model;
//        $this->personnel_model = $personnel_model;
//        $this->personnel_position_model = $personnel_position_model;
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

    public function edit(Project $model, array $data)
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

//    public function getCreationForm()
//    {
//        return new NcrcApplicationsForm();
//    }

//    public function getColumns($category_slug)
//    {
//        $columns = [];
//        switch ($category_slug) {
//            case 'capitalization':
//                $columns = [
//
//                    'capitalization_id' => column_array(trans('ncrc.company_capitalization'), 'select', '', 'capitalization', 'name', true, '', false),
//                    'highest_equity_shares' => column_array(trans('ncrc.highest_equity_shares'), 'text', '', null, null, true, '', true, 100),
//                    'equity_shares_company' => column_array(trans('ncrc.equity_shares_company'), 'number', '', '', '', true, '', true, 100),
//                    'equity_shares_rig' => column_array(trans('ncrc.equity_shares_rig'), "number", '', '', '', true, '', true, 100),
//                    'manning_rig_crew' => column_array(trans('ncrc.manning_rig_crew'), 'number', '', '', '', true, '', true, 100),
//                    'personnel_senior_positions' => column_array(trans('ncrc.personnel_senior_positions'), 'number', '', '', '', true, '', true, 100),
//                    'email' => column_array(trans('ncrc.email'), 'email', '', '', '', true, ''),
//                    'phone' => column_array(trans('ncrc.phone'), 'number', '', '', '', true, ''),
//                    'rig_ownership' => column_array(trans('ncrc.rig_ownership'), 'select', '', '', '', true, '', true, 100),
//                ];
//                break;
//            case 'rig-details':
//                $columns = [
//                    'rig_category_id' => column_array(trans('ncrc.rig_category_id'), 'select', '', 'category', 'name', true),
//                    'rig_type' => column_array(trans('ncrc.rig_type_id'), 'text', 'search for a rig type', 'type', 'name', true),
//                    'engine_model' => column_array(trans('ncrc.engine_model'), "text", '', '', '', true),
//                    'manufacturer' => column_array(trans('ncrc.manufacturer'), 'text', '', '', '', true),
//                    'country_id' => column_array(trans('ncrc.country_id'), 'select', '', 'country', 'country', true),
//                    'total_rig_value' => column_array(trans('ncrc.total_rig_value'), 'number_mask', '', 'applications', 'name', true),
//                    'rig_drilling_depth' => column_array(trans('ncrc.rig_drilling_depth'), 'number', '', null, null, true),
//                    'engine_power' => column_array(trans('ncrc.engine_power'), 'number', '', '', '', true),
//                    'engine_rated_speed' => column_array(trans('ncrc.engine_rated_speed'), "number", '', '', '', true),
//                    'pump_rated_displacement' => column_array(trans('ncrc.pump_rated_displacement'), 'text', '', '', '', true),
//                    'drilling_type' => column_array(trans('ncrc.drilling_type'), 'text', '', '', '', true),
//                ];
//                break;
//            case 'nigerian-shares':
//                $columns = [
//                    'nigerian_equity_percent' => column_array(trans('ncrc.nigerian_equity_percent'), 'select', '', 'applications', 'name', true),
//                    'total_subcontract' => column_array(trans('ncrc.total_subcontract'), 'select', '', '', '', true),
//                    'agreement_nig_company' => column_array(trans('ncrc.agreement_nig_company'), "select", '', '', '', true),
//                    'period_rig_nigeria' => column_array(trans('ncrc.period_rig_nigeria'), 'select', '', '', '', true),
//                    'work_over_asset' => column_array(trans('ncrc.work_over_asset'), 'select', '', '', '', true),
//                    'proceeds_nig_bank' => column_array(trans('ncrc.proceeds_nig_bank'), 'select', '', '', '', true),
//                ];
//                break;
//            case 'nigerian-materials':
//                $columns = [
//                    'manufactured_nig_materials' => column_array(trans('ncrc.manufactured_nig_materials'), 'select', '', '', '', true),
//                    'procured_nig_materials' => column_array(trans('ncrc.nigerian_personnel'), 'select', '', '', '', true),
//                ];
//                break;
//            case 'nigerian-personnel':
//                $columns = [
//                    'nig_personnel_crew' => column_array(trans('ncrc.nig_personnel_crew'), 'select', '', '', '', true),
//                    'evidence_eq_approval' => column_array(trans('ncrc.evidence_eq_approval'), "select", '', '', '', true),
//                    'nig_personnel_senior' => column_array(trans('ncrc.nig_personnel_senior'), "select", '', '', '', true),
//                ];
//                break;
//            case 'services-contract':
//                $columns = [
//                    'value_of_logistics' => column_array(trans('ncrc.value_of_logistics'), "select", '', '', '', true),
//                    'catering_services' => column_array(trans('ncrc.catering_services'), 'select', '', '', '', true),
//                    'housekeeping_services' => column_array(trans('ncrc.housekeeping_services'), 'select', '', '', '', true),
//                    'waste_management' => column_array(trans('ncrc.waste_management'), 'select', '', '', '', true),
//                    'security_services' => column_array(trans('ncrc.security_services'), "select", '', '', '', true),
//                    'maintenance_services' => column_array(trans('ncrc.maintenance_services'), 'select', '', '', '', true),
//                    'specialised_services' => column_array(trans('ncrc.specialised_services'), 'select', '', '', '', true),
//                ];
//                break;
//        }
//        return $columns;
//    }

//    public function findForDatatable($company, $filter = 'all')
//    {
//        $filters = \Input::get('order');
//        $query = $this->model
//            ->with(['renewApplication'])
//            ->join('service_companies', 'ncrc_applications.service_company_id', '=', 'service_companies.id')
//            //->leftJoin('ncrc_categories', 'ncrc_applications.rig_category_id', '=', 'ncrc_categories.id')
//            ->leftJoin('ncrc_base_records rig_categories', 'ncrc_applications.rig_category_id', '=', 'rig_categories.id')
//            ->leftJoin('ncrc_base_records rig_types', 'ncrc_applications.rig_type_id', '=', 'rig_types.id')
//            ->leftJoin('ncrc_base_records capitalizations', 'ncrc_applications.capitalization_id', '=', 'capitalizations.id')
//            ->select([
//                'ncrc_applications.previous_app_id',
//                'ncrc_applications.id',
//                'ncrc_applications.app_no',
//                'ncrc_applications.created_at',
//                'ncrc_applications.app_status',
//                'ncrc_applications.certificate_no',
//                'service_companies.org_name',
//                //'ncrc_categories.name as category',
//                'ncrc_applications.wf_case_id',
//                'ncrc_applications.submitted_date',
//                'ncrc_applications.approval_date',
//                'rig_categories.name as rig_category',
//                'rig_types.name as rig_type',
//                'capitalizations.name as capitalization',
//            ]);
//        if (!empty($company)) {
//            $query = $query->where('ncrc_applications.service_company_id', $company->id);
//            if (empty($filters)) $query = $query->orderBy('ncrc_applications.created_at', 'desc');
//            return $query;
//        }
//        $query = $query->whereNotNull('wf_case_id');
//
//        if ($filter == 'approved') {
//            $query = $query->where('ncrc_applications.app_status', 1);
//        }
//
//        if ($filter == 'returned') {
//            $query = $query->where('ncrc_applications.app_status', -1);
//        }
//
//        if ($filter == 'pending') {
//            $query = $query->where(function ($query) {
//                return $query->where('ncrc_applications.app_status', 0)->orWhere('ncrc_applications.app_status', 2);
//            });
//        }
//
//        if (empty($filters)) $query = $query->orderByRaw('ncrc_applications.approval_date desc nulls last');
//
//        return $query;
//    }

//    public function getLastCertificateNumber()
//    {
//        return $this->model->whereNotNull('certificate_no')->count();
//    }

//    public function checkExistingRequest($company, $data, $id = null)
//    {
//        $first_name = !empty($data['first_name']) ? strtolower($data['first_name']) : '';
//        $last_name = !empty($data['last_name']) ? strtolower($data['last_name']) : '';
//        $rig_title_id = $data['rig_title_id'];
//        $model = $this->model
//            ->with(['application'])
//            ->whereRaw("lower(first_name) = '$first_name'")
//            ->whereRaw("lower(last_name) = '$last_name'")
//            ->where('job_title_id', $rig_title_id);
//        if (!empty($company)) {
//            $model = $model
//                ->where('requestable_type', get_class($company))
//                ->where('requestable_id', $company->id);
//        }
//        $model = $model->first();
//        if (!empty($id) && $model->id == $id) return null;
//        return $model;
//    }
//
//    public function getPersonnelPositions()
//    {
//        return $this->personnel_position_model->lists('name', 'id');
//    }

    public function savePersonnel($app, $personnel)
    {
        $personnel = !is_array($personnel) ? json_decode($personnel,true) : $personnel;
        if(!empty($personnel)){
            $app->personnel()->delete();
            foreach($personnel as $person){
                if(!empty($person['name'])){
                    $app->personnel()->create($person);
                }
            }
        }
    }
}
