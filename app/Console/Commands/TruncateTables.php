<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables except users and migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Nonaktifkan foreign key checks untuk sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Ambil semua tabel di database
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        // Daftar tabel yang tidak ingin dihapus
        $excludedTables = ['users', 'migrations']; // Tambahkan tabel yang ingin dikecualikan

        foreach ($tables as $table) {
            // Truncate semua tabel kecuali yang ada di $excludedTables
            if (!in_array($table, $excludedTables)) {
                DB::table($table)->truncate();
            }
        }

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tables truncated successfully except for users and migrations.');
    }
}
