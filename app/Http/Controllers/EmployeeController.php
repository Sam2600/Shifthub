<?php

namespace App\Http\Controllers;

use PDF;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\EmployeeInterface;
use App\Http\Requests\EmployeeRegisterRequest;
use App\DBTransactions\Employee\EmployeeStore;
use App\DBTransactions\Employee\EmployeeDelete;
use App\DBTransactions\Employee\EmployeeUpdate;
use App\Http\Requests\EmployeeUpdateRequest;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{

    use ResponseAPI;

    protected $employeeInterface;

    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
    }

    /**
     * Display a listing of the all employee from the employees table.
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return Illuminate\View\View => A view to the employees index page
     */
    public function index()
    {  
        // Making the current page number
        $page = request()->input('page') ? request()->input('page') : 1;

        // Catching the route's query strings
        $employee_id = request()->input('employee_id') ? request()->input('employee_id') : NULL;
        $level = request()->input('level') ? request()->input('level') : NULL;
        $career = request()->input('career') ? request()->input('career') : NULL;


        // This process is to get the total numbers of employees and level and careers
        $totalEmployees = $this->employeeInterface->AllEmployees();

        //get total numbers of employees
        $total = $totalEmployees->count();

        // I made major changes in these codes previously i made the bad codes and it runs so many queries behind the process
        $careers = [];
        $levels = [];

        // foreach loop to make the unique level and career array with collection unique
        foreach ($totalEmployees as $totalEmployee) {

            array_push($careers, $totalEmployee->career_id);
            array_push($levels, $totalEmployee->level_id);
        }

        // get distince total levels and careers
        $uniqueCareers = collect($careers)->unique();
        $uniqueLevels = collect($levels)->unique();

        // get all employees without deleted_at
        $employees = $this->employeeInterface->getAllEmployees();

        // get all total number of employees including deleted_at
        $counts = $employees->count();

        if ($counts == 0) {

            // We check the query strings it means search process is proceed
            if ($employee_id != null || $career != null || $level != null) {

                if ($page > 1) {

                    $page = $page - 1;

                    return redirect()->route('employees.index', ["employee_id" => $employee_id, "career" => $career, "level" => $level, "page" => $page]);
                }

                return view('employees.index', [
                    "employees" => $employees,
                    "counts" => $counts,
                    "careers" => $uniqueCareers,
                    "levels" => $uniqueLevels,
                    "total" => $total,
                ]);
            }

            if ($page > 1) {

                $page = $page - 1;

                return redirect()->route('employees.index', ["page" => $page]);
            }

            return view('employees.index', [
                "employees" => $employees,
                "counts" => $counts,
                "careers" => $uniqueCareers,
                "levels" => $uniqueLevels,
                "total" => $total,
            ]);

        }

        if ($employees) { // check if the result is empty or not by checking boolean data

            return view('employees.index', [
                "employees" => $employees,
                "counts" => $counts,
                "careers" => $uniqueCareers,
                "levels" => $uniqueLevels,
                "total" => $total,
            ]);
        }

        return view('employees.index')->with("employeeList", "There is no employee yet");
    }


    /**
     * Show the form for creating new employees.
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return \Illuminate\View\View => A view to employee create form
     */
    public function create()
    {
        if (Session::get('id') == 2) {
            return redirect()->back()->with("wrongAdmin", "Admin ID 2 doesn't have permission to use this route!");
        }

        $page = request()->input('page') ? request()->input('page') : 1;

        //This process is to make auto generate employee_id (0001) and send it with the route to view
        $id = $this->employeeInterface->AllEmployeesWithTrashed();

        $id = count($id); // this step is extra

        $counts = "0000";

        if ($id >= 9) {

            $counts = "000";
            $id = $id + 1;
            $employee_id = $counts . $id;
        } else if ($id >= 99) {

            $counts = "00";
            $id = $id + 1;
            $employee_id = $counts . $id;
        } else if ($id >= 999) {
            $counts = "0";
            $id = $id + 1;
            $employee_id = $counts . $id;
        }

        $id = $id + 1;
        $employee_id = $counts . $id; // now we get the auto generate employee_id and we carry it to the view

        // dd($page);

        return view(
            'employees.create',
            [
                "employee_id" => $employee_id,
                "page" => $page
            ]
        );
    }


    /**
     * Store a newly created employee's data into the employees table.
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param  \Http\Requests\EmployeeRegisterRequest;
     * @return \Illuminate\Http\RedirectResponse => Redirect to the employees' index page
     */
    public function store(EmployeeRegisterRequest $request)
    {

        $page = request()->input('page');

        $store = new EmployeeStore($this->employeeInterface, $request);

        $employees = $store->executeProcess(); // this method return boolean

        if ($employees) { // if true we good to go

            return redirect()->route("employees.index", ['page' => $page])->with('storeMessage', "Employee $request->name is created successfully");
        } else { // if not we go back to the register form with the error message

            return redirect()->back()->with('register_error', "There is an error in the process of employee registration.");
        }
    }

    /**
     * Display the specified employee's data.
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param integer => $id The employee's ID
     * @return \Illuminate\View\View => A view to employee's detail form
     */
    public function show(Request $request, $id)
    {

        //$page = request()->query('page') ? request()->query('page') : 1;

        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {
            // get the programming language datas for the employee
            $progs = $this->employeeInterface->getEmployeeProgramming_language_id($id);

            // Make empty to store the programming language id and send to view
            $array = [];

            foreach ($progs as $prog) {

                array_push($array, $prog->programming_language_id);
            }

            return view('employees.detail', ["employee" => $employee, "progs" => $array]);
        }

        return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
    }


    /**
     * Show the form for editing the employee's data
     * @param  integer => $id The employee's ID
     * @return \Illuminate\View\View => A view to employee update form
     * @author KaungHtetSan
     * @date 21/06/2023
     */
    public function edit($id)
    {
        if (Session::get('id') == 1) {

            return redirect()->back()->with("wrongAdmin", "Admin ID 1 doesn't have permission to use this route!");
        }

        $page = request()->input('page');

        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {
            // get the programming language datas for the employee
            $progs = $this->employeeInterface->getEmployeeProgramming_language_id($id);

            // Make empty to store the programming language id and send to view
            $array = [];

            foreach ($progs as $prog) {
                array_push($array, $prog->programming_language_id);
            }

            return view('employees.edit', ["employee" => $employee, "progs" => $array]);
        }
        return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
    }

    /**
     * Update the employee infos (data) that is get from the table
     * @param  \Illuminate\Http\Request  $request
     * @param  integer => $id  The employee's ID
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return \Illuminate\Http\RedirectResponse => Redirect to the employees' index page if ture,
     *                                               if false.. redirect back to the update page
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {

        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {

            // make an object to get the method of EmployeeUpdate class
            $employee = new EmployeeUpdate($request, $id);

            $result = $employee->executeProcess();

            if ($result) { // if true we good to go

                return redirect()->route('employees.index')->with('updateMessage', "Employee Id $id is updated successfully");
            }
            // if not we go back with error message

            return redirect()->back()->with('updateFailMessage', 'There is an error updating your employee. Please try again');
        }

        return redirect()->back()->with('updateFailMessage', 'There is no employee with that Id');
    }

    /**
     * Remove the employee form the employees table
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param integer => $id The employee's ID
     * @return \Illuminate\Http\RedirectResponse => Redirect to the employees' index page if ture
     *                                               if false.. redirect back with the error message
     */
    public function destroy($id)
    {

        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {

            // make an object to get the method of EmployeeUpdate class
            $employee = new EmployeeDelete($id);

            $result = $employee->executeProcess();

            if ($result) { // if true we good to go

                return redirect()->back()->with('deleteMessage', "Employee id $id is removed.");
            }

            // if not we go back with error message
            return redirect()->back()->with('deleteErrorMessage', "There is an error removing your employee");
        }

        return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
    }


    /**
     * Download the excel file
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return \Illuminate\Http\Response => Excel download
     */
    public function excelExport(Request $request)
    {
        $employeesExcel = uniqid() . "_" . "employees.xlsx";
        return Excel::download(new EmployeesExport($request), $employeesExcel);
    }


    /**
     * Download the pdf file
     * @author KaungHtetSan
     * @date 23/06/2023
     * @return \Illuminate\Http\Response => PDF download
     */
    public function pdfExport()
    {

        // get the employees' data from the empoyees table
        $employees = $this->employeeInterface->getAllEmployees();

        // after that we pass the data that we got earlier
        $pdf = PDF::loadView('employees.pdf.employeePDF', [
            "employees" => $employees
        ])->setPaper('a2', 'portrait');
        $pdfname = uniqid() . "_" . "employee.pdf";
        return $pdf->download($pdfname);
    }


    /**
     * Get the names of employees by jquery ajax
     * @author KaungHtetSan
     * @date 02/07/2023
     * @return Response/json(["name"])
     */

    public function getNames()
    {
        //dd(request()->input());
        $result = $this->employeeInterface->getNames();

        return Response()->json([
            "name" => $result
        ]);
    }
}
