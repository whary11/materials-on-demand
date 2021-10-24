<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProductsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'products';
    /**
     * Run the migrations.
     * @table products
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('meta_title', 100);
            $table->text('meta_description');
            $table->text('meta_tags');
            $table->string('slug');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('type_id')->default('1');
            $table->unsignedInteger('brand_id');
            $table->integer('number_activities')->default('1');
            $table->dateTime('apdated_at');
            $table->timestamps();


            $table->foreign('status_id')
                ->references('id')->on('statuses')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('type_id')
                ->references('id')->on('types')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('brand_id')
                ->references('id')->on('brands')
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
