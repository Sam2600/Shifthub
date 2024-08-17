<?php

namespace App\Repositories;

use App\Interfaces\Employee_projectInterface;
use Illuminate\Support\Facades\DB;

class Employee_projectRepositories implements Employee_projectInterface
{

    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return collecction => query data of employees_projects table
     */

    public function getEmployeeProject()
    {
        return DB::table('employees_projects')->get();
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return collection => Joined query data of employees_projects table and employees and projects table
     */
    public function getJoinedEmployeeProject()
    {
        return DB::table('employees_projects')
            ->join('employees', 'employees.id', '=', 'employees_projects.employee_id')
            ->join('projects', 'projects.id', '=', 'employees_projects.project_id')
            ->select('employees_projects.id', 'employees.employee_id', 'employees.name as employeeName', 'projects.name as projectName',  'employees_projects.start_date', 'employees_projects.end_date')
            ->get();
    }


    public function getIDofEmployeeProjectByEmployeeId($id)
    {
        return DB::table('employees_projects')->select('id')->where('employee_id', $id)->get();
    }
}
