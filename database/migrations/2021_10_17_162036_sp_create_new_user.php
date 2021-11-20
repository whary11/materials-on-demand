<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpCreateNewUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_create_new_user"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_name TEXT, p_last_name TEXT, p_email TEXT, p_password TEXT,p_is_anonymous INT, p_now DATETIME)
        BEGIN
        
            DECLARE EXIT HANDLER FOR SQLEXCEPTION
            BEGIN
                    SHOW ERRORS;
                    ROLLBACK;   
            END;
            DECLARE EXIT HANDLER FOR SQLWARNING
            BEGIN
                    SHOW ERRORS;  
                    ROLLBACK;   
            END;
            START TRANSACTION;  
                INSERT INTO `users`(`name`, `last_name`, `email`, `password`, `is_anonymous`, `created_at`, `updated_at`) 
                VALUES (p_name, p_last_name, p_email, p_password, 0, p_now, p_now);
                
                
                CALL ksp_response(TRUE, LAST_INSERT_ID());
                
            COMMIT;
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
