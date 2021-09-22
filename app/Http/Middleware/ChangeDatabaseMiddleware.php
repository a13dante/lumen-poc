<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\MasterDetail;
use Closure;

class ChangeDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $organisation = trim($request->header('app')) ?? '';

        if($organisation!= '') {
            //Purging is necessory to remove connection cache
            DB::purge('mysql');
            
            $masterDetail = MasterDetail::where('organisation_name', $organisation)->firstOrFail();

            $schemaDetails = $this->setDatabaseConfig($masterDetail);
            
            Config::set('database.connections.mysql', $schemaDetails);
            //After setting Purging is necessory to remove connection cache
            DB::purge('mysql');

            return $next($request);
        }
        return response()->json(['message' => 'Not authorised'], 401);
    }

    public function setDatabaseConfig($masterDetail)
    {
        //Can't have seperate username passwrod details for multiple read connection
        $schemaDetails['read'] = [
            'host' => [$masterDetail->db_slave_host],
            'port' => 3306,
            'username'  => $masterDetail->db_username,
            'password'  => $masterDetail->db_password,
        ];
        $schemaDetails['write'] = [
            'host' => $masterDetail->db_master_host,
            'port' => 3306,
            'username'  => $masterDetail->db_username, //can be different for master
            'password'  => $masterDetail->db_password
        ];
        $schemaDetails['database']  = $masterDetail->db_name;
        $schemaDetails['strict'] = true;
        $schemaDetails['driver'] = 'mysql';
        //Not much necessory
        $schemaDetails['unix_socket'] = env('DB_SOCKET', '');
        $schemaDetails['charset'] = env('DB_CHARSET', 'utf8mb4');
        $schemaDetails['collation'] = env('DB_COLLATION', 'utf8mb4_unicode_ci');
        $schemaDetails['prefix'] = env('DB_PREFIX', '');
        $schemaDetails['strict'] = env('DB_STRICT_MODE', true);
        $schemaDetails['engine'] = env('DB_ENGINE', null);
        $schemaDetails['timezone'] = env('DB_TIMEZONE', '+00:00');

        return $schemaDetails;
    }
}
