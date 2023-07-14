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

        $idNotNullAndCareerAndLevelNotZero = request()->employee_id != null && request()->career != 0 && request()->level != 0;
        $levelAndCareerNotZero = request()->level != 0 && request()->career != 0;

        $levelNotZeroAndIdNotNull = request()->level != 0 && request()->employee_id != null;

        $idNotNullAndCareerNotZero = request()->employee_id != null && request()->career != 0;

        $idNotNullAndLevelCareerIsZero = request()->employee_id != null && request()->career == 0 && request()->level == 0;

        $levelIsNotZeroAndCareerIsZero = request()->level != 0;
        $careerIsNotZeroAndLevelIsZero = request()->career != 0;

    
        $employees = DB::table('employees')->where('deleted_at', NULL)->orderBy('updated_at', 'desc')

            ->when($idNotNullAndCareerAndLevelNotZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career_id', '=', request()->get('career'))
                    ->where('level_id', '=', request()->get('level'));
            })

            ->when($levelAndCareerNotZero, function ($query) {

                $query->where('level_id', '=', request()->get('level'))
                    ->where('career_id', '=', request()->get('career'));
            })

            ->when($levelNotZeroAndIdNotNull, function ($query) {

                $query->where('level_id', '=', request()->get('level'))
                    ->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($idNotNullAndCareerNotZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%')
                    ->where('career_id', '=', request()->get('career'));
            })

            ->when($idNotNullAndLevelCareerIsZero, function ($query) {

                $query->where('employee_id', 'LIKE', '%' . request()->get('employee_id') . '%');
            })

            ->when($levelIsNotZeroAndCareerIsZero, function ($query) {

                $query->where('level_id', '=', request()->get('level'));
            })

            ->when($careerIsNotZeroAndLevelIsZero, function ($query) {

                $query->where('career_id', '=', request()->get('career'));
            })

            // if all of conditions above are wrong, this parigante will do the job..
            ->paginate(2);

        // need to appends the query string datas to get the paginate data succefully
        $employees->appends([
            'employee_id' => request()->get('employee_id'),
            'career' => request()->get('career'),
            'level' => request()->get('level'),
        ]);

        return $employees;
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of employees table by ignoring the deleted_at
     */

    public function AllEmployeesWithTrashed()
    {
        return Employee::withTrashed()->get();
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
