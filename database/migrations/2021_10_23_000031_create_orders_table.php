<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateOrdersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'orders';
    /**
     * Run the migrations.
     * @table orders
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('phone_id');
            $table->unsignedInteger('address_id');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('puchase_status_id');
            $table->unsignedInteger('payment_method_id');
            $table->unsignedInteger('time_zone_id');
            $table->double('total');
            $table->string('platform');
            $table->timestamps();


            $table->foreign('address_id')
                ->references('id')->on('addresses')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('phone_id')
                ->references('id')->on('phones')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('puchase_status_id')
                ->references('id')->on('purchase_statuses')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('payment_method_id')
                ->references('id')->on('payment_methods')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('time_zone_id')
                ->references('id')->on('time_zones')
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
