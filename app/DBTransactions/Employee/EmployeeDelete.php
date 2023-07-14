<?php

namespace App\DBTransactions\Employee;

use App\Classes\DBTransaction;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @author KaungHtetSan
 * @create 06/21/2023
 * @param  no param...  use the class's $id variable into the constructor
 * @return array
 */

class EmployeeDelete extends DBTransaction
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function process()
    {   
        
        $employee = Employee::find($this->id);

       
        // check if there is row data or not

        if (!$employee) { //if there is no.. we return false array
            return ['status' => false, 'error' => 'Failed'];
        }

        $photoPath = storage_path('app/public/employees/photos/' . $employee->photo);
        //$docsPath = storage_path('app/public/employees/documents/' . $employee->photo);

        if (file_exists($photoPath)) {

            unlink($photoPath);
        }

        // This process is to delete the employee's programming languages
        DB::table('employees_programming_languages')->where('employee_id', '=', $this->id)->update(['deleted_at' => now()]);

        // This process is to check and delete the employee_projects table's rows
        $results = DB::table('employee_projects')->where('employee_id', "=", $this->id)->get();

        // And we will make sure that the employee we want to delete gets the documents or not. Even though this step is unecessary. I will make sure for it.
        if (count($results) > 0) { // it means there is employees with that id so we delete it

            $ids = [];

            foreach ($results as $result) {

                array_push($ids, $result->id);
            }


            // Collection of id of that is get from 56
            foreach ($ids as $id) {

                // Now we delete the emplolyee_projects table's data that is connected with that employee
                DB::table('employee_projects')->where('id', "=", $id)->update(['deleted_at' => now()]);

            }

            $resultArrays = [];

            foreach ($ids as $id) {

                $resultDocs = DB::table('documents_emp_projects')->select('filename')->where('employee_project_id', "=", $id)->get()->toArray();

                foreach ($resultDocs as $resultDoc) {

                    array_push($resultArrays, $resultDoc);
                }
            }


            if (count($resultArrays) > 0) {

                foreach ($ids as $id) {
                    // this step is to also delete the employee's project documents that is founded from line 47
                    DB::table('documents_emp_projects')->where('employee_project_id', "=", $id)->update(['deleted_at' => now()]);
                }

                // we loop it
                foreach ($resultArrays as $resultArray) {

                    $file = $resultArray->filename;

                    $filePath = storage_path('app/public/employees/documents/' . $file);

                    if (file_exists($filePath)) {

                        unlink($filePath);
                    }
                }
            }
        }

        $employee->delete();

        return ['status' => true, 'error' => ''];
    }
}
