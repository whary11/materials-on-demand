<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePromotionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'promotions';
    /**
     * Run the migrations.
     * @table promotions
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 45);
            $table->unsignedInteger('destiny_type')->comment('1=categoria, 2=marca, 3= tags, 4=producto');
            $table->unsignedInteger('destiny_id');
            $table->integer('value');
            $table->integer('permitted_uses')->default('1');
            $table->integer('quantity_uses')->default('0');
            $table->dateTime('start_date');
            $table->dateTime('final_date');
            $table->unsignedInteger('status_id');
            $table->timestamps();


            $table->foreign('status_id')
                ->references('id')->on('statuses')
                ->onDelete('restrict')
                ->onUpdate('restrict');
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
