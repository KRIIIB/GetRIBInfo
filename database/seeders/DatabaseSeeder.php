<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rib;
use App\Models\Operation;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
{
    $data = json_decode(file_get_contents(__DIR__.'/data.json'), true);

    foreach ($data as $item) {
        $rib = Rib::firstOrCreate(['rib' => $item['rib']]);

        Operation::create([
            'label' => $item['label'],
            'rib_id' => $rib->id,
            'date' => $item['date'],
            'amount' => $item['amount'],
        ]);
    }
}
}
