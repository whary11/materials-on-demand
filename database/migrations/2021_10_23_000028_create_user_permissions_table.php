<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUserPermissionsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'user_permissions';
    /**
     * Run the migrations.
     * @table user_permissions
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {           
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_headquarter_id');
            $table->unsignedInteger('permission_id');
            $table->timestamps();


            $table->foreign('user_headquarter_id')
                ->references('id')->on('user_headquarters')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('permission_id')
                ->references('id')->on('permissions')
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
