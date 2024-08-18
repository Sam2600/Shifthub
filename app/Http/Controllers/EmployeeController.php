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
		// Catching the route's query strings
		$level = request()->query('level');
		$career = request()->query('career');
		$employee_id = request()->query('employee_id');

		$employees = $this->employeeInterface->getAllEmployees();

		$totalEmployees = $this->employeeInterface->getTotalCountOfEmployees();

		$paginatedEmpCount = count($employees->items());

		$careers = ["Frontend", "Backend", "Mobile", "Fullstack"];
		$levels = ["Beginner", "Junior Engineer", "Engineer", "Senior Engineer"];

		// If there is no employee, we redirect with err message
		if (!$paginatedEmpCount) {

			$page = request()->query('page');
			$page = $page > 1 ? $page - 1 : $page;

			if ($page > 1) {

				if ($employee_id || $career || $level) { # we check the query strings it means search process is proceed

					$queryParams = ["page" => $page, "employee_id" => $employee_id, "career" => $career, "level" => $level];

					return redirect()->route('employees.index', $queryParams);
				}

				return redirect()->route('employees.index', ["page" => $page]);
			}

			return $this->returnToView($employees, $paginatedEmpCount, $careers, $levels, $totalEmployees);
		}

		if ($paginatedEmpCount) { // check if the result is empty or not by checking boolean data

			return $this->returnToView($employees, $paginatedEmpCount, $careers, $levels, $totalEmployees);
		}
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

		$previous_url = url()->previous();

		// get the employee's data with the param $id
		$employee = $this->employeeInterface->getEmployeeById($id);

		if ($employee) {
			return view('employees.detail', ["employee" => $employee, "previous_url" => $previous_url]);
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

		/**
		 * @info Check if session still not exists, we create it
		 */
		if (!Session::get("previous_url")) {
			Session::put("previous_url", url()->previous());
		}

		//if admin login ID is = 2 we redirect back with error message
		if (Session::get('id') == 1) {

			return redirect()->back()->with("wrongAdmin", "Admin ID 1 doesn't have permission to use this route!");
		}

		// get the employee's data with the param $id
		$employee = $this->employeeInterface->getEmployeeById($id);

		if ($employee) { // if true we are good to go
			return view('employees.edit', ["employee" => $employee]);
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

		/**
		 * @info get session value and destroy immdiately
		 */
		$previous_url = Session::get("previous_url");
		Session::remove("previous_url");

		$employee = $this->employeeInterface->getEmployeeById($id);

		if ($employee) { // if true we are good to go

			// make an object to get the method of EmployeeUpdate class
			$employee = new EmployeeUpdate($request, $id);

			$result = $employee->executeProcess();

			if ($result) { // if true we good to go

				return redirect($previous_url)->with('updateMessage', "Employee Id $id is updated successfully");
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
		// get the employee's data with the param $id
		$employee = $this->employeeInterface->getEmployeeById($id);

		if ($employee) {

			// make an object to get the method of EmployeeUpdate class
			$employee = new EmployeeDelete($id);

			$result = $employee->executeProcess();

			if ($result) { // if true we good to go

				return redirect()->back()->with("noEmployeeMessage", "There is no employee with that Id");
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
		$result = $this->employeeInterface->getNames();

		return Response()->json([
			"name" => $result
		]);
	}

	/**
	 * @author Kaung Htet San
	 */
	private function returnToView($employees, $paginatedEmpCount, $careers, $levels, $totalEmployees)
	{
		return view('employees.index', [
			"employees" => $employees,
			"counts" => $paginatedEmpCount,
			"careers" => $careers,
			"levels" => $levels,
			"total" => $totalEmployees,
		]);
	}
}
