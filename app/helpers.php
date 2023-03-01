<?php

use App\Models\AppointmentInvoicePayments;
use App\Models\ClientProject;
use App\Models\InvoicePayment;
use App\Models\ProjectInvoicePayments;
use App\Models\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

function generate_project_id($prefix)
{
    return $prefix . Str::random(9);
}


function getQuarters()
{
    return array(
        "January - March" => "January - March",
        "April - June" => "April - June",
        "July - September" => "July - September",
        "October - December" => "October - December",
    );
}

function get_page_breadcrumb($breadcrumbs, $title)
{
    echo '<ul>
         <li> <a href="' . url('/') . '">Home</a></li>
          ';
    if ($breadcrumbs != '') {
        foreach ($breadcrumbs as $breadcrumb) {
            echo '<li><a href="' . url($breadcrumb->uri) . '">' . $breadcrumb->title . '</a> </li>';
        }
    }
    echo '<li>' . $title . '</li></ul>';
}

function get_status($value)
{
    switch ($value) {
        case -1:
            return '<span class="label label-danger">Returned</span>';
        case 0:
            return '<span class="label label-warning">Pending</span>';
        case 1:
            return '<span class="label label-success">Approved</span>';
        default:
            return '<span class="label label-success">Approved</span>';

    }
}

function get_closed_out_status($value)
{
    switch ($value) {
        case -1:
            return '<span class="label label-danger">Returned</span>';
        case 0:
            return '<span class="label label-warning">submitted</span>';
        case 1:
            return '<span class="label label-success">Closed Out</span>';
        default:
            return '<span class="label label-success">Closed Out</span>';

    }
}

function getMultiDeleteView($route)
{
    return '<div class="row">
               <div class="table-footer">
                    <div class="col-md-6">
                        <div class="table-actions">
                              <a href="#" data-href="' . route($route . '.multi.delete') . '" class="btn btn-danger bs-tooltip multi_delete" data-original-title="delete selected" data-confirm="Are you sure you want to delete the selected data?">
                                <i class="icon-trash"> multiple records delete</i>
                              </a>
                         </div>
                    </div>
               </div>
            </div>';
}

function get_activated_status($val)
{
    switch ($val) {
        case 0:
            return '<span class="label label-danger">Deactivated</span>';
            break;
        case 1:
            return '<span class="label label-success">Activated</span>';
            break;
        default:
            return '';
    }
}

function get_inactive_status()
{
    return '<span class="label label-default">Inactive</span>';
}

function get_input_field_types()
{
    $input = array(
        'select' => 'Select',
        'text' => 'Text',
        'textarea' => 'Checkbox',
        'date' => 'Date',
        'file' => 'File',
    );

    $array = array('' => "--- Select an option----");
    foreach ($input as $key => $value) {
        $array[$key] = $value;
    }
    asort($array);

    return $array;
}


function get_live_status($value)
{
    switch ($value) {
        case 0:
            return '<span class="label label-danger">No</span>';
            break;
        case 1:
            return '<span class="label label-success">Yes</span>';
            break;
        default:
            return '';

    }
}

function get_status_from_value($value, $verb)
{
    switch ($value) {
        case -1:
            return '<span class="label label-danger">Not ' . $verb . '</span>';
        case 0:
            return '<span class="label label-warning">Pending</span>';
        case 1:
            return '<span class="label label-info">' . $verb . '</span>';
        default:
            return '';
    }
}

function get_verification_status($value)
{
    switch ($value) {
        case 0:
            return '<span class="label label-danger">Pending</span>';
            break;
        case -1:
            return '<span class="label label-info">Not Verified</span>';
            break;
        case 1:
            return '<span class="label label-success">Verified</span>';
            break;
        default:
            return '';

    }
}

function first_upper($string)
{
    return ucwords(strtolower($string));
}

//@ajayi- convert array value to uppercase
function arrayToUpper(array $data)
{
    if (is_array($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = strtoupper($value);
        }
        return $result;
    }
}

function range_dob_day()
{
    $dayRange = range(1, 31);
    $new_array = array('' => 'Day');
    foreach ($dayRange as $key => $dayNumber) {
        $new_array[$dayNumber] = $dayNumber;
    }
    return $new_array;
}

function range_dob_month()
{
    $monthRange = range(1, 12);
    $new_array = array('' => 'Month');
    foreach ($monthRange as $key => $month) {
        $new_array[$month] = $month;
    }
    return $new_array;
}

function range_dob_year()
{
    $now = \Carbon\Carbon::now();
    $start = $now->subYears(18)->format('Y');
    $end = $now->subYears(60)->format('Y');
    $yearRange = range($start, $end);
    $new_array = array('' => 'Year');
    foreach ($yearRange as $key => $year) {
        $new_array[$year] = $year;
    }
    return $new_array;
}

function range_years_obtained()
{
    //lets create a range of years
    $years = range(date('Y'), 1940);
    $array = array('' => "-- Select a year --");
    foreach ($years as $key => $value) {
        $array[$value] = $value;
    }
    return $array;

}

function sessionList()
{
    //lets create a range of years
    $years = range(date('Y'), date('Y') - 55);
    $array = array('' => "-- Select Session --");
    foreach ($years as $key => $from) {
        $to = $from + 1;
        $array[$from . '-' . $to] = $from . '-' . $to;
    }
    return $array;

}

function range_years()
{
    $array = range(date('Y') + 20, 1940);
    $new_array = array('' => '-- Select year --');
    foreach ($array as $key => $value) {
        $new_array[$value] = $value;
    }
    return $new_array;
}

function step_active($pattern)
{
    if (Request::is($pattern)) {
        return '<li class="active">';
    } else {
        return '<li>';
    }

}

function display_doc($file, $path = 'certificates/', $label = 'Uploaded Certificate')
{
    $image = array('jpg', 'jpeg', 'gif', 'png');
    $type = substr($file, -3);
    echo '<strong style="font-size:14px;">' . $label . '</strong><div style="margin-bottom:10px;"></div>';
    if (in_array($type, $image)) {
        return '<img src="' . url('uploads/' . $path . $file) . '" width="250">';
    } else {
        return '<a href="' . url('uploads/' . $path . $file) . '" target="_blank" class="btn btn-info">Click here to preview uploaded certificate in pdf or doc.</a>';
    }
}

function display_file($file, $path = 'files/')
{
    $image = array('jpg', 'jpeg', 'gif', 'png');
    $type = substr($file, -3);
    if (in_array($type, $image)) {
        echo '<img src="' . url('uploads/' . $path . $file) . '" width="150">';
    } else if (!empty($file)) {
        echo '<a href="' . url('uploads/' . $path . $file) . '" target="_blank" class="btn btn-info">Click here to preview uploaded document.</a>';
    } else {
        echo 'No File Attached.';
    }
}

function column_array($title, $type = 'text', $placeholder = '', $relation = null, $relation_name = 'name', $required = false, $id = '', $sort = true, $max = null, $min = null)
{
    return [
        'title' => $title,
        'type' => $type,
        'placeholder' => $placeholder,
        'relation' => $relation,
        'relation_name' => $relation_name,
        'required' => $required,
        'id' => $id,
        'sort' => $sort,
        'max' => $max,
        'min' => $min,
    ];
}

function generate_user_grid($users)
{
    $str = '';
    foreach ($users as $user) {
        $ind = $user->individual;
        $str .= '<div class="col-md-3">' . PHP_EOL;
        $str .= '<div class="widget-box">' . PHP_EOL;
        $str .= '<div class="widget-content">' . PHP_EOL;
        $str .= '<a target="blank" href=' . url('system-admin/individuals/view/') . '/' . $ind->id . '>' . PHP_EOL;
        $str .= '<div class="ribbon-wrapper"></div>' . PHP_EOL;
        $str .= '<div style="background-color:grey;width:70px;height:70px;display:inline-block"></div>' . PHP_EOL;
        $str .= '<div style="display:inline-block">' . PHP_EOL;
        $str .= '<p><strong>' . $ind->first_name . ' ' . $ind->last_name . '</strong></p>' . PHP_EOL;
        $str .= '<p> Competency ID: ' . $ind->competency_id . '</p>' . PHP_EOL;
        $str .= '<p> JQS ID: ' . $ind->jqs_id . '</p>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</a>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
    }
    return $str;
}

function generate_grid($users)
{
    $str = '';
    foreach ($users as $user) {
        $ind = $user->staff->individual;
        $str .= '<div class="col-md-12">' . PHP_EOL;
        $str .= '<div class="widget-box">' . PHP_EOL;
        $str .= '<div class="widget-content">' . PHP_EOL;
        $str .= '<a target="blank" href=' . url('system-admin/individuals/view/') . '/' . $ind->id . '>' . PHP_EOL;
        $str .= '<div class="ribbon-wrapper"></div>' . PHP_EOL;
        $str .= '<div style="background-color:grey;width:70px;height:70px;display:inline-block"></div>' . PHP_EOL;
        $str .= '<div style="display:inline-block">' . PHP_EOL;
        $str .= '<p><strong>' . $ind->first_name . ' ' . $ind->last_name . '</strong></p>' . PHP_EOL;
        $str .= '</a><p> Competency ID: ' . $ind->competency_id . '</p>' . PHP_EOL;
        $str .= '<p> JQS ID: ' . $ind->jqs_id . '</p>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '<div style="display:inline-block"><p>Gap Closure Plan: ' . $user->gap_closure_plan . '</p></div>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</div><hr>' . PHP_EOL;
    }
    return $str;
}

function get_skills_tag($skills)
{
    $str = "";
    foreach ($skills as $skill) {
        $str .= '<span data-toggle="modal" href=""  style="font-size:10px;padding:2px;margin:2px;text-align:center"
            class="label label-default missing_skills">' . $skill->name . '</span> ';
    }
    return $str;
}

function get_skill_tags($uns_tags, $gapsClosure = "")
{
    $str = '';
    $tags = unserialize($uns_tags);
    $gaps = $gapsClosure;
    $isJson = false;
    if (isJson($gapsClosure)) {
        $isJson = true;
        $gaps = json_decode($gapsClosure, true);
    }
    for ($i = 0; $i < count($tags); $i++) {
        $skill = \Cms\Site\Models\SkillCategory::find($tags[$i]);
        if ($skill) {
            $dataGaps = ($isJson) ? $gaps[$skill->id] : $gaps;
            if (!is_null($skill)) {
                $str .= '<span data-toggle="modal" href="#openGapsClosure"  style="font-size:11px;padding:4px;text-align:center"
            data-gaps="' . $dataGaps . '" class="label label-danger missing_skills">' . $skill->name . '</span> ';
            }
        }

    }
    return $str;
}

function isJson($jsonString)
{
    json_decode($jsonString);
    return (json_last_error() == JSON_ERROR_NONE);
}


function generate_view_table($columns = array(), $item, $without_table_tags = false, $route_prefix = '', $doc_path = 'files/')
{

    $i = 1;
    $str = '';
    if (!$without_table_tags) {
        $str .= '<table class="table table-striped table-bordered table-view">' . PHP_EOL;
    }

    foreach ($columns as $col => $value) {
        $str .= '<tr>';
        $colValue = (!is_null($item)) ? $item->$col : "---";
        if ($value['type'] == 'file') {
            $str .= '<td colspan="2">' . display_file($item->$col, $doc_path) . '</td>' . PHP_EOL;
        } elseif ($value['type'] == 'file_pdf') {
            $str .= '<td colspan="2">' . display_file($item->$col, $doc_path) . '</td>' . PHP_EOL;
        } elseif ($value['type'] == 'database_file') {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            $str .= '<td><a href="' . route($route_prefix . '.' . $col, array($item->id)) . '" target="_blank" class="btn btn-info">View File</a></td>' . PHP_EOL;
        } elseif ($value['type'] == 'multiple') {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            $str .= '<td>';
            $g = '';
            if (!is_null($item)) {
                if (is_array($item->$col)) {
                    foreach ($item->$col as $c) {
                        if (isset($c->name))
                            $g .= $c->name . ', ';
                        else
                            $g .= $c . ', ';
                    }
                }
            }
            $str .= substr($g, 0, -2);
            $str .= '</td>' . PHP_EOL;

        } else if ($value['type'] == 'checkbox') {
            //handle checkbox fields
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            if ($item->$col) {
                $str .= '<td>Yes</td>' . PHP_EOL;
            } else {
                $str .= '<td>No</td>' . PHP_EOL;
            }
        } else if ($value['type'] == 'number_mask') {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            $str .= '<td id="' . $col . '">' . format_number($colValue) . '</td>' . PHP_EOL;
        } else if ($value['type'] == 'hidden2') {
            continue;
        } else {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            //$str .= '<td>'.$item->$col.'</td>'. PHP_EOL;
            if ($value['relation'] != null) {
                $value = isset($item->$value['relation']->$value['relation_name']) ? $item->$value['relation']->$value['relation_name'] : '';
                $str .= '<td>' . $value . '</td>' . PHP_EOL;

            } elseif ($col == 'live_status') {
                $str .= '<td>' . get_live_status($item->$col) . '</td>' . PHP_EOL;
            } else {
                $str .= '<td id="' . $col . '">' . $colValue . '</td>' . PHP_EOL;
            }
        }
        $str .= '</tr>';
    }
    if (!$without_table_tags) {
        $str .= '</table>';
    }

    return $str;
}

function generate_view_table2($columns = array(), $item, $without_table_tags = false, $route_prefix = '', $doc_path = 'files/')
{
    $i = 1;
    $str = '';
    if (!$without_table_tags) {
        $str .= '<table class="table table-striped table-bordered">' . PHP_EOL;
    }

    foreach ($columns as $col => $value) {
        $str .= '<tr>';
        if ($value['type'] == 'file') {
            $str .= '<td colspan="2">' . display_file($item->$col, $doc_path) . '</td>' . PHP_EOL;
        } elseif ($value['type'] == 'file_pdf') {
            $str .= '<td colspan="2">' . display_file($item->$col, $doc_path) . '</td>' . PHP_EOL;
        } elseif ($value['type'] == 'database_file') {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            $str .= '<td><a href="' . route($route_prefix . '.' . $col, array($item->id)) . '" target="_blank" class="btn btn-info">View File</a></td>' . PHP_EOL;
        } elseif ($value['type'] == 'multiple') {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            $str .= '<td>';

            $str .= $item->$col;
            $str .= '</td>' . PHP_EOL;
        } else if ($value['type'] == 'checkbox') {
            //handle checkbox fields
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            if ($item->$col) {
                $str .= '<td>Yes</td>' . PHP_EOL;
            } else {
                $str .= '<td>No</td>' . PHP_EOL;
            }
        } else if ($value['type'] == 'hidden2') {
            continue;
        } else {
            $str .= '<td><strong>' . $value['title'] . '</strong></td>' . PHP_EOL;
            //$str .= '<td>'.$item->$col.'</td>'. PHP_EOL;
            if ($value['relation'] != null) {
                $value = isset($item->$value['relation']->$value['relation_name']) ? $item->$value['relation']->$value['relation_name'] : '';
                $str .= '<td>' . $value . '</td>' . PHP_EOL;

            } elseif ($col == 'live_status') {
                $str .= '<td>' . get_live_status($item->$col) . '</td>' . PHP_EOL;
            } else {
                $str .= '<td>' . $item->$col . '</td>' . PHP_EOL;
            }
        }
        $str .= '</tr>';
    }
    if (!$without_table_tags) {
        $str .= '</table>';
    }

    return $str;
}

function generate_dynamic_form_inputs($items, $datas, $divide, $equipment)
{
    $str = '';
    foreach ($items as $item) {
        $value = null;
        $id = '';
        if ($datas->count()) {
            foreach ($datas as $data) {
                if ($item->id == $data->equipment_specific_field_id) {
                    $value = $data->value;
                    $id = $data->id;
                }
            }
        }
        $str .= '<div class="col-md-' . $divide . '">' . PHP_EOL;
        $str .= '<div class="form-group">' . PHP_EOL;
        $str .= '<div class="col-md-12">' . PHP_EOL;
        $str .= '<label class="control-label" style="margin-bottom:5px">' . $item->label . ((isSpecificFieldRequired($item, $equipment) == 'required') ? '<span style="color:red"> *</span>' : '') . '</label>' . PHP_EOL;
        $str .= Form::hidden('datas[' . $item->id . '][field_id]', $item->id);
        $str .= Form::hidden('datas[' . $item->id . '][name]', $item->title);
        $str .= Form::hidden('datas[' . $item->id . '][id]', $id);
        switch (strtolower($item->type)) {
            case "select":
                //$array = $item->dropdowns->lists('name','id');
                $array = [];
                $v_array = ($item->value) ? json_decode($item->value) : [];
                //dd($array);
                if (count($v_array)) {
                    foreach ($v_array as $value) {
                        $array[$value] = $value;
                    }
                }
                $str .= Form::select('datas[' . $item->id . '][value]', $array, $value, array('class' => 'form-control', 'placeholder' => $item->placeholder, isSpecificFieldRequired($item, $equipment)));
                $str .= '<a href="#" class="btn btn-xs btn-success missing-record" style="display:inline-block" id="sr_link_c_d">Send Missing Request</a>';
                $str .= ' <i class="icon-info-sign bs-popover" data-trigger="hover" data-placement="top" data-content="If any option does not exist in the dropdowns, kindly click on the send missing request to send a request to the appropriate channel to update the option list" data-original-title="Missing Request" style="color:#3968c6">
                                                </i>';
                break;
            case "text":
                $str .= Form::text('datas[' . $item->id . '][value]', $value, array('class' => 'form-control', 'placeholder' => $item->placeholder, isSpecificFieldRequired($item, $equipment)));
                break;
            case "number":
                $str .= Form::text('datas[' . $item->id . '][value]', $value, array('class' => 'form-control', 'placeholder' => $item->placeholder, isSpecificFieldRequired($item, $equipment)));
                break;
            case "date":
                //$str .= '<div class="input-group">';
                $str .= Form::text('datas[' . $item->id . '][value]', $value, array('class' => 'form-control date-with-past', 'placeholder' => 'Date Format: dd/mm/yyy', 'readonly'));
                //$str .= ' <span class="input-group-addon"><i class="icon-calendar"></i></span></div>';
                break;
            case "textarea":
                $str .= Form::textarea('datas[' . $item->id . '][value]', $value, array('class' => 'form-control', 'rows' => '3', 'placeholder' => $item->placeholder, isSpecificFieldRequired($item, $equipment)));
                break;
            case "file":
                if ($value == '' or $value == null) {
                    $str .= Form::file('files[' . $item->id . '][]', array('multiple' => true, isSpecificFieldRequired($item, $equipment))) . '<div class="spacer-10"></div>';
                    $str .= '<span class="label label-danger">Document not uploaded</span>';
                } else {
                    $str .= Form::file('files[' . $item->id . '][]', array('multiple' => true)) . '<div class="spacer-10"></div>';
                    $str .= '<a href="' . url('uploads/files/' . $value) . '" target="_blank" class="btn btn-xs btn-success">Click here to preview uploaded document.</a>';
                }
                break;
            default:
                $str .= Form::text('datas[' . $item->id . '][value]', $value, array('class' => 'form-control', isSpecificFieldRequired($item, $equipment)));
        }
        $str .= '</div>' . PHP_EOL;
        $str .= '</div>' . PHP_EOL;
        $str .= '</diV>' . PHP_EOL;
    }
    return $str;
}

function isSpecificFieldRequired($item, $equipment)
{
    if ($item->is_required == 1) {
        return "required";
    } elseif ($item->is_required == 10) {
        $is_required_if = json_decode($item->is_required_if);
        if (count($is_required_if)) {
            if ($is_required_if->assert == '==') {
                if ($equipment->{$is_required_if->field} == $is_required_if->value) {
                    return "required";
                }
                return "";
            } else {
                if ($equipment->{$is_required_if->field} != $is_required_if->value) {
                    return "required";
                }
                return "";
            }
        } else {
            return "";
        }
    }
    return "";
}

function generate_form_inputs($columns, $select_array = null, $divide = 4, $for_edit = false)
{
    if (is_array($select_array)) {
        extract($select_array);
    }

    $chunk = 12 / $divide;

    $chunked_columns = array_chunk($columns, $chunk, true);

    $str = '<div class="form-container">' . PHP_EOL;

    foreach ($chunked_columns as $columns) {
        $str .= '<div class="row">' . PHP_EOL;
        foreach ($columns as $col => $value) {
            //dd($columns);
            //dd($col);
            //d($value);
            //dd($columns);
            $str .= '<div class="col-md-' . $divide . '">' . PHP_EOL;
            $str .= '<div class="form-group">' . PHP_EOL;
            $str .= '<div class="col-md-12">' . PHP_EOL;
            if ($value['type'] != 'hidden') {
                $str .= '<label class="control-label ' . $col . '" style="margin-bottom:5px">' . $value['title'] .
                    (($value['required']) ? '<span style="color:red"> *</span>' : '') . '</label>' . PHP_EOL;
            }

            switch ($value['type']) {
                case "select":
                    $list = is_array($$col) ? $$col : [];
                    if (!empty($value['sort'])) asort($list);
                    $selected = isset(${$col . '_selected'}) ? ${$col . '_selected'} : null;
                    //@adedolapo this ensures all list are sorted alphabetically, sln to 'Ensure the contents of all Listbox are sorted in ascending order.'
                    $str .= Form::select($col, $list, $selected, array('class' => 'form-control', isRequired($value['required'])));
                    if ($value['placeholder']) {
                        $str .= '<span class="help-block ">' . $value['placeholder'] . '</span>';
                    }

                    break;
                case "select2":
                    $list = $$col;
                    if (is_array($list)) {
                        asort($list);
                    } else {
                        $list = null;
                    }
                    //@adedolapo this ensures all list are sorted alphabetically, sln to 'Ensure the contents of all Listbox are sorted in ascending order.'
                    $str .= Form::select($col, $list, null, array('class' => 'select2 full-width-fix', 'id' => $col, 'placeholder' => $value['placeholder']));
                    break;
                case "multiple":
                    if ($for_edit) {
                        $a = $col . '_selected';
                        $v = $$a;
                    } else {
                        $v = null;
                    }
                    $str .= Form::select($col . '[]', $$col, $v, array('multiple', 'class' => 'select2 full-width-fix', 'id' => $col, 'placeholder' => $value['placeholder'], isRequired($value['required'])));
                    break;
                //added 23/12/2015 @paul
                //added isRequired to enable html5 form validity check
                case "email":
                    $str .= Form::email($col, null, array('class' => 'form-control', isRequired($value['required']), 'placeholder' => $value['placeholder'], 'autocomplete' => 'off'));
                    break;
                case "text":
                    $str .= Form::text($col, null, array('class' => 'form-control', 'placeholder' => $value['placeholder'],
                        'autocomplete' => 'off', isRequired($value['required'])));
                    break;
                case "text_with_helper":
                    $str .= Form::text($col, null, array('class' => 'form-control', 'autocomplete' => 'off', isRequired($value['required'])));
                    if ($value['placeholder']) {
                        $str .= '<span class="help-block ">' . $value['placeholder'] . '</span>';
                    }
                    break;
                case "url":
                    $str .= Form::url($col, null, array('class' => 'form-control', 'placeholder' => $value['placeholder']));
                    break;
                case "password":
                    $str .= Form::password($col, array('class' => 'form-control', 'placeholder' => $value['placeholder'], 'id' => $value['id']));
                    break;
                case "date":
                    $str .= '<div class="input-group date">';
                    $str .= Form::text($col, null, array('id' => $col, 'class' => 'form-control', 'placeholder' => 'Date Format: dd/mm/yyy', 'readonly', isRequired($value['required'])));
                    $str .= ' <span class="input-group-addon"><i class="icon-calendar"></i></span></div>';
                    break;
                case "date2": //use this date if you will be initializing the date somewhere else e.g for validation etc @paul
                    $str .= '<div class="input-group date2">';
                    $str .= Form::text($col, null, array('id' => $col, 'class' => 'form-control', 'placeholder' => 'Date Format: dd/mm/yyy', 'readonly', isRequired($value['required'])));
                    $str .= ' <span class="input-group-addon"><i class="icon-calendar"></i></span></div>';
                    break;
                case "textarea":
                    $str .= Form::textarea($col, null, array('class' => 'form-control', 'rows' => '3', isRequired($value['required'])));
                    // $str .= '<span style="color:red"> *</span>';
                    break;
                case "textarea_summernote":
                    $str .= Form::textarea($col, null, array('class' => 'form-control minified_summernote', 'id' => $col, 'rows' => '3', 'placeholder' => $value['placeholder'], isRequired($value['required'])));
                    // $str .= '<span style="color:red"> *</span>';
                    break;
                case "file":
                    $str .= '<input type="file" name="' . $col . '" ' . isRequired($value['required']) . ' class="form-control"><div class="spacer-10"></div>';
                    if ($value['placeholder'] != '') {
                        $str .= '<span style="color:red;">' . $value['placeholder'] . '</span>';
                    } else {
                        $str .= '<span style="color:red;">Max file size: ' . config('nogic.file_size') . 'mb</span>';
                        $str .= '<div></div>';
                        $str .= '<span style="color:red;">Formats Allowed: png, jpg, jpeg,gif,pdf,doc</span>';
                    }
                    break;
                case "file_pdf":
                    $str .= '<input type="file" name="' . $col . '" ' . isRequired($value['required']) . ' class="form-control"><div class="spacer-10"></div>';
                    if ($value['placeholder'] != '') {
                        $str .= '<span style="color:red;">' . $value['placeholder'] . '</span>';
                    } else {
                        $str .= '<span style="color:red;">Max file size: ' . config('nogic.file_size') . 'mb</span>';
                        $str .= '<div></div>';
                        $str .= '<span style="color:red;">Formats Allowed: pdf</span>';
                    }
                    break;
                case "file_xls":
                    $str .= '<input type="file" name="' . $col . '" ' . isRequired($value['required']) . ' class="form-control"><div class="spacer-10"></div>';
                    if ($value['placeholder'] != '') {
                        $str .= '<span style="color:red;">' . $value['placeholder'] . '</span>';
                    } else {
                        $str .= '<span style="color:red;">Max file size: ' . config('nogic.file_size') . 'mb</span>';
                        $str .= '<div></div>';
                        $str .= '<span style="color:red;">Formats Allowed: Excel(xls)</span>';
                    }
                    break;
                case "database_file":
                    $str .= '<input type="file" name="' . $col . '"><div class="spacer-10"></div>';
                    if ($value['placeholder'] != '') {
                        $str .= '<span style="color:red;">' . $value['placeholder'] . '</span>';
                    } else {
                        $str .= '<span style="color:red;">Max file size: ' . config('nogic.file_size') . 'mb; (Allowed formats: png, jpg, jpeg,gif,pdf,doc)</span>';
                    }
                    break;
                case "hidden":
                    $str .= '<input type="hidden" name="' . $col . '" id="' . $col . '"><div class="spacer-10"></div>';
                    break;
                case "hidden2":
                    $str .= Form::hidden($col);
                    break;
                case "checkbox":
                    $str .= Form::checkbox($col) . Form::hidden($col, 0);
                    break;
                case "number":
                    $array = ['class' => 'form-control', isRequired($value['required'])];
                    if (!empty($value['max'])) {
                        $array = array_merge(['maxLength' => $value['max']], $array);
                    }
                    if (!empty($value['min'])) {
                        $array = array_merge(['minLength' => $value['min']], $array);
                    }
                    $str .= Form::input('number', $col, null, $array);
                    break;
                case "number_int":
                    $str .= Form::input('number', $col, null, array('class' => 'form-control', 'min' => 1, isRequired($value['required'])));
                    break;
                case "number_mask":
                    $str .= Form::input('text', $col, null, array('class' => 'form-control mask-input', isRequired($value['required'])));
                    break;
                default:
                    $str .= Form::text($col, null, array('class' => 'form-control', 'placeholder' => $value['placeholder'],
                        'autocomplete' => 'off', isRequired($value['required'])));
            }
            if ($value['placeholder'] && in_array($value['type'], ['textarea_summernote', 'textarea'])) {
                $str .= '<span class="help-block ">' . $value['placeholder'] . '</span>';
            }
            $str .= '</div>' . PHP_EOL;
            $str .= '</div>' . PHP_EOL;
            $str .= '</diV>' . PHP_EOL;
        }
        $str .= '</diV>' . PHP_EOL;
    }
    $str .= '</diV>' . PHP_EOL;

    return $str;
}

function getServiceMode()
{
    $mode = array("" => "-- Select --",
        "In partnership with foreign company" => "In partnership with foreign company",
        "In partnership with local company" => "In partnership with local company",
        "In partnership with local and foreign company" => "In partnership with local and foreign company",
        "Without any partnership" => "Without any partnership");

    return $mode;
}

/**
 * @param bool|false $required
 * @return string
 * @author Okeke Paul
 * 24/12/2015
 */
function isRequired($required = false)
{
    if ($required) {
        return "required";
    }
    return "";
}

/**
 * @param array $cols
 * @param $items
 * @param array $table_actions
 * @param array $new_cols
 * @param bool|false $status
 * @param bool|true $datatable
 * @param null $wf_type
 * @param bool|true $checkbox
 * @param string $doc_path
 * @return string
 * @since 2015
 * The DataTable is initialized at
 * @comment added target_blank to open file on a new tab. @olusola
 */
function generate_table($cols = array(), $items, $table_actions = array(), $new_cols = array(), $status = false, $datatable = true, $wf_type = null, $checkbox = true, $doc_path = "files/")
{
    $str = '';
    $add_datatable = '';
    if ($datatable == true) {
        $add_datatable = 'datatable';
    }
    $checkFalse = true;
    $str .= '<table class="table table-striped table-bordered table-checkable table-hover ' . $add_datatable . '">' . PHP_EOL;
    if ($checkbox === false) {
        $checkFalse = false;
    }
    $str .= generate_table_header($cols, $new_cols, $status, $checkFalse, $table_actions);
    $str .= '<tbody> ' . PHP_EOL;
    foreach ($items as $item) {
        $str .= '<tr data-record-id="' . $item->id . '" class="row-' . $item->id . '">' . PHP_EOL;
        if ($checkFalse === true) {
            //we need to also check if the record is validated: if yes lets
            // disable the checkbox to prevent delete or multi-delete  ---- comment by paul
            if (isset($item->wf_case_id)) {
                $status = \Workflow::getCaseStatus($item->wf_case_id, \Workflow::VALIDATION);
                if ($status === 'VALIDATED') {
                    $str .= '<td class="checkbox-column"><input type="checkbox" class="uniform" name="check[]" value="' . $item->id . '" disabled ></td>';
                } else {
                    $str .= '<td class="checkbox-column"><input type="checkbox" class="uniform" name="check[]" value="' . $item->id . '"></td>';
                }
            } else {
                $str .= '<td class="checkbox-column"><input type="checkbox" class="uniform" name="check[]" value="' . $item->id . '"></td>';
            }
        }

        foreach ($cols as $col => $field) {
            if (count($new_cols) > 0) {
                if (in_array($col, $new_cols)) {
                    if ($field['relation'] == null) {
                        $str .= '<td>' . $item->$col . '</td>' . PHP_EOL;
                    } else {
                        //check for orphaned records
                        if ((is_null($item)) || (is_null($item->$field['relation']))) {
                            $str .= '<td></td>' . PHP_EOL;
                        } else {
                            $str .= '<td>' . $item->$field['relation']->$field['relation_name'] . '</td>' . PHP_EOL;
                        }

                    }
                }
            } else {
                if ($field['relation'] == null) {
                    if ($field['type'] == 'file') {
                        $str .= '<td><a href="' . asset('uploads/' . $doc_path . $item->$col) . '"" target="_blank">' . $item->$col . '</a></td>' . PHP_EOL;
                    } else {
                        $str .= '<td>' . $item->$col . '</td>' . PHP_EOL;
                    }
                } else {
                    $get_val = isset($item->$field['relation']->$field['relation_name']) ? $item->$field['relation']->$field['relation_name'] : 'n/a';
                    $str .= '<td>' . $get_val . '</td>' . PHP_EOL;
                }
            }

        }
        if ($status) {
            if (!is_null($item->status)) {
                $str .= '<td>' . get_status($item->status) . '</td>' . PHP_EOL;
            } else if (!is_null($item->validation_status)) {
//in operator module validation status is in a seperate entity
                $str .= '<td><span class="label label-info">' . $item->validation_status->description . '</span></td>' . PHP_EOL;
            } else if (!is_null($item->wf_case_id)) {
                $str .= '<td>' . get_status_html(\Workflow::getCaseStatus($item->wf_case_id, $wf_type)) . '</td>' . PHP_EOL;
            } else {
                $str .= '<td> none </td>' . PHP_EOL;
            }

        }
        if (count($table_actions)) {
            $str .= generate_table_actions($table_actions, $item);
        }
        $str .= '</tr>' . PHP_EOL;
    }

    $str .= '</tbody>' . PHP_EOL;

    $str .= '</table>';
    return $str;
}

function generate_table_header($cols, $new_cols, $status, $checkbox = true, $table_actions)
{
    $str = '';
    $str .= '<thead><tr> ' . PHP_EOL;
    if ($checkbox === true) {
        $str .= '<th class="checkbox-column"><input type="checkbox" class="uniform"></th>' . PHP_EOL;
    }
    foreach ($cols as $col => $field) {
        if (count($new_cols) > 0) {
            if (in_array($col, $new_cols)) {
                $str .= '<th>' . $field['title'] . '</th>' . PHP_EOL;
            }
        } else {
            $str .= '<th>' . $field['title'] . '</th>' . PHP_EOL;
        }
    }
    if ($status) {
        $str .= '<th>Validation Status</th>' . PHP_EOL;
    }
    if (count($table_actions)) {
        $str .= '<th>Action</th>' . PHP_EOL;
    }
    $str .= '</tr></thead>' . PHP_EOL;
    return $str;
}

function generate_table_actions($table_actions, $item)
{
    $str = '';
    $str .= '<td> <ul class="table-controls"  id="ajaxModal">';
    $status = \Workflow::getCaseStatus($item->wf_case_id, \Workflow::VALIDATION);
    foreach ($table_actions as $key => $action) {
        if ($key == 'delete') {
            if ($status !== 'VALIDATED' && is_null($item->wf_case_id)) {
                $str .= '<li><a href="' . route($action['route'], $item->$action['id']) . '" class="bs-tooltip delete-me" data-original-title="' . $action['title'] . '" data-confirm="' . $action['confirm'] . '"><i class="' . $action['icon'] . '"></i></a> </li>';
            }
        } else if ($key == 'edit') {
            if ($status !== 'VALIDATED') {
                $str .= '<li><a href="' . route($action['route'], $item->$action['id']) . '" class="bs-tooltip" data-original-title="' . $action['title'] . '"><i class="' . $action['icon'] . '"></i></a> </li>';
            }
        } else if ($key == 'visible') {
//            $str .="<a href=\"{{url('uploads/files/'.$vessel_report->document_name)}} \" target=\"_blank\"     class=\"btn pi-btn-base\" style=\"margin-right:10px;\">
            //                                            Download
            //                                        </a>";
            $document = (is_null($item->document)) ? $item->job_document : $item->document;
            $str .= '<a href="' . url($action['route'], $document) . '" target="_blank"  class="bs-tooltip" data-original-title="' . $action['title'] . '"><i class="' . $action['icon'] . '"></i></a> ';
        } else {
            $str .= '<li><a href="' . route($action['route'], $item->$action['id']) . '" class="bs-tooltip" data-original-title="' . $action['title'] . '"><i class="' . $action['icon'] . '"></i></a> </li>';
        }
    }
    $str .= '</ul></td>';
    return $str;
}

function generate_sortable_columns(array $sortableColumns)
{
    $str = '<div>' . PHP_EOL;
    $str .= '<div class="csv-sortable">' . PHP_EOL;
    foreach ($sortableColumns as $id => $readable) {
        $str .= '<a id="' . $id . '" class="btn btn-default">' . $readable . '</a>' . PHP_EOL;
    }
    $str .= '</div>' . PHP_EOL;
    $str .= '</div>' . PHP_EOL;
    return $str;
}

function generate_advance_search_table($columns, $select_array = null, $divide = 4, $start_index = 0)
{
    if (is_array($select_array)) {
        extract($select_array);
    }
    $str = '';
    $i = $start_index;
    foreach ($columns as $col => $value) {
//        dd($value);

        $str .= '<div class="col-md-' . $divide . ' form-group" id="filter_col' . $i . '" data-column="' . $i . '">' . PHP_EOL;
        $str .= '<label class="control-label ' . $col . '" >' . $value['title'] . '</label>' . PHP_EOL;
        switch ($value['type']) {
            case "select":
                //dd($$col);
                $list = $$col;
                if (is_array($list)) {
                    asort($list);
                } else {
                    $list = null;
                }
                //@adedolapo this ensures all list are sorted alphabetically, sln to 'Ensure the contents of all Listbox are sorted in ascending order.'
                $str .= Form::select(null, $list, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_filter'));
                break;
            case "text":
                $str .= Form::text(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_filter'));
                break;
            case "date":
                $str .= '<div class="input-group date">';
                $str .= Form::text(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_filter', 'placeholder' => 'Date Format: dd/mm/yyy', 'readonly'));
                $str .= ' <span class="input-group-addon"><i class="icon-calendar"></i></span></div>';
                break;
            case "date2": //use this date if you will be initializing the date somewhere else e.g for validation etc @paul
                $str .= '<div class="input-group date2">';
                $str .= Form::text(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_filter', 'placeholder' => 'Date Format: dd/mm/yyy', 'readonly'));
                $str .= ' <span class="input-group-addon"><i class="icon-calendar"></i></span></div>';
                break;
            default:
                $str .= Form::text(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_filter'));

        }
        $str .= Form::hidden(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_regex'));
        $str .= Form::hidden(null, null, array('class' => 'form-control column_filter', 'id' => 'col' . $i . '_smart'));
        $str .= '</div>' . PHP_EOL;
        $i++;

    }
    return $str;
}

function table_action_array($title, $route, $icon, $id = 'id', $confirm = 'Are you sure you want to delete this data?')
{
    return array('title' => $title, 'route' => $route, 'icon' => $icon, 'id' => $id, 'confirm' => $confirm);
}

function get_staff_type($staff)
{
    $is_exp = $staff->individual->is_exp;
    return $is_exp == 'Yes' ? 'Expatriate' : 'National';
}

function get_decorated_staff_type($staff)
{
    $is_exp = $staff->individual->is_exp;
    switch ($is_exp) {
        case 'Yes':
            return '<span class="label label-success">Yes</span>';
            break;
        case 'No':
            return '<span class="label label-danger">No</span>';
            break;
        default:
            return '<span class="label label-danger">No</span>';
    }
}

function generate_module_list($modules)
{
    $str = '<div>' . PHP_EOL;
    $str .= '<ul>' . PHP_EOL;
    foreach ($modules as $module) {
        $str .= '<li>' . $module['display_name'];
        if (isset($module['children']) && count($module['children'])) {
            foreach ($module['children'] as $c) {
                $str .= '<ul>' . PHP_EOL;
                if ($c->org_name == '') {
                    $str .= '<li>' . $c->first_name . ' ' . $c->last_name;
                } else {
                    $str .= '<li>' . $c->org_name;
                }
                $str .= '</li>' . PHP_EOL;
                $str .= '</ul>' . PHP_EOL;
            }
        }
        $str .= '</li>' . PHP_EOL;
    }
    $str .= '</ul>' . PHP_EOL;
    $str .= '</div>' . PHP_EOL;
    return $str;
}

function generate_module_list_btn($modules, $user_id)
{
    $str = '<div>' . PHP_EOL;
    $str .= '<ul>' . PHP_EOL;
    foreach ($modules as $module) {
        $str .= '<li style="margin-bottom:15px"><h5 style="font-weight: bold;">' . $module['display_name'] . '</h5>';
        if (isset($module['children']) && count($module['children'])) {
            $str .= '<table class="table table-striped table-bordered table-hover">' . PHP_EOL;
            foreach ($module['children'] as $c) {
                if ($c->org_name == '') {
                    $str .= '<tr><td style="width:50%">' . $c->first_name . ' ' . $c->last_name;
                } else {
                    $str .= '<tr><td style="width:50%">' . $c->org_name;
                }
                $str .= '</td>' . PHP_EOL;
                $str .= '<td><a href="' . route('admin.users.detach.module', [$user_id, $c->id, $module['group_name']]) . '" class="btn btn-xs btn-danger delete-me-1" data-confirm="' . trans("app.detach_user_from_module_msg") . '"><i class="icon-trash"></i> remove</a>
</td>';
            }
            $str .= '</tr></table>' . PHP_EOL;
        }
        $str .= '</li>' . PHP_EOL;
    }
    $str .= '</ul>' . PHP_EOL;
    $str .= '</div>' . PHP_EOL;
    return $str;
}

function get_profile_url($company)
{
    $type = $company->quotable_type;
    switch ($type) {
        case 'Cms\Operator\Models\Operator':
            return 'admin.operators.profile';
        case 'Cms\ServiceCompany\Models\ServiceCompany':
            return 'admin.service_coys.view';
    }
}

function get_application_expatriate_count($application)
{
    $count = $application->quotas()->sum('no_of_expatriates');
    $count += count($application->nigerianizations) + count($application->withdrawals);
    return $count;
}

function get_quotas_expatriate_count($quotas)
{
    $count = 0;
    foreach ($quotas as $quota) {
        $count += $quota->no_of_expatriates;
    }
    return $count;
}

function get_expatriate_application_last_review($application)
{
    $reviews = $application->reviews()->where('status_id', '!=', 50)->get();
    if (is_null($reviews) || !isset($reviews)) {
        return null;
    }
    $l = $reviews->sortBy(function ($r) {
        return $r->id;
    })->last();
    return $l;
}

function get_expatriate_application_review_status($application)
{
    $review = get_expatriate_application_last_review($application);
    return get_expatriate_application_review_status_r($review);
}

function get_expatriate_application_review_status_r($review)
{
    $status = (is_null($review)) ? null : $review->status;
    if (is_null($review)) {
        return '<span class="label label-primary">New Application</span>';
    }
    if ($review->status_id == 0) {
        return '<span class="label label-primary">Resubmitted Application</span>';
    }
    if (!is_null($status) && $status->code < 0) {
        return '<span class="label label-danger">' . $status->description . '</span>';
    }
    if (!is_null($status) && $status->code == 10) {
        return '<span class="label label-primary">' . $status->description . '</span>';
    }
    $description = (!is_null($status)) ? $status->description : "";
    return '<span class="label label-success">' . $description . '</span>';
}

function get_select_type($key, array $array)
{
    return $key;
    #return $array[$key];
}

function get_cut_string($string, $maxLen)
{
    if (strlen($string) > $maxLen) {
        $string = substr($string, 0, $maxLen - 3) . '...';
    }
    return $string;
}

function get_quater($month)
{
    //$curQuarter = ceil($month/3);
    switch ($month) {
        case ($month <= 3):
            return "JAN-MAR";
            break;
        case ($month > 3 and $month <= 6):
            return "APR-JUN";
            break;
        case ($month > 6 and $month <= 9):
            return "JUL-SEP";
            break;
        case ($month > 9 and $month <= 12):
            return "OCT-DEC";
            break;
    }
}

function get_current_quarter()
{
    $month = c_mnt();
    return get_quater($month);
}

function c_mnt()
{
    return date('m');
}

function c_yr()
{
    return date('Y');
}

/**
 * @return  Cms\Site\Repo\FiscalYearRepository
 */
function fiscal_years()
{
    return App::make('Cms\Site\Repo\FiscalYearRepositoryInterface');
}

/**
 * Return the user friendly date
 * @param null $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function format_date($date = null, $format = 'd/m/Y')
{
    if (is_null($date)) {
        return $date;
    }
    // @adedolapoo check if a date is null to avoid creating the present date

    $date = new DateTime($date);

    return $date->format($format);
}

function format_datetime($date = null)
{
    if (is_null($date)) {
        return $date;
    }
    // @adedolapoo check if a date is null to avoid creating the present date
    $date = new DateTime($date);
    return $date->format('d/m/Y H:i:s');
}

function format_timeago($date = null)
{
    return Carbon\Carbon::parse($date)->diffForHumans();
}

function calDaysFromTime($date)
{
    $cDate = Carbon\Carbon::parse($date);
    return $cDate->diffInDays();
}

/**
 * Return the database time
 * @param null $userDate
 * @return null|string
 */
function unformat_date($userDate = null)
{
    if (!empty($userDate)) {
        if (strpos($userDate, '-')) return $userDate;
        list($day, $month, $year) = explode('/', $userDate);
        $date = Carbon\Carbon::createFromDate($year, $month, $day);
        return $date->format('Y-m-d');
    }

    return null;
}

function get_auth_redirects($module_name)
{
    switch ($module_name) {
        case "Individual":
            return 'individual.dashboard';
            break;
        case "SystemAdmin":
            return 'system_admin.dashboard';
            break;
        case "ServiceCompany":
            return 'service_company.dashboard';
            break;
        case "Operator":
            return 'operator.dashboard';
            break;
        case "Academia":
            return 'academia.dashboard';
            break;
        case "ExternalAgent":
            return 'external_agent.dashboard';
            break;
    }
}

function get_curr_user_dashboard()
{
    $module_name = \Sentry::getUser()->getGroups()->first()->name;
    return get_auth_redirects($module_name);

}


function get_file($file, $mime_type, $otherheaders = array())
{
    $response = \Illuminate\Support\Facades\Response::make($file, 200);
    $response->header('Content-Type', $mime_type);
    return $response;
}

function set_file($data, $file_attr)
{
    if (!(is_null($data[$file_attr]) && trim($data[$file_attr]) == '')) {
        $data[$file_attr] = file_get_contents($data[$file_attr]);
    } else {
        unset($data[$file_attr]);
    }
//do not overwrite previous file data

    return $data;
}


function get_mime_type($ext)
{
    // Determine Content Type
    switch (strtolower($ext)) {
        case "pdf":
            $ctype = "application/pdf";
            break;
        case "exe":
            $ctype = "application/octet-stream";
            break;
        case "zip":
            $ctype = "application/zip";
            break;
        case "tar":
        case "tgz":
        case "tbz":
            $ctype = "application/x-tar";
            break;
        case "gz":
            $ctype = stripos($url, '.tar.gz') === false ? "application/x-gzip" : "application/x-tar";
            break;
        case "doc":
            $ctype = "application/msword";
            break;
        case "xls":
            $ctype = "application/vnd.ms-excel";
            break;
        case "ppt":
            $ctype = "application/vnd.ms-powerpoint";
            break;
        case "odt":
            $ctype = "application/vnd.oasis.opendocument.text";
            break;
        case "ods":
            $ctype = "application/vnd.oasis.opendocument.spreadsheet";
            break;
        case "odp":
            $ctype = "application/vnd.oasis.opendocument.presentation";
            break;
        case "gif":
            $ctype = "image/gif";
            break;
        case "png":
            $ctype = "image/png";
            break;
        case "jpeg":
        case "jpg":
            $ctype = "image/jpg";
            break;
        default:
            $ctype = "application/force-download";
    }
    return $ctype;
}


function case_priority()
{
    $priority = array(
        '1' => 'High',
        '2' => 'Urgent',
        '3' => 'Low',
        '4' => 'Normal',
    );
    $array = array('' => "--Select Option---");
    foreach ($priority as $key => $value) {
        $array[$value] = $value;

    }
    asort($array);
    return $array;

}


function getRequestSubCategory()
{
    $sub_category = array(
        '1' => 'Enquiry about NOGIC JQS',
        '2' => 'Enquiry about NCDMB',
        '3' => 'Others',
    );

    $array = array('' => "--- Select an option----");
    foreach ($sub_category as $key => $value) {
        $array[$value] = $value;
    }
    asort($array);
    return $array;

}


function get_search_degrees()
{
    $degrees = array(
        '1' => 'GCE',
        '2' => 'NECO',
        '3' => 'NABTEB',
        '4' => 'WAEC',
        '5' => 'Distinction',
        '6' => 'Upper Credit',
        '7' => 'Lower Credit',
        '8' => 'Pass',
        '9' => 'Merit',
        '10' => 'First Class',
        '11' => 'Second Class Upper',
        '12' => 'Second Class Lower',
        '13' => 'Third Class',
        '14' => 'Ordinary Degree (PASS)',
        '15' => 'Honors Degree',
        '16' => 'Others',
    );

    $array = array('' => "--- Select an option----");
    foreach ($degrees as $key => $value) {
        $array[$value] = $value;
    }

    return $array;
}

function save_file_to_oracle_db($table, $col, $id, $lob_data)
{
    $pdo = \DB::connection('oracle')->getPdo();
    $sql = 'UPDATE ' . $table . ' SET ' . $col . ' = EMPTY_BLOB() WHERE ID = ' . $id . ' RETURNING ' . $col . ' INTO :blob';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':blob', $lob, \PDO::PARAM_LOB);
    $stmt->execute();
    $lob->save($lob_data);
}


function getAgeDiff($individual)
{
    $date = date('Y');
    $age = $date - $individual->dob_year;
    return $age;
}

function get_year($date)
{
    return date('Y', strtotime($date));
}

function getUserNameFromId($id)
{
    if (is_null($id)) {
        return '';
    }

    $user = Sentry::findUserById($id);
    return $user->first_name . ' ' . $user->last_name;
}

//Turn database date to human friendly date in the form of Jan 01, 2001
function get_date($date)
{
    return date('M d, Y', strtotime($date));
}

// Make database friendly date in the form of 2001-10-21
function make_date($date_string)
{
    return date('Y-m-d', strtotime($date_string));
}


function subject_type($subject_type)
{
    switch ($subject_type) {
        case 'SERVICE_COY':
            return 'Service Company';
        case 'OPERATOR':
            return 'Operator';
        case 'OTHERS':
            return 'Others';
        default:
            return 'N/A';
    }
}

function creator_type($creator_type)
{
    switch ($creator_type) {
        case 'SERVICE_COY':
            return 'Service Company';
        case 'OPERATOR':
            return 'Operator';
        case 'INDIVIDUAL':
            return 'Individual';
        case 'OTHERS':
            return 'Others';
        default:
            return 'N/A';
    }
}

function ind_fullname($ind)
{
    return $ind->first_name . ' ' . $ind->last_name;
}

function captcha()
{
    $app = app();
    $obj = $app['captcha'];
    return $obj->display();
}


function get_email_template($type)
{
    $model = app('Cms\SystemAdmin\Repo\EmailTemplateInterface');
    try {
        $attachment = $model->findBySingle('type', $type);
    } catch (\Exception $e) {
        $attachment = null;
    }
    return $attachment;
}

function stringify_email_template($type, $data)
{
    $template = get_email_template($type);
    //check for null pointers ..paul
    if ($template !== null) {
        $subject = $template->subject;
        $body = \StringView::make(['template' => $template->body, 'cache_key' => $template->type, 'updated_at' => 0], $data);
        return [$subject, $body];
    }
    return null;
}

function str_words($string, $limit)
{
    return \Str::words($string, $limit, '</div>');
}

function get_menu($menu, $type)
{
    $new_array = [];
    foreach ($menu as $m) {
        if ($m['group']['slug'] == $type) {
            $new_array[] = $m;
        }
    }
    return $new_array;
}

function get_emp_status()
{

    $emp = array(
        '1' => 'Employed',
        '2' => 'Unemployed',
        '3' => 'Self Employed',
    );

    $array = array('' => "--- Select an option---");
    foreach ($emp as $key => $value) {
        $array[$value] = $value;
    }

    return $array;

}

function get_sole_source_stages()
{

    $emp = array(
        '1' => 'Commercial Template',
        '2' => 'Comm Eval',
        '3' => 'NCCC',
    );

    $array = array('' => "--- Select A Stage ---");
    foreach ($emp as $key => $value) {
        $array[$value] = $value;
    }

    return $array;

}


/**
 * @param $message
 * @param $record
 * @param $data
 * @param $route
 * @return \Illuminate\Http\RedirectResponse
 * @author Olusola Akinmolayan
 * @date 26-01-2015
 * @comment DRY {Dont Repeat Yourself} this can be used for all list views with highlights
 */
function redirectOnSuccess($message, $record, $data, $route, $id)
{
    Notification::success($message);
    $successData = array("record_id" => $record->id);
    $redirect = (isset($data['save_exit'])) ?
        Redirect::route($route, array())->with($successData) : Redirect::route($route, array($id))->with($successData);
    return $redirect;
}

function get_profile_navigation($type, $id)
{
    $str = '';
    $nav_array = Config::get('modules/profile_nav/' . $type);
    foreach ($nav_array as $nav) {
        $is_active = (Request::is($nav['active'])) ? 'active' : '';
        $route = $nav['route'];
        if (!empty($route)) {
            $route = is_array($route) ? route($route[0], $route[1]) : route($route);
        } else {
            $route = "javascript:void(0);";
        }
        $interface = isset($nav['interface']) ? $nav['interface'] : null;
        if ($type == 'operator' && $nav['route'] == 'admin.staff.index') {
            $str .= '<a href="' . route($nav['route'], [$id, 'Operator']) . '" class="list-group-item ' . $is_active . '"><strong><i class="icon-caret-right"></i> ' . $nav['label'] . '</strong></a>';
        } else {
            $str .= '<a href="' . $route . '/' . $id . '" class="list-group-item ' . $is_active . '"><strong><i class="icon-caret-right"></i> ' . $nav['label'] . ' ' . getLinkCount($interface, $id, $type) . '</strong></a>';
        }

    }
    return $str;
}

/*
 * @author: Ajayi Gbeminiyi
 * @date: 12/10/2016
 * @desc: Count functionality for the profile navigation
 * */
function getLinkCount($interface, $id, $type)
{
    if (isset($interface) && !is_null($interface)) {
        $model = App($interface);
        if ($type == 'operator') {
            return ' (' . $model->operatorRecordCount($id) . ')';
        } else {

            return ' (' . $model->findCount($id) . ')';
        }

    }
}

function range_dropdown($start, $end, $title = 'year')
{
    $array = range($start, $end);
    $new_array = array('' => '-- select ' . $title . ' --');
    foreach ($array as $key => $value) {
        $new_array[$value] = $value;
    }
    return $new_array;
}

/* Ajayi Gbeminiyi*/
function getTitle()
{
    return array('' => '--Select--', 'Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Engr.' => 'Engr.', 'Dr' => 'Dr', 'Prof.' => 'Prof.');
}

function researchStatus()
{
    return array(
        '' => '--Select--',
        'Proposed' => 'Proposed',
        'Ongoing' => 'Ongoing',
        'Concluded' => 'Concluded',
    );
}

function generate_mpp_code($start, $end)
{
    //MPP-C30087Q/2008-2015
    $code = 'MPP-C';
    $rand = rand(1, 4);
    $code .= $rand . 'Q/';
    $code .= $start . '-' . $end;
    return $code;

}

function index_exists($index)
{
    if (isset($index) && !empty($index)) {
        return true;
    }

    return false;
}

function get_sex_array()
{
    return array('' => '-- Select --', 'Male' => 'Male', 'Female' => 'Female');
}

function get_belonged_to_coy_detail($data)
{
    $data = json_decode($data);
    return $data->coy_name;
}


function get_eq_application_due_date($approval_date)
{
    $expiry_date = Carbon\Carbon::parse($approval_date)->addMonths(24)->subDay();
    return $expiry_date->toFormattedDateString();
}

function eq_application_is_due($approval_date)
{
    $expiry_date = Carbon\Carbon::parse($approval_date)->addMonths(18);
    $now = Carbon\Carbon::now();
    return $now->greaterThanOrEqualTo($expiry_date);
}

function format_number($number)
{
    if ($number) {
        return number_format($number, 2, '.', ',');
    }
}

function unformat_number($number)
{
    return (float)str_replace(',', '', $number);
}

function get_app_review_status_label($status, $position)
{
    if ($status == 1) {
        if (is_null($position)) {
            $msg = 'Approved';
        } else {
            if ($position->code == 'SP' or $position->code == 'PO') {
                $msg = 'Recommended for Approval by ' . $position->name;
            } else {
                $msg = 'Approved by ' . $position->name;
            }
        }
        return '<span class="label label-success">' . $msg . '</span>';
    } elseif ($status == 5) {
        return '<span class="label label-warning">Recommended for Inspection By Processing Officer</span>';
    } elseif ($status == -2) {
        return '<span class="label label-warning">Recommended for Rejection By Processing Officer</span>';
    } elseif ($status == -1) {
        if (is_null($position)) {
            $msg = 'Returned';
        } else {
            if ($position->code == 'SP' or $position->code == 'PO') {
                $msg = 'Recommended for Rejection by ' . $position->name;
            } else {
                $msg = 'Returned by ' . $position->name;
            }
        }
        return '<span class="label label-danger">' . $msg . '</span>';
    } elseif ($status == 10) {
        return '<span class="label label-primary">Not Submitted</span>';
    } else {
        return '<span class="label label-primary">Resubmitted Applicaton</span>';
    }
}

function check_user_has_position($position_code, $user = NULL)
{
    if (is_null($user)) $user = \Sentry::getUser();

    if (!is_null($user->position)) {
        if (is_array($position_code)) return in_array($user->position->code, $position_code);
        return ($user->position->code == $position_code);
    }

    return false;
}

function get_app_last_review($app)
{
    $reviews = $app->reviews;
    if (!empty($reviews)) {
        return $reviews->sortBy(function ($r) {
            return $r->id;
        })->last();

    }
}

function check_user_password_validity($user = null)
{
    if (is_null($user)) {
        $user = Sentry::getUser();
    }

    if ($user->passwordHistory->count()) {
        $date = $user->passwordHistory->first()->expiry_date;
        $expiry_date = Carbon\Carbon::parse($date);
        $now = Carbon\Carbon::now();

        return $now->greaterThanOrEqualTo($expiry_date);
    }

    return false;
}

function get_generic_documents_by_type($type_slug, $with_slugs = [], $exempt = [])
{
    try {
        $type = app("Cms\SystemAdmin\Repo\GenericDocumentTypeRepositoryInterface")->findByFirst('slug', $type_slug);
        $documents = $type->documents;
        if (!empty($type->parent_id)) $with_slugs[] = $type->parent->slug;
        if (is_array($with_slugs) && count($with_slugs)) {
            $types = app("Cms\SystemAdmin\Repo\GenericDocumentTypeRepositoryInterface")->findByIn('slug', $with_slugs, ['documents']);
            foreach ($types as $type) {
                if (!empty($type->documents)) $documents = $documents->merge($type->documents);
            }
        }
        if (!empty($exempt)) {
            return $documents->filter(function ($document) use ($exempt) {
                return !in_array($document->slug, $exempt);
            });
        }
        return $documents;
    } catch (\Exception $e) {
        return [];
    }
}

function get_generic_documents_uploaded($model)
{
    $documents = $model->documentFiles;
    $uploaded_documents = [];
    if ($documents) {
        foreach ($documents as $document) {
            $uploaded_documents[] = [
                'document_id' => (int)$document->document_id,
                'file_path' => $document->file_path
            ];
        }
    }
    return $uploaded_documents;
}

function get_generic_documents_files($uploaded_documents, $document_id)
{
    if (count($uploaded_documents)) {
        $documents = [];
        foreach ($uploaded_documents as $document) {
            if ($document['document_id'] == $document_id) {
                $documents[] = $document;
            }
        }
        return $documents;
    }
    return [];
}

function generate_unque_id($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand(0, $limit))), 16, 36), 0, $limit);
}

function get_case_actions($case, $case_type, $module, $user)
{
    $html = '';
    $html .= '<div class="btn-group">';
    if ($case_type == 'unassigned') {
        $html .= '<a href="' . $case['app_unassigned_url'] . '" class="btn btn-xs btn-success"><i class="icon-share"></i> Claim Task </a>';
    } else {
        if (isset($case['app_reassign_url']) && is_admin_module($module) && $case['assigned_user_id'] == $user->wf_user_id) {
            $html .= '<button class="btn btn-xs btn-inverse dropdown-toggle" data-toggle="dropdown"> <i class="icon-cog"></i> options <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<li>
					<a href="' . $case['app_url'] . '">
						<i class="glyphicon glyphicon-eye-open"></i> View Task
					</a>
				</li>
				<li>
					<a data-href="' . $case['app_reassign_url'] . '"  data-toggle="modal"
					href="#AssignForm" data-remote="false">
						<i class="glyphicon glyphicon-refresh"></i>';
            $html .= get_workflow_assign_label($user);
            $html .= '
					</a>
				</li>
			</ul>';
        } else {
            $html .= '<a href="' . $case['app_url'] . '" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-eye-open"></i>View Task</a>';
        }
    }
    $html .= '</div>';

    return $html;
}


function get_file_upload_size()
{
    $value = (int)config('nogic.file_size', 2);
    return 1024 * 1024 * $value;
}

function get_projects_status_label($status)
{
    if ($status == "NotSubmitted") {
        return '<span class="badge rounded-pill bg-danger">NOT SUBMITTED</span>';
    } elseif ($status == "Processing") {
        return '<span class="badge rounded-pill bg-success">Processing</span>';
    } elseif ($status == "CostFixed") {
        return '<span class="badge rounded-pill bg-blue-500">Payment Requested</span>';
    } elseif ($status == "UnderReview") {
        return '<span class="badge rounded-pill bg-blue-200">UNDER REVIEW</span>';
    } elseif ($status == "Finished") {
        return '<span class="badge rounded-pill bg-blue-400">FINISHED<span>';
    } else {
        return '<span class="badge rounded-pill bg-primary">PENDING</span>';
    }

}

function get_projects_status($status)
{
    if ($status == 1) {
        return 'NotSubmitted';
    } elseif ($status == 2) {
        return 'UnderReview';
    } elseif ($status == 3) {
        return 'Processing';
    } elseif ($status == 4) {
        return 'Finished';
    } else {
        return 'PENDING';
    }

}

function get_appointment_status($status)
{
    if ($status == 1) {
        return 'Submitted';
    } elseif ($status == 2) {
        return 'shchedule';
    } elseif ($status == 3) {
        return 'NotSubmitted';
    } else {
        return 'Declined';
    }

}

function get_appointment_status_label($status)
{
    if ($status == 'Submitted') {
        return '<span class="badge rounded-pill bg-warning">SUBMITTED</span>';
    } elseif ($status == 'shchedule') {
        return '<span class="badge rounded-pill bg-blue-500">SCHEDULED</span>';
    } elseif ($status == 'NotSubmitted') {
        return '<span class="badge rounded-pill bg-danger">NOT SUBMITTED</span>';
    } else {
        return '<span class="badge rounded-pill bg-red-500">DECLINED</span>';
    }

}

function getProjectUnderReviewCount($userObj,$currentWorkspace){

    if ($userObj->type == 'user' || $userObj->type == 'admin'){

        return   ClientProject::join("projects", "projects.project_id", "=", "client_projects.project_id")
            ->where('projects.status', '=',get_projects_status(2))
            ->count();

//        return    UserProject::join("projects", "projects.project_id", "=", "user_projects.project_id")
//            ->where("user_id", "=", $userObj->id)
//            ->where('projects.workspace', '=', $currentWorkspace->id)
//            ->where('projects.status', '=',get_projects_status(2))
//            ->count();
    }else{
        return   ClientProject::join("projects", "projects.project_id", "=", "client_projects.project_id")
            ->where("client_id", "=", $userObj->id)
            ->where('projects.workspace', '=', $currentWorkspace->id)
            ->where('projects.status', '=',get_projects_status(4))
            ->count();
    }

}


function report_exception($exception, $severity = 'info')
{
    if (\Config::getEnvironment() == 'production') {
        \Bugsnag::notifyException($exception, null, $severity);
    }
    \Log::error($exception);
}

function merge_array($array)
{

    $result = [];
    foreach ($array as $arr) {
        $result = array_merge($result, $arr);
    }

    return $result;

}



function get_year_in_decimal($number)
{

    if (empty($number)) return '---';

    $number_array = explode('.', (string)$number);
    if (count($number_array) > 1) {
        list($int, $dec) = $number_array;
        $years = ($int > 1) ? $int . " years" : (($int < 1) ? '' : $int . " year");
        $month = ($dec > 1) ? $dec . " months" : (($dec < 1) ? '' : $dec . " month");
        return $years . ' ' . $month;
    } else {
        return ($number > 1) ? $number . " years" : $number . " year";
    }
}

function generate_statutory_report_code($org_name)
{
    if (strlen($org_name) >= 4)
        return strtoupper(substr($org_name, 0, 4)) . date('dmyhi');
    else
        return strtoupper(str_pad($org_name, 4, '#')) . date('dmyhi');
}

function validate_editable($model, $status = 'app_status')
{
    if (empty($model)) return false;
    return (is_null($model->wf_case_id) || $model->$status == -1);
}

function replace_slug_delimiter($slug, $delimiter = '-', $replacement = '_')
{
    return str_replace($delimiter, $replacement, $slug);
}

function get_document_formats($formats)
{
    $all_formats = config('nogic.document_formats');
    $formats = !empty($formats) ? array_only($all_formats, $formats) : $all_formats;
    return implode(', ', array_flatten(array_values($formats)));
}

function get_workflow_group_sync_msg($message, $type = 'success')
{
    return '<div class="alert alert-' . $type . '"><i class="icon-remove close"
                                                                       data-dismiss="alert"></i>
    ' . $message . '
</div>';
}

function get_validation_tasks_status_label($app, $status = 'app_status')
{
    if ($app->wf_case_id == '' && !$app->$status) {
        return '<span class="label label-warning">NOT SUBMITTED</span>';
    } else {
        if ($app->$status == 1) {
            return '<span class="label label-info">VALIDATED</span>';
        } elseif ($app->$status == -1) {
            return '<span class="label label-danger">RETURNED</span>';
        } elseif ($app->$status == 2) {
            return '<span class="label label-success">UNDER REVIEW</span>';
        } else {
            return '<span class="label label-primary">PENDING VALIDATION</span>';
        }
    }
}

function get_module_super_user($model)
{
    $users = $model->users;

    if (empty($users)) return null;

    if (count($users) > 1) {
        return $model->users()->where('email', $model->email)->first();
    } else {
        return $model->users()->first();
    }
}


function generate_qr_code($code)
{
    $qr_code = str_replace('/', '', \DNS2D::getBarcodePNGPath($code, 'QRCODE', 4, 4));
    return str_replace('qr-codes', '/qr-codes/', $qr_code);
}

function generate_code($prefix = null)
{
    $unique_no = rand(000000, 999999);
    if (!empty($prefix)) return $prefix . $unique_no;
    return $unique_no;
}

function generic_document_files_create($model, $uploaded_documents)
{
    $documents = !is_null($uploaded_documents) ? json_decode($uploaded_documents, true) : [];

    if (count($documents)) {
        $model->documentFiles()->delete();
        foreach ($documents as $document) {
            $model->documentFiles()->create($document);
        }
    }
}

function get_generic_document_review_status_dropdown()
{
    return [
        '' => 'N/A',
        0 => 'Not Okay',
        1 => 'Okay',
    ];
}

function get_generic_document_review_by_doc_id($reviews, $document_id)
{
    return $reviews->filter(function ($review) use ($document_id) {
        return $review->document_id == $document_id;
    })->first();
}

function get_generic_document_review_status($review_status)
{
    if ($review_status == '') {
        return '<span class="label label-warning">NOT REVIEWED</span>';
    } elseif ($review_status == 0) {
        return '<span class="label label-danger">NOT OKAY</span>';
    } else {
        return '<span class="label label-success">OKAY</span>';
    }
}

function get_certificate_is_due($expiry_date)
{
    if (!empty($expiry_date)) {
        $expiry_date = Carbon\Carbon::parse($expiry_date);
        $now = Carbon\Carbon::now();
        return $now->greaterThanOrEqualTo($expiry_date);
    } else {
        return false;
    }
}


function generate_pament_txn_id($prefix)
{
    return $prefix . substr(rand(0, time()), 0, 7);
}

function getAppointmentPayment($response,$currentWorkspace)
{
//    $chek_info = AppointmentInvoicePayments::where('user_id',$response['data']['metadata']['user_id'])->first();
//    if (!$chek_info){
        $payment_info = new AppointmentInvoicePayments();
        $payment_info->user_id = $response['data']['metadata']['user_id'];
        $payment_info->currency = $response['data']['currency'];
        $payment_info->amount_paid = $response['data']['amount'];
        $payment_info->txn_id = generate_code('APPOINT');
        $payment_info->payment_type = 'appointment';
        $payment_info->payment_status = 'paid';
        $payment_info->payment_by =  $response['data']['metadata']['email'];
        $payment_info->chance = 3;
        $payment_info->save();
}
