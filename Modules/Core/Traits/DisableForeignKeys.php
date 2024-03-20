<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    /**
     * Command to disable foreign key for each database management.
     *
     * @var array
     */
    private $commands = [
        'mysql' => [
            'enable' => 'SET FOREIGN_KEY_CHECKS=1;',
            'disable' => 'SET FOREIGN_KEY_CHECKS=0;',
        ],
        'sqlite' => [
            'enable' => 'PRAGMA foreign_keys = ON;',
            'disable' => 'PRAGMA foreign_keys = OFF;',
        ],
        'sqlsrv' => [
            'enable' => 'EXEC sp_msforeachtable @command1="print \'?\'", @command2="ALTER TABLE ? WITH CHECK CHECK CONSTRAINT all";',
            'disable' => 'EXEC sp_msforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT all";',
        ],
        'pgsql' => [
            'enable' => 'SET CONSTRAINTS ALL IMMEDIATE;',
            'disable' => 'SET CONSTRAINTS ALL DEFERRED;',
        ],
    ];

    /**
     * Disable foreign key checks for current db driver.
     */
    protected function disableForeignKeys()
    {
        DB::statement($this->getDisableStatement());
    }

    /**
     * Enable foreign key checks for current db driver.
     */
    protected function enableForeignKeys()
    {
        DB::statement($this->getEnableStatement());
    }

    private function getEnableStatement()
    {
        return $this->getDriverCommands()['enable'];
    }

    private function getDisableStatement()
    {
        return $this->getDriverCommands()['disable'];
    }

    private function getDriverCommands()
    {
        return $this->commands[DB::getDriverName()];
    }
}
