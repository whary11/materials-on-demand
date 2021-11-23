<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpSaveLogException extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_save_log_exception"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_user_id INT, p_code INT, p_file TEXT,p_line INT,p_message TEXT,p_now DATETIME)
        BEGIN
    INSERT INTO `log_exceptions`
        (
            `user_id`,
            `code`,
            `file`,
            `line`,
            `message`,
            `origin`,
            `created_at`,
            `updated_at`
        )
    VALUES
        (
            p_user_id,
            p_code,
            p_file,
            p_line,
            p_message,
            "BACKOFFICE",
            p_now,
            p_now
        );
    CALL ksp_response(TRUE, "Excepcion creada.");
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