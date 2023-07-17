<?php

namespace App\Http\Controllers;

use App\DBTransactions\Employee_project\Employee_projectDelete;
use App\DBTransactions\Employee_project\Employee_projectStore;
use Illuminate\Http\Request;
use App\Interfaces\ProjectInterface;
use App\Interfaces\EmployeeInterface;
use App\DBTransactions\Project\ProjectStore;
use App\Http\Requests\EmployeeProjectsRequest;
use App\Interfaces\Employee_projectInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    // Declaring the variables to be able to use the interfaces' methods that we implemented
    protected $employeeInterface;
    protected $projectInterface;
    protected $employee_projectInterface;

    public function __construct(EmployeeInterface $employeeInterface, ProjectInterface $projectInterface, Employee_projectInterface $employee_projectInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->projectInterface = $projectInterface;
        $this->employee_projectInterface = $employee_projectInterface;
    }


    /**
     * Display a from to assign projects for the employees.
     * @author Kaung Htet San
     * @date 28/6/2023
     * @return \Illuminate\Http\Response => view to the project assign form
     */
    public function projectForm()
    {
        // if the admin is logined with ID => 2
        if(Session::get('id') == 2) { // we go back with error message

            return redirect()->back()->with("wrongAdmin", "Admin ID 2 doesn't have permission to use this route!");
        }

        // Get the employees data from the employees table
        $employees = $this->employeeInterface->AllEmployees();

        return view('employees.project.assign', [
            "employees" => $employees,
        ]);
    }


    /**
     * Get all employees, projects, and pivot table's datas.
     * @author Kaung Htet San
     * @date 02/7/2023
     * @return \Illuminate\Http\Response => view to the project assign form
     */
    public function fetchAllDatas()
    {

        // Get the projects data from the projects table
        $projects = $this->projectInterface->getAllProjects();

        // We make a count for the number of rows to check conditions
        $projectCount = count($projects);

        // Get the joined datas of employees and projects from the employee_projects table
        $employee_projects = $this->employee_projectInterface->getJoinedEmployeeProject();


        return response()->json([
            //"employees" => $employees,
            "projectCount" => $projectCount,
            "projects" => $projects,
            "employee_projects" => $employee_projects
        ]);
    }


    /**
     * Store a newly created project data into the projects table.
     * @author KaungHtetSan
     * @date 28/6/2023
     * @return \Illuminate\Http\RedirectResponse => Redirect to the employees.project route if true, if false redirect back wih the fail mesage
     */

    public function EmployeeprojectAdd(Request $request)
    {
        // First we validate the request's input
        $validate = Validator::make($request->all(), [

            "project" => "required|min:3"

        ]);


        if ($validate->fails()) { # if fails we go back with error codes and message

            return response()->json([
                "status" => 400,
                "message" => $validate->getMessageBag(),
            ]);
        }

        $project = new ProjectStore();

        $result = $project->executeProcess();
        $message = $project->process()["error"];

        if ($result) { # if true we go back with success code and message

            return response()->json([
                "status" => 200,
                "message" => 'Project is created succefully.'
            ]);

        } else { # if false we we go back with error code and message

            return response()->json([
                "status" => 404,
                "message" => $message
            ]);
        }
    }


    /**
     * Remove projects table and employee_projects table's row datas
     * @author Kaung Htet San
     * @date 28/6/2023
     * @return \Illuminate\Http\RedirectResponse => Redirect back to the project assign form with success or fail messages
     */

    public function EmployeeprojectRemove(Request $request)
    {

        // First we validate the request's input
        $validate = Validator::make($request->all(), [

            "project" => "required"

        ]);

        if ($validate->fails()) { # if fails we go back with error codes and message

            return response()->json([
                "status" => 400,
                "message" => $validate->getMessageBag()
            ]);
        }

        $result = new Employee_projectDelete($request);

        $projects = $result->executeProcess();
        $errorMessage = $result->process()["error"];

        if ($projects == FALSE) { # if false we we go back with error code and message

            return response()->json([
                "status" => 403,
                "message" => $errorMessage
            ]);
        }

         # if true we go back with success code and message
        return response()->json([
            "status" => 200,
            "message" => 'Project removed successfully.'
        ]);
    }


    /**
     * Store a newly created projects and employees data into the employee_projects table.
     * @author Kaung Htet San
     * @date 28/6/2023
     * @return \Illuminate\Http\RedirectResponse => Redirect back to the project assign form with success or fail messages
     */

    public function projectAssign(EmployeeProjectsRequest $request)
    {

        // 1st we need to make sure that the new request's start date is less than request's end date
        $checkStartDate = $request->input('startDate');
        $checkEndDate = $request->input('endDate');

        $requestStartDate = Carbon::parse($checkStartDate);
        $requestEndDate = Carbon::parse($checkEndDate);

        //This step is even new start date is greater than old end date we have to check that [ New startdate is must be greater than or equal today and New Enddate]
        if ($requestStartDate >= Carbon::today()) {

            if ($requestStartDate >= $requestEndDate) {

                return response()->json([
                    "status" => 1,
                    "message" => "Start date must be greater than end date"
                ]);
            }

            // create the object to get the method of Employee_projectStore class
            $emp_prj = new Employee_projectStore($request);

            $result = $emp_prj->executeProcess();

            if ($result) { // if true we are good to go!

                return response()->json([
                    "status" => 200,
                    "message" => "Project is assigned successfully",
                ]);
            }

            // if false redirect back with fail message
            return response()->json([
                "status" => 3,
                "message" => "There is an conflict between old assigns dates and your new assign dates. Please try again"
            ]);
        }

        return response()->json([
            "status" => 4,
            "message" => "Start date must be greater than or equal with tody date"
        ]);
    }
}
