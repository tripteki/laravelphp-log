<?php

namespace App\Http\Requests\Logs;

use Illuminate\Validation\Rule;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Http\Requests\FormValidation;
use Illuminate\Support\Facades\Auth;

class LogShowValidation extends FormValidation
{
    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "log" => $this->route("log"),
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        $provider = \Spatie\Activitylog\ActivitylogServiceProvider::getActivityModelInstance();

        return [

            "log" => [

                "required",
                "string",
                Rule::exists(get_class($provider), keyName($provider))->where(function ($query) {

                    return $query->where("causer_type", get_class(app(AuthModelContract::class)))->where("causer_id", Auth::id());
                }),
            ],
        ];
    }
};
