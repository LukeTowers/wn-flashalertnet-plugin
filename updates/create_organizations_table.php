<?php namespace LukeTowers\FlashAlertNet\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('luketowers_flashalertnet_organizations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('api_org_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('luketowers_flashalertnet_organizations');
    }
}
