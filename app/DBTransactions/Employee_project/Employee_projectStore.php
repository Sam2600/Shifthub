<?php

namespace App\DBTransactions\Employee_project;

use Carbon\Carbon;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


// object param is $request that is get from the Request $request from controller

class Employee_projectStore extends DBTransaction
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    /* This is the data format of to storing data into employee_project pivot table
        employee_id = bruce
        project = 5
        startDate = "2023-06-26"
        endDate = "2023-07-26"
        document = ".../uploadedfile
    */

    /**
     * Store a newly created employees_projects and document datas into the employees_projects table and document employee_table.
     * @author Kaung Htet San
     * @date 28/6/2023
     * @return array => return ['status' => false, 'error' => 'Failed!'] or ['status' => true, 'error' => '']
     */

    public function process()
    {

        // we get the employee id
        $searchResult = DB::table('employees')->select('id')->where('employee_id', $this->request->employee_id)->get();

        $employee_id = $searchResult[0]->id; // 1


        // 1st we need to check there is already the project for the request input's employee or not even if there is already a similar project, we can still assign him by checking end date of current project if new end_date is not similar and its start date is greater than old data's end date, we are good to go!

        //getting input request's data
        $checkProject = $this->request->input('project');
        $checkEmployee = $this->request->input('employee_id');

        $checkStartDate = $this->request->input('startDate');
        $checkEndDate = $this->request->input('endDate');

        // start to check THAT there is alredy employee and his project with this two 2 ids or not

        /* example
            employee_id = 1
            project_id = 1
        */

        $requestStartDate = Carbon::parse($checkStartDate);
        $requestEndDate = Carbon::parse($checkEndDate);

        $checkResults = DB::table('employees_projects')
            ->where('project_id', $checkProject) // 1
            ->where('employee_id', $employee_id) // 1
            ->where('start_date', '>=', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->get();


        $checking = count($checkResults->toArray());  // if there is already, count will > 0

        //dd($requestStartDate<$requestEndDate);

        if ($checking > 0) {

            // I used carbon library to compare the string dates for validation
            $requestStartDate = Carbon::parse($checkStartDate);
            $requestEndDate = Carbon::parse($checkEndDate);

            // and make for each loop to proceed validation
            foreach ($checkResults as $checkResult) {

                $oldStartDate = Carbon::parse($checkResult->start_date);
                $oldEndDate = Carbon::parse($checkResult->end_date);

                // now we got new dates and old dates to compare. We have to check to make sure new assign start date is greater than old assign's end_date
                if ($requestStartDate > $oldEndDate) {

                    // U may wonder why i contined the loop because i want my request's end date is need to be ckeck with current index +1 's start date
                    continue;
                }

                if ($requestEndDate > $oldStartDate) {
                    return ['status' => false, 'error' => "Failed!"];
                }
            }
        }


        // after there is no confict between dates, we get the id, we insert into the emp_prj pivot table
        $employees_projects = DB::table('employees_projects')->insert([

            "employee_id" => $employee_id,
            "project_id" => $this->request->project,
            "end_date" => $this->request->endDate,
            "start_date" => $this->request->startDate,
            "created_by" => Session::get('id'),
            "updated_by" => Session::get('id'),
            "created_at" => Carbon::now()
        ]);

        // Store the data into document_employee_project table

        if ($employees_projects) { // if employee_project table storing is success we proceed the storing data into docs_emp_prj table

            $get_emp_prj_id = DB::table('employees_projects')->select('id')->where('employee_id', '=', $employee_id)->where('project_id', '=', $this->request->project)->where('end_date', '=', $this->request->endDate)->get();

            $emp_prj_id = $get_emp_prj_id[0]->id;
        } else { // if we don't we return the false array to DBTransactions

            return ['status' => false, 'error' => "Failed!"];
        }

        $fileArray = $this->request->file('document'); // This is array because of multiple file upload

        /* This is the format of storing datas into table
            "filename" =
            "filesize" =
            "filepath" =
            "employee_project_id" = emp_prj_id => thats got from line 42
        */

        // 1st we make empty array to insert into document_employee_project table
        $doc_emp_prjs = [];

        // we have to loop this. See in line 51
        foreach ($fileArray as $file) {

            $fileName = uniqid() . "_" . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/employees/documents', $fileName);
            $fileSize = $file->getSize();

            $doc = [
                "filename" => $fileName,
                "filesize" => $fileSize,
                "filepath" => $filePath,
                "employees_projects_id" => $emp_prj_id,
                "created_by" => Session::get('id'),
                "updated_by" => Session::get('id'),
                "created_at" => Carbon::now()
            ];

            array_push($doc_emp_prjs, $doc);
        }

        // Now we insert into the table with the array that we got earlier
        $document_emp_prjs = DB::table('documents_emp_projects')->insert($doc_emp_prjs);

        if ($document_emp_prjs) { // if the storing is success we return the true array result to the DBTransactions
            return ['status' => true, 'error' => ''];
        }

        // if false we return the false array result to the DBTransactions
        return ['status' => false, 'error' => 'Failed!'];
    }
}
