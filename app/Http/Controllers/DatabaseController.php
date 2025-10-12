<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function showCloneForm()
    {
        // Fetch all databases
        $databases = DB::select("SHOW DATABASES");
        $dbList = collect($databases)->pluck('Database');
        // Filter out system databases
        $filtered = $dbList->reject(function ($db) {
            return in_array($db, [
                'information_schema',
                'mysql',
                'performance_schema',
                'sys'
            ]);
        });
        return view('DB.clone', ['databases' => $filtered]);
    }
    public function clone(Request $request)
    {
        $request->validate([
            'source_db' => 'required|string',
            'new_db' => 'required|string|regex:/^[a-zA-Z0-9_]+$/'
        ]);
        $sourceDb = $request->source_db;
        $newDb = $request->new_db;
        try {
            //Check if DB already exists
            $exists = DB::select("SHOW DATABASES LIKE '" . addslashes($newDb) . "'");
            if (!empty($exists)) {
                return back()->with('error', "Database `$newDb` already exists. Please choose another name.");
            }
            //Create new DB
            DB::statement("CREATE DATABASE `$newDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            //Get tables from source
            $tables = DB::select("SHOW TABLES FROM `$sourceDb`");
            $key = "Tables_in_" . $sourceDb;
            foreach ($tables as $table) {
                $tableName = $table->$key;
                //Clone schema only (no data)
                DB::statement("CREATE TABLE `$newDb`.`$tableName` LIKE `$sourceDb`.`$tableName`");
                //Clone triggers (clean old schema references)
                $triggers = DB::select("SHOW TRIGGERS FROM `$sourceDb` WHERE `Table` = ?", [$tableName]);
                foreach ($triggers as $trigger) {
                    $triggerName = $trigger->Trigger;
                    $timing = $trigger->Timing;
                    $event = $trigger->Event;
                    $statement = $trigger->Statement;
                    // Remove references to old DB schema
                    $cleanStatement = str_replace("`$sourceDb`.", "", $statement);
                    DB::statement("
                    CREATE TRIGGER `$newDb`.`$triggerName`
                    $timing $event ON `$newDb`.`$tableName`
                    FOR EACH ROW $cleanStatement
                ");
                }
            }
            return back()->with('success', "Database `$newDb` cloned successfully from `$sourceDb` (schema + triggers only, no data).");
        } catch (\Exception $e) {
            //Cleanup on failure
            DB::statement("DROP DATABASE IF EXISTS `$newDb`");
            return back()->with('error', "Cloning failed: " . $e->getMessage());
        }
    }
}
