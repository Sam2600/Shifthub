<?php

namespace App\Repositories;

use App\Interfaces\Employee_projectInterface;
use Illuminate\Support\Facades\DB;

class Employee_projectRepositories implements Employee_projectInterface
{

    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return collecction => query data of employee_projects table
     */

    public function getEmployeeProject()
    {
        return DB::table('employee_projects')->get();
    }


    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return collection => Joined query data of employee_projects table and employees and projects table
     */
    public function getJoinedEmployeeProject()
    {
        return DB::table('employee_projects')
            ->join('employees', 'employees.id', '=', 'employee_projects.employee_id')
            ->join('projects', 'projects.id', '=', 'employee_projects.project_id')
            ->select('employee_projects.id', 'employees.employee_id', 'employees.name as employeeName', 'projects.name as projectName',  'employee_projects.start_date', 'employee_projects.end_date')
            ->get();
    }


    public function getIDofEmployeeProjectByEmployeeId($id)
    {
        return DB::table('employee_projects')->select('id')->where('employee_id', $id)->where('deleted_at', NULL)->get();
    }
}
