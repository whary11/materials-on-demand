<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SpCreateToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_create_token"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_uuid text, p_token_expires varchar(100), p_token_refresh_exp varchar(100), p_user_id int)
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
                INSERT INTO `oauth_access_tokens` VALUES (null, p_uuid,p_user_id,0,now(),now(),p_token_expires);
                INSERT INTO `oauth_refresh_tokens` VALUES (null, last_insert_id(),0, p_token_refresh_exp, now(), now());
            COMMIT;
            CALL ksp_response(true,"Usuario registrado correctamente");  
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
