<?php

namespace App\Repositories;

use App\Interfaces\Documentationinterface;
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
        return DB::table('documents_emp_projects')->select('filename')->where('employee_project_id', $id)->get();
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
            ->join('employee_projects', 'employee_projects.id', 'documents_emp_projects.employee_project_id')
            ->join('employees', 'employees.id', 'employee_projects.employee_id')
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
            ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
            ->join("employees", "employees.id", "employee_projects.employee_id")
            ->join("projects", "projects.id", "employee_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->where('documents_emp_projects.deleted_at', NULL)
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
            ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
            ->join("employees", "employees.id", "employee_projects.employee_id")
            ->join("projects", "projects.id", "employee_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->whereDate("employee_projects.start_date", "<=", $todayDate)
            ->whereDate("employee_projects.end_date", ">=", $todayDate)
            ->where('documents_emp_projects.deleted_at', NULL)
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
            ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
            ->join("employees", "employees.id", "employee_projects.employee_id")
            ->join("projects", "projects.id", "employee_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->where("employee_projects.start_date", ">", $todayDate)
            ->where('documents_emp_projects.deleted_at', NULL)
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
            ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
            ->join("employees", "employees.id", "employee_projects.employee_id")
            ->join("projects", "projects.id", "employee_projects.project_id")

            ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

            ->where("employees.id", $id)
            ->where("employee_projects.end_date", "<", $todayDate)
            ->where('documents_emp_projects.deleted_at', NULL)
            ->get();
    }
}
