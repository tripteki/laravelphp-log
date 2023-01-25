<?php

namespace App\Http\Requests\Logs;

use Illuminate\Validation\Rule;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Http\Requests\FormValidation;
use Illuminate\Support\Facades\Auth;

class LogUpdateValidation extends FormValidation
{
    /**
     * @const string
     */
    const ARCHIVE = "archive";

    /**
     * @const string
     */
    const UNARCHIVE = "unarchive";

    /**
     * @return void
     */
    protected function preValidation()
    {
        return [

            "context" => $this->route("context"),
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

            "context" => "required|string|in:".self::ARCHIVE.",".self::UNARCHIVE,
            "logs" => "required|array",
            "logs.*" => [

                Rule::exists(get_class($provider), keyName($provider))->where(function ($query) {

                    return $query->where("causer_type", get_class(app(AuthModelContract::class)))->where("causer_id", Auth::id());
                }),
            ],
        ];
    }
};
