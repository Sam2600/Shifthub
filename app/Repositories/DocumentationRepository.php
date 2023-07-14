<?php

namespace App\Repositories;

use App\Interfaces\Documentationinterface;
use Illuminate\Support\Facades\DB;


class DocumentationRepository implements DocumentationInterface 
{

    public function getFilenameOfDocumentEmpProjectTableByEmployeeProjectId($id)
    {
        return DB::table('documents_emp_projects')->select('filename')->where('employee_project_id', $id)->get();
    }


    public function getFilenameOfDocumentEmpProjectTableById($id)
    {
        return DB::table('documents_emp_projects')
                    ->join('employee_projects', 'employee_projects.id', 'documents_emp_projects.employee_project_id')
                    ->join('employees', 'employees.id', 'employee_projects.employee_id')
                    ->select('documents_emp_projects.filename', 'employees.employee_id')
                    ->where('documents_emp_projects.id', $id)->get();
    }
}