<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SpGetUsersManage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public $sp_name = "ksp_get_users_manage"; 
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS  $this->sp_name");
        DB::unprepared('
        CREATE PROCEDURE '.$this->sp_name .'(p_offset INT, p_limit INT)
        BEGIN
                                SELECT COUNT(u.id) FROM users u 
                        JOIN user_headquarters uh ON uh.user_id = u.id
                        JOIN headquarters h ON h.id = uh.headquarter_id
                                GROUP BY uh.user_id LIMIT 1
                    INTO @count;
                                
                                
                    SET @avatar = "https://scontent.fbog11-1.fna.fbcdn.net/v/t1.6435-1/p160x160/67654490_10218672031367891_7179299875513696256_n.jpg?_nc_cat=102&ccb=1-5&_nc_sid=dbb9e7&_nc_ohc=BPI0w_1xZLEAX8ZTXc0&_nc_ht=scontent.fbog11-1.fna&oh=67c557923a9dc4c94f4606c0e4639201&oe=619B2085";
                    SELECT @count count, u.id, u.name fullname, @avatar avatar, u.email, GROUP_CONCAT(CONCAT(h.id,"|||||",h.name,"|||||",uh.id) SEPARATOR "&&&&&") headquarters_name FROM users u 
                        JOIN user_headquarters uh ON uh.user_id = u.id
                        JOIN headquarters h ON h.id = uh.headquarter_id
                    GROUP BY u.id LIMIT p_offset,p_limit;
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
