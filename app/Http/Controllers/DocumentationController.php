<?php

namespace App\Http\Controllers;

use App\Interfaces\DocumentationInterface;
use App\Interfaces\Employee_projectInterface;
use App\Interfaces\EmployeeInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class DocumentationController extends Controller
{

    use ResponseAPI;

    protected $employeeInterface;

    protected $employeeProjectInterface;

    protected $documentationInterface;

    public function __construct(EmployeeInterface $employeeInterface, Employee_projectInterface $employeeProjectInterface, DocumentationInterface $documentationInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->employeeProjectInterface = $employeeProjectInterface;
        $this->documentationInterface = $documentationInterface;
    }


    /**
     * Download the employees' connected project documents
     * @date 03/07/2023
     * @author Kaung Htet San
     * @return \Illuminate\Http\Response
     */

    public function downloadDocs($id)
    {

        // first we get the employee_projects table's primary key id
        $emp_prjs = $this->employeeProjectInterface->getIDofEmployeeProjectByEmployeeId($id);

        $results =  $emp_prjs->toArray(); // emp_prj tabl's id => 2

        //dd($docs);

        // 1st we need to check is there any document for this emplyee

        if (count($results) != 0) { // it means there is project and documents for him

            $docArrays = [];

            foreach ($results as $result) { // now we foreach loop and add the filename that is connected with id (employee_projects's table)
                $docs = $this->documentationInterface->getFilenameOfDocumentEmpProjectTableByEmployeeProjectId($result->id);
                array_push($docArrays, $docs);
            }

            //dd($docArrays);

            // and we use ZipArchive class and build object in order to able to use its open method
            $zip = new ZipArchive();

            $zipFileName = uniqid() . '_' . 'Employee' . $id . '\'s documents.zip'; // this is downloaded file's name

            $zipFilePath = storage_path($zipFileName);

            //dd($zip->open($zipFilePath,ZipArchive::CREATE | ZipArchive::OVERWRITE));

            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) { // if createing of zip file is success we good to go

                // now we foreach loop the file names array to download in zip file
                foreach ($docArrays as $docArray) {

                    // we nested loop this. If you ask me why the $docArrays is nested array and in order to download it we have to double loop it
                    // You can dd the $docArrays
                    foreach ($docArray as $docs) {

                        $filePath = 'app/public/employees/documents/' . $docs->filename;

                        $storagePath = storage_path($filePath);

                        $zip->addFile($storagePath, $docs->filename);
                    }
                }

                $zip->close();

                // we have to use deleteFileAfterSend(true) if We don't to remain the doc files in our local storage pth
                // if we want to also store it we don't have to use it and it will work fine as well
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {

                // Handle error if the zip file cannot be created
                return response('Failed to create the zip file', 500);
            }
        }

        return redirect()->back()->with("noProject", "There is no projects yet for this employee");
    }

    public function showProjects($id)
    {
       
        $page = request()->input('page');
        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {

            // first we get the employee_projects table's primary key id
            $emp_prjs = $this->employeeProjectInterface->getIDofEmployeeProjectByEmployeeId($id); // id => 2

            if (count($emp_prjs->toArray()) == 0) {

                return redirect()->back()->with("NoProjects", "Employee id $id is not assigned yet!");
            }


            // get today date
            $todayDate = Carbon::now('Asia/Yangon');

            

            if (request()->input('no') == null) {

                
                // all projects and documents
                $empPrjDocs = DB::table("documents_emp_projects")
                    ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
                    ->join("employees", "employees.id", "employee_projects.employee_id")
                    ->join("projects", "projects.id", "employee_projects.project_id")

                    ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

                    ->where("employees.id", $id)
                    ->where('documents_emp_projects.deleted_at', NULL)
                    ->get();
            }

            //dd($empPrjDocs);

            if (request()->input("no") == 1) {

                // Ongoing
                $empPrjDocs = DB::table("documents_emp_projects")
                    ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
                    ->join("employees", "employees.id", "employee_projects.employee_id")
                    ->join("projects", "projects.id", "employee_projects.project_id")

                    ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

                    ->where("employees.id", $id)
                    ->whereDate("employee_projects.start_date", "<=", $todayDate)
                    ->whereDate("employee_projects.end_date", ">=", $todayDate)
                    ->where('documents_emp_projects.deleted_at', NULL)
                    ->get();

                //dd(count($employeeProjectsDocs));
            }


            if (request()->input("no") == 2) {

                //  Upcomming
                $empPrjDocs = DB::table("documents_emp_projects")
                    ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
                    ->join("employees", "employees.id", "employee_projects.employee_id")
                    ->join("projects", "projects.id", "employee_projects.project_id")

                    ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

                    ->where("employees.id", $id)
                    ->where("employee_projects.start_date", ">", $todayDate)
                    ->where('documents_emp_projects.deleted_at', NULL)
                    ->get();
            }


            if (request()->input("no") == 3) {

                // Expired
                $empPrjDocs = DB::table("documents_emp_projects")
                    ->join("employee_projects", "employee_projects.id", "documents_emp_projects.employee_project_id")
                    ->join("employees", "employees.id", "employee_projects.employee_id")
                    ->join("projects", "projects.id", "employee_projects.project_id")

                    ->select("documents_emp_projects.id", "documents_emp_projects.filename", "projects.name", "employee_projects.start_date", "employee_projects.end_date", "employees.employee_id")

                    ->where("employees.id", $id)
                    ->where("employee_projects.end_date", "<", $todayDate)
                    ->where('documents_emp_projects.deleted_at', NULL)
                    ->get();
            }

            // we set index 0 because there won't be more than one of employee_id cuz we came from table row

            $employee_id = "";
            $totalProjects = count($emp_prjs->toArray());

            

            foreach ($empPrjDocs as $empPrjDoc) {

                $employee_id = $empPrjDoc->employee_id;
            }

            $count = count($empPrjDocs);

            if ($count == 0) {
                return redirect()->back()->with("NoAssign", "There is no assign for this request");
            }

           
            return view(
                "employees.project.projectsShow",
                [
                    "employeeProjectsDocs" => $empPrjDocs,
                    "employee_id" => $employee_id,
                    "totalProjects" => $totalProjects,
                    "page" => $page
                ]
            );
        }

        return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
    }




    public function projectIndividualDocDownload($id)
    {
        //dd($id); // employee_project's id

        $docResult = $this->documentationInterface->getFilenameOfDocumentEmpProjectTableById($id)->toArray();

        //dd($docResult);

        $docArrays = [];

        $employeeId = null;
        foreach ($docResult as $docRes) {

            array_push($docArrays, $docRes->filename);
            $employeeId = $docRes->employee_id;
        }

        //dd($employeeId);

        // and we use ZipArchive class and build object in order to able to use its open method
        $zip = new ZipArchive();

        $zipFileName = uniqid() . '_' . 'Employee ' . $employeeId . '\'s documents.zip'; // this is downloaded file's name

        $zipFilePath = storage_path($zipFileName);

        //dd($zip->open($zipFilePath,ZipArchive::CREATE | ZipArchive::OVERWRITE));

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) { // if createing of zip file is success we good to go

            foreach ($docArrays as $docs) {

                $filePath = 'app/public/employees/documents/' . $docs;

                $storagePath = storage_path($filePath);

                $zip->addFile($storagePath, $docs);
            }

            $zip->close();

            // we have to use deleteFileAfterSend(true) if We don't to remain the doc files in our local storage pth
            // if we want to also store it we don't have to use it and it will work fine as well
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {

            // Handle error if the zip file cannot be created
            return response('Failed to create the zip file', 500);
        }

        return redirect()->back()->with("noProject", "There is no projects yet for this employee");
    }
}
