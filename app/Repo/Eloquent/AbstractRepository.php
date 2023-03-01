<?php

namespace App\Repo\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


abstract class AbstractRepository
{
    /**
     * The model to execute queries on.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;


    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model to execute queries on
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

    }

    /**
     * Get a new instance of the model.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    public function findAll($orderColumn = 'title', $orderDir = 'asc', $cols = array('*'))
    {
        $model = $this->model
            ->orderBy($orderColumn, $orderDir)
            ->get($cols);
        return $model;
    }

    public function findAllWithoutGet($orderColumn = 'title', $orderDir = 'asc', $cols = array('*'))
    {
        $model = $this->model->get();
        //$obj = new Collection($model);
        return $model;
    }

    public function findLimit($limit, $orderColumn = 'title', $orderDir = 'asc', $cols = array('*'))
    {
        $model = $this->model
            ->orderBy($orderColumn, $orderDir)
            ->limit($limit)
            ->get($cols);

        return $model;
    }

    /**
     * Use this function to return an ordered list in alphabetical order
     * @param $key
     * @param $value
     * @param null $default
     * @return array
     */
    public function orderedList($key, $value, $default = NULL)
    {
        $model = $this->model->orderBy($value)->lists($value, $key);
        if ($default != NULL) $model = array_add($model, '', $default);
        return $model;
    }

    public function listAll($key, $value, $default = NULL, $default_value = '')
    {
        $model = $this->model->lists($value, $key);
        if ($default != NULL) $model = array_add($model, $default_value, $default);
        asort($model);//sort everything.... @P
        return $model;
    }

    /**
     * @author Okeke Paul
     * For listing all columns of a model in json format
     * @param array $list
     * @param null $order Specify the value it should orderded by
     * @return mixed
     */
    public function listAllToJson(array $list, $order = NULL)
    {
        $model = $this->model->select($list)->orderBy($order)->get($list);
        return $model->toJson(JSON_HEX_APOS);
    }

    public function listModelRelationIds($model, $relation )
    {

        $get  = $model->$relation()->get()->lists('id');
         return $get;

        /*
        $new_array = array();
        foreach($get as $g){
            $new_array[] = $g->id;
        }
        return $new_array;*/
    }

    public function delete($id)
    {
        try {
            $model = $this->findById($id);
            $model->delete();
        } catch (\Exception $e) {}
    }

    public function findById($id, $cols = array('*'))
    {
        return $this->model->findOrFail($id, $cols);
    }

    public function findBy($where, $value, $cols = array('*'))
    {
        return $this->model->where($where, $value)->get($cols);
    }

    //@adedolapoo, use findby to retirieve single row
    public function findByFirst($where, $value, $cols = array('*'))
    {
        return $this->model->where($where, $value)->firstorFail($cols);
    }

    public function findByFirstWithRelations($where, $value, $with = [])
    {
        return $this->model->with($with)->where($where, $value)->firstorFail();
    }

    //@adedolapoo, use findby to retirieve single row
    public function findByFirstcod($where, $value1,$value2,$value3,$value4)
    {
        return $this->model->where($where, $value1,$value2,$value3,$value4)->get();
    }


    /**
     * @since 24/12/2015
     * Use this method to order records from recently added to least recent
     * @param $where
     * @param $value
     * @param array $cols
     * @return mixed
     */
    public function findInRecentOrder($where, $value, $cols = array('*'))
    {
        return $this->model->where($where, $value)->orderBy('created_at', 'desc')->get($cols);
    }

    /**
     * @since 28/12/2015
     * Use this method to list record in order by a column
     * @param $where
     * @param $value
     * @param array $cols
     * @param string $by
     * @return mixed
     */
    public function findAndOrderByCol($where, $value, $cols = array('*'), $by = "name")
    {
        return $this->model->where($where, $value)->orderBy($by, 'asc')->get($cols);
    }

    public function findBySingle($where, $value)
    {
        return $this->model->where($where, $value)->firstOrFail();
    }

    public function findByWhere($where, $cols = array('*'))
    {
        return $this->model->where($where)->get($cols);
    }

    public function findBySearch($data, $cols = array('*'))
    {
        $model = $this->model;
        return $model->get($cols);
    }

    public function findByIdWith($id, $cols = array('*'), $with = [])
    {
        return $this->model->with($with)->findOrFail($id, $cols);
    }

    public function findByWith($where, $value, $with, $cols = array('*'))
    {
        return $this->model->where($where, $value)->with($with)->get($cols);
    }

    public function getYesNo()
    {
        return array(
            '' => '--select--',
            'Yes' => 'Yes',
            'No' => 'No'
        );
    }

    public function getLiveDraft()
    {
        return array(
            '1' => 'Live',
            '0' => 'Draft'
        );
    }

    public function listRange($begin, $end, $default = NULL)
    {
        $range = array_combine($range = range($begin, $end), $range);
        if ($default != NULL) $range = array_add($range, '', $default);
        return $range;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
    public function operatorRecordCount($id)
    {
        return $this->model->where('operator_id', $id)->count();
    }

    public function getAllBySearchQuery($query, $search_column = 'name')
    {
        $query = trim(strtolower($query));
        $get = $this->model
            ->select([$search_column,'id'])
            ->whereRaw("lower(".$search_column.") like q'[%".$query."%]' ")
            /*->orWhereRaw("lower(".$search_column.") like '%\_".$query."%' ESCAPE '\' ")*/
            ->get();

        $model = [];
        foreach($get as $g){
            $model['suggestions'][] = ['value' => $g->$search_column, 'data' => $g->id];
        }

        if(!count($model))  $model['suggestions'] = [];

        return $model;
    }

    public function createAdvertReview($id, $data, $user, $current_case_command = 'approve')
    {

        if (is_numeric($id))
        {
            $model = $this->model->find($id);
        } else
        {
            $model = $id;
        }

        $action = isset($data['approve']) ? $data['approve'] : '';

        if ($action == "NEW") $review_status = 1;
        elseif ($action == 'approve') $review_status = 1;
        elseif ($action == 'reject') $review_status = -1;

        else $review_status = 1;

        //save Review
        $review_array = [
            //'discussion' => $data['comment'],
            'reviewer_id' => $user->id,
            'status' => $review_status,
            'reviewer_position_id' => $user->position_id
        ];

        if ($current_case_command == "RESUBMIT")
        {
            $review_array['status'] = 1;
        }

        $model->reviews()->create($review_array);
    }


    public function createAppReview($id, $data, $user, $current_case_command = 'RESUBMIT')
    {
        $model = $id;
        if (is_numeric($id)) $model = $this->model->find($id);

        $action = isset($data['action']) ? $data['action'] : '';

        if ($action == "NEW") $review_status = 0;
        elseif ($action == 'REJECT' || $action == "RETURN") $review_status = -1;
        elseif ($action == 'RECOMMEND_INSPECTION') $review_status = 5;
        elseif ($action == "RECOMMEND_REJECT") $review_status = -2;
        elseif ($action == "SAVE") $review_status = 10;
        else $review_status = 1;

        //save Review
        $review_array = [
            'discussion' => $data['comment'],
            'reviewer_id' => $user->id,
            'status' => $review_status,
            'reviewer_position_id' => $user->position_id
        ];

        if ($current_case_command == "RESUBMIT") $review_array['status'] = 0;

        $review = get_saved_user_app_review($model, $user);
        if(!empty($review)){
            $review->fill($review_array);
            $review->save();
        }else{
            $model->reviews()->create($review_array);
        }
    }

    private function _saveAppReviewDocuments(array $data, $model)
    {
        $documents = isset($data['review_uploaded_documents']) ? json_decode($data['review_uploaded_documents'], true) : [];
        if (count($documents))
        {
            $model->documents()->delete();
            foreach ($documents as $document)
            {
                $model->documents()->create($document);
            }
        }
    }

    public function countAll()
    {
        return $this->model->count();
    }

    public function findByIn($where, $array, $with = [])
    {
        return $this->model->with($with)->whereIn($where, $array)->get();
    }

    public function findAllForApi($with = [])
    {
        $filters = \Request::all();

        $per_page  = !empty($filters['per_page']) ? $filters['per_page'] : config('nogic.api.per_page');
        if(!empty($filters['length'])){
            $per_page = (int) $filters['length'];
        }

        $query = $this->model->with($with);

        if(method_exists($this->model,'scopeModelQuery')){
            $query = $query->modelQuery($filters);
        }

        if(method_exists($this->model,'scopeSearch')){
            $query = $query->search($filters);
        }

        if(method_exists($this->model,'scopeSort')){
            $query = $query->sort($filters);
        }

        $query =  $query->paginate($per_page);
        return $query;
    }

    public function findAllForApiByRelation($model, $relation_name, $relation_id = null)
    {
        $query = $model->$relation_name();

        $filters = \Request::all();

        if(empty($relation_id)){

            $per_page  = !empty($filters['per_page']) ? $filters['per_page'] : config('nogic.api.per_page');

            if(method_exists($this->model,'scopeModelQuery')){
                $query = $query->modelQuery($filters);
            }

            if(method_exists($this->model,'scopeSearch')){
                $query = $query->search($filters);
            }

            if(method_exists($this->model,'scopeSort')){
                $query = $query->sort($filters);
            }

            $query = $query->paginate($per_page);

        }else{
            $query = $query->where('id',$relation_id)->firstOrFail();
        }
        return $query;
    }

    public function getBaseSearchQueryData($input,$column = 'name')
    {
        if($input){
            if(is_numeric($input)){
                $input = $this->model->find($input)->$column;
            }else{
                $input = $this->model->firstOrCreate(['name'=>$input])->$column;
            }
            return $input;
        }
        return '';
    }

    public function getAllCodes(){
        $model = $this->model->all('code');
        return $model;
    }

}
