<?php

namespace Database\Seeders;

use App\Models\ProgrammingLanguage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProgrammingLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            ['name' => 'C++', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Java', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'PHP', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'React', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Android', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Laravel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

        ];

        // Insert the data into the table
        ProgrammingLanguage::insert($data);
    }
}
