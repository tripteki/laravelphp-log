<?php

return [

    "enabled" => true,

    "default_log_name" => "log",

    "activity_model" => Tripteki\Log\Models\Admin\Log::class,
    "table_name" => "logs",
    "database_connection" => null,

    "default_auth_driver" => null,

    "subject_returns_soft_deleted_models" => false,

    "delete_records_older_than_days" => 365,
];
