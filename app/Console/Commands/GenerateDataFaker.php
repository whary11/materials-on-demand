<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Department;
use App\Models\Headquarter;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Traits\Headquarter as TraitsHeadquarter;
use App\Traits\Permission as TraitsPermission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class GenerateDataFaker extends Command
{
    use TraitsPermission, TraitsHeadquarter;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:data';

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
        $this->info("Inicio: ".Carbon::now()->toDateTimeString());

        try {
            //code...
            Artisan::call('db:wipe');
            $this->newLine(1);
            $this->info("Base de datos borrada.");
            Artisan::call('migrate');
            $this->newLine(1);
            $this->info("Migraciones ejecutadas.");
            // Crear estados
            $created_at = Carbon::now()->toDateTimeString();
            $statuses = [
                [
                    'name' => 'Activo',
                    'color' => 'blue',
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => 'Inactivo',
                    'color' => 'red',
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Status::insert($statuses);
            $this->newLine(1);
            $this->info("Estados creados.");
            // Crear Paises
            $countries = [
                [
                    'name' => 'Colombia',
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => 'Brasil',
                    
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Country::insert($countries);
            $this->newLine(1);
            $this->info("Paises creados.");
    
            // Crear departamentos
            $departments = [
                [
                    'name' => 'Cundinamarca',
                    'status_id' => 1,
                    'country_id' => 1,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => 'São Paulo',
                    'status_id' => 1,
                    'country_id' => 2,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Department::insert($departments);
            $this->newLine(1);
            $this->info("Departamentos creados.");
            // Crear ciudades

            $cities = [
                [
                    'name' => 'Bogotá D.C.',
                    'department_id' => 1,
                    'status_id' => 1,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => 'São Paulo',
                    'status_id' => 1,
                    'department_id' => 2,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            City::insert($cities);
            $this->newLine(1);
            $this->info("Ciudades creadas.");
            // Crear configs
            $configurations = [
                [
                    'name_company' => "Materials on demand",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Configuration::insert($configurations);
            $this->newLine(1);
            $this->info("Configuraciones creadas.");

            // Crear roles
            $roles = [
                [
                    'name' => "ADMIN",
                    'description' => "ADMIN",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => "SUPER_ADMIN",
                    'description' => "SUPER_ADMIN",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Role::insert($roles);
            $this->newLine(1);
            $this->info("Roles creados.");
    
            // Crear permisos
            $permissions = [
                [
                    'name' => "create_user",
                    'description' => "Crear usuarios (office)",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => "edit_user",
                    'description' => "Editar usuarios (office)",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => "delete_user",
                    'description' => "Eliminar usuarios (office)",
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ]
            ];
            Permission::insert($permissions);
            $this->newLine(1);
            $this->info("Permisos creados.");
    
            // Agregarle permisos a un rol
            $this->addPermissionsToRole(1, Permission::get()->pluck('name')->toArray());

            // Crear sedes
            $headquarters = [
                [
                    'name' => "Bogota sur",
                    'address' => "Bosa",
                    'lat' => 2341234,
                    'long' => 234345345,
                    'status_id' => 1,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => "Bogota norte",
                    'address' => "Engativá",
                    'lat' => 3563456345,
                    'long' => 3563456345,
                    'status_id' => 1,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
            ];
            Headquarter::insert($headquarters);
            $this->newLine(1);
            $this->info("Usuarios creados.");
    
            // Crear usuarios
            $users = [
                [
                    'name' => "Luis Fernando",
                    'last_name' => "Raga Renteria",
                    'email' => "luis@gmail.com",
                    'password' => Hash::make("password"),
                    'is_anonymous' => 0,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
                [
                    'name' => "David",
                    'last_name' => "Raga Renteria",
                    'email' => "david@gmail.com",
                    'password' => Hash::make("password"),
                    'is_anonymous' => 0,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ],
            ];
            User::insert($users);
            $this->newLine(1);
            $this->info("Usuarios creados.");
            // Agregar usuario a sedes

            $users = User::all(['id'])->pluck('id')->toArray();
            $this->addHeadquartersToUser(1, Headquarter::get()->pluck('id')->toArray());
            $this->addHeadquartersToUser(2, Headquarter::get()->pluck('id')->toArray());
            $this->newLine(1);
            $this->info("Sedes agregadas a todos los usuarios.");
            // Ponerle roles a un usuario
            $this->addRolesToUser(1, [1,2]);
            $this->addRolesToUser(2, [1,2]);
            $this->addRolesToUser(3, [1,2]);
            $this->addRolesToUser(4, [1,2]);
            $this->newLine(1);
            $this->info("Roles asignados.");
            // Ponerle permisos especiales a un usuario ?
    

        } catch (\Throwable $th) {
            dump($th->getMessage());
        }    

        $this->newLine(1);
        $this->info("Fin: ".Carbon::now()->toDateTimeString());
        return Command::SUCCESS;
    }
}
