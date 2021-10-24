<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SpAddRoleToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_add_role_to_user"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_user_headquarter_id INT, p_role_id INT, p_now DATETIME)
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
                    # Validar si el usuario ya tiene este rol
                    SELECT COUNT(uh.id) FROM user_headquarters uh
                        JOIN user_roles ur ON ur.user_headquarter_id = uh.id
                    WHERE uh.id = p_user_headquarter_id AND ur.role_id = p_role_id
                    INTO @validate_user_headquarter;
                
                    IF @validate_user_headquarter > 0 THEN
                        CALL ksp_response(FALSE,"El usuario tiene este rol.");
                    ELSE
                        # Validar que la sede exista
                        SELECT COUNT(r.id) FROM roles r
                        WHERE r.id = p_role_id
                        INTO @validate_role;
                
                        IF @validate_role > 0 THEN	
                            INSERT INTO `user_roles`(`role_id`, `user_headquarter_id`, `created_at`, `updated_at`) VALUES (p_role_id, p_user_headquarter_id,p_now,p_now);
                            CALL ksp_response(TRUE,"Rol agregado.");
                        ELSE
                            CALL ksp_response(FALSE,"El rol no existe.");
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
