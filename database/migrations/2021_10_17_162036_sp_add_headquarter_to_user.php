<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SpAddHeadquarterToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_add_headquarter_to_user"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_user_id INT, p_headquarter_id INT, p_now DATETIME)
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
                                
                                        # Validar si el usuario ya tiene esta sede
                                        SELECT COUNT(uh.id) FROM user_headquarters uh
                                        WHERE uh.headquarter_id =  p_headquarter_id
                                            AND uh.user_id = p_user_id
                                        INTO @validate_user_headquarter;
                
                                        IF @validate_user_headquarter > 0 THEN
                                                        CALL ksp_response(FALSE,"El usuario tiene esta sede asignada.");
                                        ELSE
                                            # Validar que la sede exista
                                            SELECT COUNT(h.id) FROM headquarters h 
                                            WHERE h.id = p_headquarter_id
                                            INTO @validate_headquarter;
                
                                            IF @validate_headquarter > 0 THEN	
                
                                                    INSERT INTO `keny`.`user_headquarters`(`user_id`, `headquarter_id`, `created_at`, `updated_at`) VALUES (p_user_id, p_headquarter_id, p_now, p_now);
                                                    CALL ksp_response(TRUE,"Sede asignada.");
                                            ELSE
                                                    CALL ksp_response(FALSE,"La sede no existe.");
                                            END IF;
                                        END IF;
                                        
                                        
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
