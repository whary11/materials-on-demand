<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpAddPermissionsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_add_permissions_to_user"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_user_headquarter_id INT, p_permission TEXT, p_now DATETIME)
        COMMENT "Agrega permisos a un usuario dentro de una sede"
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
        
            SELECT COUNT(id) FROM user_permissions up 
            JOIN permissions p ON p.id = up.permission_id
            WHERE up.user_headquarter_id = p_user_headquarter_id AND p.`name` = p_permission INTO @validate_permission_exist;
    
            IF @validate_permission_exist = 0 THEN 
                CALL ksp_response(FALSE, "El usuario ya tiene el permiso en esta bodega.");
            ELSE
                SET @permission_id = NULL;
                # Validar que la sede exista
                SELECT id FROM permissions p
                WHERE p.name = p_permission
                INTO @permission_id;
    
                IF @permission_id IS NOT NULL THEN	
                    INSERT INTO `user_permissions`(`permission_id`, `user_headquarter_id`, `created_at`, `updated_at`) VALUES (@permission_id, p_user_headquarter_id, p_now, p_now);
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
