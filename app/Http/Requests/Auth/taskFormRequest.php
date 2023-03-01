<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;


class taskFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|array|max:255',
            'title.*' => 'required|string|max:255',

            'start_date' => 'required|array|max:255',
            'start_date.*' => 'required|date|max:255',

            'due_date' => 'required|array|max:255',
            'due_date.*' => 'required|date|max:255',

//            'priority' => 'array|max:255',
//            'priority.*' => 'string|max:255',

            'task_id' => 'required|max:255',

            'description' => 'required|array|max:255',
            'description.*' => 'required|string|max:255',

        ];
    }

}
