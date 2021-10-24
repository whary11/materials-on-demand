<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOrderDetailsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'order_details';
    /**
     * Run the migrations.
     * @table order_details
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('reference_id');
            $table->string('reference_name', 45);
            $table->double('price');
            $table->integer('quantity');
            $table->double('price_with_discount');
            $table->unsignedInteger('puchase_status_id');
            $table->timestamps();


            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('puchase_status_id')
                ->references('id')->on('purchase_statuses')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('reference_id')
                ->references('id')->on('references')
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
