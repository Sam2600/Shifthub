<?php

namespace Database\Factories;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{

    private $ramdomNums = [
        "00001",
        "00002",
        "00003",
        "00004",
        "00005",
        "00006",
        "00007",
        "00008",
        "00009",
        "00010",
        "00011",
        "00012",
        "00013",
        "00014",
        "00015",
        "00016",
        "00017",
        "00018",
        "00019",
        "00020",
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        /**
         * @todo First, check if directory exists or not. If not, 
         * we create necessary directories and store dummy files
         */
        $dir = public_path('storage/employees/photos');


        if (!File::isDirectory($dir)) {
            // 1st true is recursive, it means if employees dir is not exists, it create 1st and then photos dir
            // 2nd true is force, idk exactly tho seems like it crete directories no matter what
            File::makeDirectory($dir, 0755, true, true);
        }

        // after that, destroy unecessary variable
        unset($dir);

        return [
            'employee_id' => $this->faker->unique()->randomElement($this->ramdomNums),
            'name' => $this->faker->name(),
            'nrc' => $this->faker->regexify('^([0-9]{1,2})\/([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])\([N,P,E]\)[0-9]{6}$'),
            'phone' => $this->faker->regexify('^0\d{10}$'),
            'email' => $this->faker->safeEmail(),
            'gender' => $this->faker->randomElement(["male", "female"]),
            'dateOfBirth' => $this->faker->date(),
            'address' => $this->faker->address(),
            'programming_languages' => $this->faker->randomElement(["Android", "Java", "PHP", "React", "Laravel", "C++"]),
            'languages' => $this->faker->randomElement(["Japan", "English"]),
            'career' => $this->faker->randomElement(["Frontend", "Backend", "Fullstack", "Mobile"]),
            'level' => $this->faker->randomElement(["Beginner", "Junior Engineer", "Engineer", "Senior Engineer"]),
            'photo' => '',
            'created_by' => $this->faker->numberBetween(1, 2),
            'updated_by' => $this->faker->numberBetween(1, 2),
        ];
    }
}
