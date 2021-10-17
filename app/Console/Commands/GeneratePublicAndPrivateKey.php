<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use phpseclib3\Crypt\RSA;

class GeneratePublicAndPrivateKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate
                            {--force : Overwrite keys they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for the generation of RSA keys for the transport of simple information.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $controller;
    public function __construct()
    {
        parent::__construct();
        $this->controller = new Controller;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(RSA $rsa)
    {
        $publicKey = storage_path('lk-public.key');
        $privateKey =storage_path('lk-private.key');

        if ((file_exists($publicKey) || file_exists($privateKey) )) {
            // Validar si el archivo tiene información
            if ((!empty(file_get_contents($publicKey)) || !empty(file_get_contents($privateKey))) && !$this->option('force')) {
                $this->error('Los archivos ya están generados con sus respectivas llaves, utiliza --force para sobreescribirlos, ten encuenta que esto generará cambios en los clientes que estén utilizando la llave pública.');
                return;
            }else{
                $generate = true;
            }

            if ($this->option('force') || $generate) {
                $keys = $rsa->createKey(4096);
                file_put_contents($publicKey, Arr::get($keys, 'publickey'));
                file_put_contents($privateKey, Arr::get($keys, 'privatekey'));
                $this->info('Llaves de encriptación generadas con éxito.');
                return;
            }
        } else {
            $publicKey = fopen(storage_path('lk-public.key'), "w+b");
            $privateKey = fopen(storage_path('lk-private.key'), "w+b");
            if(!$publicKey && !$privateKey){
                $this->error('No se pudo generar los archivos, crea estos archivos (lk-public.key, lk-private.key) en el storage e intenta nuevamente.');
                return;
            }

            fclose($publicKey);
            fclose($privateKey);
            $publicKey = storage_path('lk-public.key');
            $privateKey =storage_path('lk-private.key');

            $keys = $rsa->createKey(4096);
            file_put_contents($publicKey, Arr::get($keys, 'publickey'));
            file_put_contents($privateKey, Arr::get($keys, 'privatekey'));
            $this->info('Llaves de encriptación generadas con éxito.');
        }
    }
}
