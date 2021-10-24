<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateTimeZonesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'time_zones';
    /**
     * Run the migrations.
     * @table time_zones
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 80);
            $table->unsignedInteger('headquarter_id');
            $table->unsignedInteger('status_id');
            $table->integer('sorter');
            $table->date('day');
            $table->string('start_time', 45);
            $table->string('final_time', 45);
            $table->timestamps();


            $table->foreign('headquarter_id')
                ->references('id')->on('headquarters')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('status_id')
                ->references('id')->on('statuses')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
