<?php namespace LukeTowers\FlashAlertNet\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

class CreateAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('luketowers_flashalertnet_alerts', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('organization_id')->unsigned()->index();
            $table->string('guid');
            $table->string('type')->default('news');
            $table->string('title')->nullable();
            $table->text('content');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('luketowers_flashalertnet_alerts');
    }
}
