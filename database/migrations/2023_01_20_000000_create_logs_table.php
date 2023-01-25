<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Log\Providers\LogServiceProvider;

class CreateLogsTable extends Migration
{
    /**
     * @var string
     */
    protected $keytype;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->keytype = app(AuthModelContract::class)->getKeyType();
    }

    /**
     * @return void
     */
    public function up()
    {
        $keytype = $this->keytype;

        Schema::connection(config("activitylog.database_connection"))->create(config("activitylog.table_name"), function (Blueprint $table) use ($keytype) {

            $table->uuid("id");
            $table->string("log_name")->nullable();
            $table->text("description");

            if (! LogServiceProvider::shouldSubjectUid()) {

                $table->nullableMorphs("subject", "subject");

            } else {

                $table->nullableUuidMorphs("subject", "subject");
            }

            if ($keytype == "int") {

                $table->nullableMorphs("causer", "causer");

            } else if ($keytype == "string") {

                $table->nullableUuidMorphs("causer", "causer");
            }

            $table->uuid("batch_uuid")->nullable();
            $table->json("properties")->nullable();
            $table->string("event")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary("id");
            $table->index("log_name");
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::connection(config("activitylog.database_connection"))->dropIfExists(config("activitylog.table_name"));
    }
};
