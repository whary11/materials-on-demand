<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpGetPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_get_permissions"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_search TEXT, p_user_id INT, p_type VARCHAR(100))
            COMMENT "Devuelve roles o permisos segun el type que reciba"
        BEGIN
    IF p_type = "permissions" THEN 
        SELECT p.id, p.name, p.description FROM permissions p
        WHERE p.name LIKE CONCAT("%",p_search,"%%") OR p.description LIKE CONCAT("%",p_search,"%%");
    ELSEIF p_type = "roles" THEN 
        SELECT r.id, r.name, r.description FROM roles r
        WHERE r.name LIKE CONCAT("%",p_search,"%%") OR r.description LIKE CONCAT("%",p_search,"%%");
    END IF;
END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name;");
    }
}
