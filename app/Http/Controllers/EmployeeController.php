<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Traits\HelperFunctions;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\DBTransactions\Employee\EmployeeStore;
use App\DBTransactions\Employee\EmployeeDelete;
use App\DBTransactions\Employee\EmployeeUpdate;

class EmployeeController extends Controller
{
    use HelperFunctions;

    public function __construct(protected EmployeeInterface $employeeInterface) {}

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

        // getting the current page url
        $currentUrl = request()->fullUrl();

        // storing in session in order to use in any routes for this current url
        session()->put("currentUrl", $currentUrl);

        // Catching the route's query strings
        $employee_id = request()->query('employee_id') ? request()->input('employee_id') : NULL;
        $level = request()->query('level') ? request()->input('level') : NULL;
        $career = request()->query('career') ? request()->input('career') : NULL;

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


        if ($counts == 0) { # if count = 0 we check this condition

            if ($employee_id != null || $career != null || $level != null) { # we check the query strings it means search process is proceed

                if (Session::has("noEmployeeMessage")) { # This condition is just for combine testing pagination delete with serch query
                    Session::forget(("noEmployeeMessage"));
                    if ($page > 1) { #if page is still greater than 1 we can substract the page

                        $page = $page - 1;

                        return redirect()->route('employees.index', ["employee_id" => $employee_id, "career" => $career, "level" => $level, "page" => $page])->with('noEmployeeMessage', "There is no employee with that id");
                    }
                }

                if ($page > 1) { # This condition is to check.. if the page is greater than 1 or not

                    $page = $page - 1;

                    // get the employee_id with session
                    $id = Session::get('emp_id');
                    Session::forget('emp_id'); # when we got what we wanted, we delete the session again

                    return redirect()->route('employees.index', ["employee_id" => $employee_id, "career" => $career, "level" => $level, "page" => $page])->with('deleteMessage', "Employee id $id is removed.");
                }

                return view('employees.index', [
                    "employees" => $employees,
                    "counts" => $counts,
                    "careers" => $uniqueCareers,
                    "levels" => $uniqueLevels,
                    "total" => $total,
                ]);
            }

            // This if condition is without search query and simple pagination delete
            if (Session::has("noEmployeeMessage")) { # This condition is just for combine testing pagination delete without search query
                Session::forget(("noEmployeeMessage"));
                if ($page > 1) {

                    $page = $page - 1;

                    return redirect()->route('employees.index', ["page" => $page])->with('noEmployeeMessage', "There is no employee with that id");
                }
            }

            if ($page > 1) {  # if page is still greater than 1 we reduce the page count

                $page = $page - 1;

                // get the employee_id with session
                $id = Session::get('emp_id');
                Session::forget('emp_id');

                return redirect()->route('employees.index', ["page" => $page])->with('deleteMessage', "Employee id $id is removed.");
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
        // if admin logined ID is 1 we redirect back with error message
        if (Session::get('id') == 2) {
            return redirect()->back()->with("wrongAdmin", "Admin ID 2 doesn't have permission to use this route!");
        }

        // We catch the current page number
        $page = request()->input('page') ? request()->input('page') : 1;

        //This process is to make auto generate employee_id (0001) and send it with the route to view
        $count = $this->employeeInterface->getTotalCountOfEmployees();

        $employee_id = $this->autoIncreasedEmployeeID($count);

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
        // get the current page number
        $page = request()->input('page');

        // making object to use EmployeeStore class's method
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
    public function show($id)
    {

        $currentPage = Session::get('currentPage') ? Session::get('currentPage') : 1;

        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) {

            return view('employees.detail', ["employee" => $employee, "page" => $currentPage]);
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

        $previousIndexUrl = Session::get("currentUrl"); // get the current page's full url that we store in session in index methd
        Session::forget("currentUrl"); // after we get what we wanted we delete session

        // if admin login ID is = 2 we redirect back with error message
        if (Session::get('id') == 1) {

            return redirect($previousIndexUrl)->with("wrongAdmin", "Admin ID 1 doesn't have permission to use this route!");
        }

        //catch the current page number
        $page = request()->input('page');

        // get the employee's data with the param $id
        $employee = $this->employeeInterface->getEmployeeById($id);

        if ($employee) { // if true we are good to go

            // get the programming language datas for the employee
            $progs = $this->employeeInterface->getEmployeeProgramming_language_id($id);

            // Make empty to store the programming language id and send to view
            $array = [];

            foreach ($progs as $prog) {
                array_push($array, $prog->programming_language_id);
            }

            return view('employees.edit', ["employee" => $employee, "progs" => $array]);
        }

        return redirect($previousIndexUrl)->with("noEmployeeMessage", "There is no employee with that Id");
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

        if ($employee) { // if true we are good to go

            // make an object to get the method of EmployeeUpdate class
            $employee = new EmployeeUpdate($request, $id);

            $result = $employee->executeProcess();

            if ($result) { // if true we good to go

                return redirect()->route('employees.index')->with('updateMessage', "Employee Id $id is updated successfully");
            }
            // if not we go back with error message

            return redirect()->back()->with('updateFailMessage', 'There is an error updating your employee. Please try again');
        }

        return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
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
        // This process is to pass employee_id to the index page
        session()->put('emp_id', $id);

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
