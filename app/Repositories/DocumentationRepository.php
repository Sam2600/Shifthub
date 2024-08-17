<?php

namespace App\Repositories;

use App\Interfaces\DocumentationInterface;
use Illuminate\Support\Facades\DB;


class DocumentationRepository implements DocumentationInterface
{

    /**
     * get the file name of Document employee project table with emp_project id
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */

    public function getFilenameOfDocumentEmpProjectTableByEmployeeProjectId($id)
    {
        return DB::table('documents_emp_projects')->select('filename')->where('employees_projects_id', $id)->get();
    }


    /**
     * get the file name of Docuement Document employee project table and employee's id
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */

    public function getFilenameOfDocumentEmpProjectTableById($id)
    {
        return DB::table('documents_emp_projects')
            ->join('employees_projects', 'employees_projects.id', 'documents_emp_projects.employees_projects_id')
            ->join('employees', 'employees.id', 'employees_projects.employee_id')
            ->select('documents_emp_projects.filename', 'employees.employee_id')
            ->where('documents_emp_projects.id', $id)->get();
    }


    /**
     * get getTableJoinedQueryByEmployeeId
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */
    public function getTableJoinedQueryByEmployeeId($id)
    {
        return DB::table("documents_emp_projects")
            ->join("employees_projects", "employees_projects.id", "documents_emp_projects.employees_projects_id")
            ->join("employees", "employees.id", "employees_projects.employee_id")
            ->join("projects", "projects.id", "employees_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employees_projects.start_date", "employees_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->get();
    }


    /**
     * get getTableJoinedQueryByEmployeeIdAndTodayDate
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */
    public function getTableJoinedQueryByEmployeeIdAndTodayDate($id, $todayDate) // where start_date <= today date and end date >= today date
    {
        return DB::table("documents_emp_projects")
            ->join("employees_projects", "employees_projects.id", "documents_emp_projects.employees_projects_id")
            ->join("employees", "employees.id", "employees_projects.employee_id")
            ->join("projects", "projects.id", "employees_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employees_projects.start_date", "employees_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->whereDate("employees_projects.start_date", "<=", $todayDate)
            ->whereDate("employees_projects.end_date", ">=", $todayDate)
            ->get();
    }


    /**
     * getTableJoinedQueryByEmployeeIdAndTodayDateIsgreaterThanStartDate
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */
    public function getTableJoinedQueryByEmployeeIdAndTodayDateIsgreaterThanStartDate($id, $todayDate)
    {
        return DB::table("documents_emp_projects")
            ->join("employees_projects", "employees_projects.id", "documents_emp_projects.employees_projects_id")
            ->join("employees", "employees.id", "employees_projects.employee_id")
            ->join("projects", "projects.id", "employees_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employees_projects.start_date", "employees_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->where("employees_projects.start_date", ">", $todayDate)
            ->get();
    }


    /**
     * getTableJoinedQueryByEmployeeIdAndTodayDateIsLessThanStartDate
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return Collection
     */
    public function getTableJoinedQueryByEmployeeIdAndTodayDateIsLessThanStartDate($id, $todayDate)
    {
        return DB::table("documents_emp_projects")
            ->join("employees_projects", "employees_projects.id", "documents_emp_projects.employees_projects_id")
            ->join("employees", "employees.id", "employees_projects.employee_id")
            ->join("projects", "projects.id", "employees_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employees_projects.start_date", "employees_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->where("employees_projects.end_date", "<", $todayDate)
            ->get();
    }
}
