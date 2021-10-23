<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMigrationsOfScriptSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Consultar las tablas de la base de datos
            $tables = DB::select('SHOW TABLE STATUS');
            // Definir el orden en que se van a crear las tablas

            $sorterTable = collect([]);
            $created = [];

            foreach ($tables as $key => $table) {
                // Consultar la información detallada de la tabla
                $tableInfo = DB::select("select CONSTRAINT_NAME foreign_key,TABLE_NAME table_foreign_key,COLUMN_NAME colunm_in_table_foreign_key, REFERENCED_COLUMN_NAME column_name_local
                from information_schema.KEY_COLUMN_USAGE
                where table_schema = 'keny'
                and referenced_table_name = '$table->Name'");

                // llenar el arreglo con una llave dependencies y dentro las tablas que dependen de esta tabla
                if (count($tableInfo) == 0) {
                    array_push($created, $table->Name);
                }else{
                    foreach ($tableInfo as $key => $tI) {
                        dump(isset($sorterTable->toArray()[$table->Name]));
                        if (isset($sorterTable->toArray()[$table->Name])) {
                            dump("existe", $table->Name);
                            // array_push($sorterTable[$table->Name]["dependencies"], $tI->table_foreign_key);

                            $dd = $sorterTable->pull("$table->Name")["dependencies"];

                            array_push($dd, $table->Name);

                            // dd($dd);
                            $sorterTable->push("$table->Name".".dependencies", $dd);
                        }else {
                            dump("no existe" , $table->Name);
                            $sorterTable->prepend([
                                "dependencies" => [
                                    $tI->table_foreign_key
                                ]
                            ],"$table->Name") ;


                            

                            // array_push($sorterTable, [
                            //     "$table->Name" => [
                            //         "dependencies" => [
                            //             $tI->table_foreign_key
                            //         ]
                            //     ]
                            // ]);
                            // $sorterTable = [
                            //     "$table->Name" => [
                            //         "dependencies" => [
                            //             $tI->table_foreign_key
                            //         ]
                            //     ]
                            // ];

                        }
                        // dd($sorterTable->where("Name", $table->Name));


                        // dd($sorterTable);

                        // array_values($sorterTable, $tables[count($tables)-1]->Name);
                    }
                }
                // dd($tableInfo, $table->Name);
            }

            dd($sorterTable);
            




            // dd($tables);
            
        } catch (\Throwable $th) {
            dd($th->getmessage());
        }
        return Command::SUCCESS;
    }
}
