<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** @todo $this->call(SeederClass::class); */
        /** @todo Model::factory(000)->create() */
        
        Employee::factory(20)->create();
    }
}
