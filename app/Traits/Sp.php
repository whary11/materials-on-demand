<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait Sp
{
    public function executeSp(String $sp, Array $params = [], String $first = null){
        $status = false;
        try {
            $db = DB::getPdo();

            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Reporte de errores: Lanza excepciones
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true); // Emula las sentencias preparadas en caso de que no se puedan ejecutar de forma nativa

            $queryResult = $db->prepare($sp);
            $queryResult->execute($params);

            $data = $queryResult->fetchAll(\PDO::FETCH_OBJ);
            $status = true;
        } catch (\Throwable $th) {
            $data = $th->getMessage();
            $queryResult->closeCursor();
            if (isset($this->sp_error)) {
                $this->sp_error = true;
                $this->sp_msg_error = $th->getMessage();
            }
            //aqui se va guardar un log de los sp que esten dando error
        }
        if ($status && $first === '1') { // consulta que retorna un solo registro
            return (isset($data[0])) ? $data[0] : $data;
        } else {
            return [
                'status' => $status,
                'data' => $data
            ];
        }
    }

    public function executeReadSp(String $sp, Array $params = [], String $first = null){
        $status = false;
        try {
            $data = DB::select($sp, $params);
            $status = true;
            //DB::close();
        } catch (\Throwable $th) {
            $data = $th->getMessage();
            if (isset($this->sp_error)) {
                $this->sp_error = true;
                $this->sp_msg_error = $th->getMessage();
            }
            //aqui se va guardar un log de los sp que esten dando error
        }

        if ($status && $first === '1') { // consulta que retorna un solo registro
            return (isset($data[0])) ? $data[0] : $data;
        } else {
            return [
                'status' => $status,
                'data' => $data
            ];
        }
    }
}
