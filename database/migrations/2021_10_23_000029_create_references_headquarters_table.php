<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateReferencesHeadquartersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'references_headquarters';
    /**
     * Run the migrations.
     * @table references_headquarters
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('reference_id');
            $table->unsignedInteger('headquarter_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('on_demad')->comment('1=Es on demand
1=No es on demand');
            $table->unsignedInteger('delivery_days')->comment('Días de entrega en el caso de que sea producto on demand.');
            $table->double('cost_price');
            $table->double('price');
            $table->unsignedInteger('stock');
            $table->unsignedInteger('reserve_stock')->default('0');
            $table->timestamps();


            $table->foreign('headquarter_id')
                ->references('id')->on('headquarters')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('reference_id')
                ->references('id')->on('references')
                ->onDelete('restrict')
                ->onUpdate('restrict');

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
