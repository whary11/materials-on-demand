<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpGetHeadquartersNotUser extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_get_headquarters_not_user"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_user_id INT, p_search TEXT)
        BEGIN
            SELECT h.id, h.name FROM headquarters h
                JOIN user_headquarters uh ON uh.headquarter_id = h.id
                JOIN users u ON u.id = uh.user_id
            WHERE uh.headquarter_id NOT IN (SELECT h.id FROM headquarters h
                JOIN user_headquarters uh ON uh.headquarter_id = h.id
            WHERE uh.user_id = p_user_id) AND h.`name` LIKE CONCAT("%",p_search,"%") GROUP BY h.id;
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
