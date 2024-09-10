<?php

namespace App\Repositories;

use App\Interfaces\EmployeeInterface;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeRepository implements EmployeeInterface
{

    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of employees table
     */

    public function getAllEmployees()
    {
        //This process is to make the boolean conditions and run the ->when condition base on the conditions that i made and When input request are not null this step will do the work

        $id_level_career_not_null = request()->employee_id && request()->career && request()->level;

        $level_career_not_null = request()->level && request()->career;
        $id_level_not_null = request()->level && request()->employee_id;
        $id_career_not_null = request()->employee_id && request()->career;

        $only_level_not_null = request()->level;
        $only_career_not_null = request()->career;
        $only_id_not_null = request()->employee_id && !request()->career && !request()->level;


        $employees = DB::table('employees')->orderBy('updated_at', 'desc')

            ->when($id_level_career_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->employee_id . '%')
                    ->where('level', '=', request()->level)
                    ->where('career', '=', request()->career);
            })

            ->when($level_career_not_null, function ($query) {
                $query->where('level', '=', request()->level)
                    ->where('career', '=', request()->career);
            })

            ->when($id_level_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->employee_id . '%')
                    ->where('level', '=', request()->level);
            })

            ->when($id_career_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->employee_id . '%')
                    ->where('career', '=', request()->career);
            })

            ->when($only_id_not_null, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->employee_id . '%');
            })

            ->when($only_level_not_null, function ($query) {

                $query->where('level', '=', request()->level);
            })

            ->when($only_career_not_null, function ($query) {

                $query->where('career', '=', request()->career);
            })

            // if all of conditions above are wrong, this parigante will do the job..
            ->paginate(4);

        // need to appends the query string datas to get the paginate data succefully
        $employees->appends([
            'employee_id' => request()->employee_id,
            'career' => request()->career,
            'level' => request()->glevel,
        ]);

        return $employees;
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of employees table by ignoring the deleted_at
     */

    public function getTotalCountOfEmployees()
    {
        return Employee::count();
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of employees table pure rows without deleted_at
     */

    public function getDistinctValues()
    {
        return Employee::query();
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of employees table pure rows without deleted_at
     */

    public function AllEmployees()
    {
        return Employee::all();
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param integer => id The employee's ID
     * @return array => query data of employees table
     */

    public function getEmployeeById($id)
    {
        return Employee::find($id);
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param integer => id The employee's ID
     * @return collection => query data of employees_programming_table's data
     */

    public function getEmployeeProgramming_language_id($id)
    {
        $emp_progs = DB::table('employees_programming_languages')->where('employee_id', '=', $id)->get();

        return $emp_progs;
    }



    public function getNames()
    {
        return DB::table('employees')->select('name')->where('employee_id', request()->input('employee_id'))->get();
    }
}
