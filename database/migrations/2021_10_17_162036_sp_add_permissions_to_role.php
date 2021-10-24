<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpAddPermissionsToRole extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_add_permissions_to_role"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_role_id INT, p_permission TEXT, p_now DATETIME)
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
                
                    # Validar si el rol ya tiene este permiso
                    SELECT COUNT(r.id) FROM roles r
                            JOIN role_permissions rp ON rp.role_id = r.id
                            JOIN permissions p ON p.id = rp.permission_id
                    WHERE p.`name` = p_permission INTO @validate_role;
        
                    IF @validate_role > 0 THEN
                            CALL ksp_response(FALSE,"El rol ya tiene este permiso asignado.");
                    ELSE
                            # Validar que el permiso exista
                            SELECT COUNT(p.id) FROM permissions p 
                            WHERE p.name = p_permission 
                            INTO @validate_permision;
        
                            IF @validate_permision > 0 THEN	
                                SELECT p.id FROM permissions p 
                                WHERE p.name = p_permission 
                                LIMIT 1 INTO @permission_id;
                                
                                INSERT INTO `role_permissions`(`permission_id`, `role_id`, `created_at`, `updated_at`) VALUES (@permission_id, p_role_id, p_now, p_now);
                                CALL ksp_response(TRUE,"Permiso agregado.");
                            ELSE
                                CALL ksp_response(FALSE,"El permiso no existe.");
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
