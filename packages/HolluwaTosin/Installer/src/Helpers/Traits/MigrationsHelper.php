<?php

namespace HolluwaTosin\Installer\Helpers\Traits;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

trait MigrationsHelper
{
    private function seed()
    {
        Artisan::call('db:seed', [
            "--force" => true
        ]);
    }

    private function migrate()
    {
        Artisan::call('migrate', [
            "--force" => true
        ]);

        $this->seed();
    }

    public function migrateAndSeed()
    {
        $this->migrate();
    }

    /**
     * Get migration path names
     *
     * @return mixed
     */
    public function getMigrations()
    {
        $path = database_path(
            'migrations' . DIRECTORY_SEPARATOR . '*.php'
        );

        return str_replace(
            '.php', '', glob($path)
        );
    }

    /**
     * Get migrated tables
     *
     * @return \Illuminate\Support\Collection
     */
    public function getExecutedMigrations()
    {
        return DB::table('migrations')->get()->pluck('migration');
    }

    public function countPendingMigrations()
    {
        return count($this->getMigrations()) - count($this->getExecutedMigrations());
    }
}
