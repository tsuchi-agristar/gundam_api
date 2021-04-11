<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class FrontRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $method = $this->getMethod();
        switch ($method) {
            case 'GET':
                if (Route::currentRouteName() === 'front.attack') {
                    $rules = [
                        "sid" => [
                            "required",
                            "max:50",
                        ],
                        "language" => [
                            "required",
                            Rule::in(config('const.language')),
                        ],
                        "team" => [
                            "required",
                            Rule::in(config('const.team')),
                        ],
                    ];
                }
                if (Route::currentRouteName() === 'front.result') {
                    $rules = [
                        "sid" => [
                            "required",
                        ],
                    ];
                }
                if (Route::currentRouteName() === 'front.participants') {
                    $rules = [
                        "team" => [
                            "required",
                            Rule::in(config('const.team')),
                        ],
                    ];
                }
                break;
        }
        return $rules;
    }
}
