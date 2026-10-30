<?php

namespace App\Console\Commands;

use App\Support\WordpressBerita;
use Illuminate\Console\Command;

class ImportBerita extends Command
{
    protected $signature = 'berita:import';

    protected $description = 'Impor berita WordPress dari database/data/berita.json';

    public function handle(): int
    {
        $count = WordpressBerita::import();
        $this->info($count.' berita tersimpan.');

        return self::SUCCESS;
    }
}
