<?php

namespace App\Http\Requests\Admin\Logs;

use Tripteki\Helpers\Http\Requests\FormValidation;

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

            "log" => "required|string|exists:".get_class($provider).",".keyName($provider),
        ];
    }
};
