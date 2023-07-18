<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;
use App\Interfaces\EmployeeInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class EmployeeStore extends DBTransaction
{

    protected $employeeInterface, $request;

    public function __construct(EmployeeInterface $employeeInterface, $request)
    {
        $this->employeeInterface = $employeeInterface;
        $this->request = $request;
    }


    /**
     * @author KaungHtetSan
     * @create 06/21/2023
     * @param  no param.. use the class's $employeInterface variable and $request variable
     * @return array
     */
    public function process()
    {
       //This process is to make auto generate employee_id (0001) and send it with the route to view
       $id = $this->employeeInterface->AllEmployeesWithTrashed();

       $id = count($id); // this step is extra

       $counts = "0000";

       if ($id > 9) {

           $counts = "000";
           $id = $id + 1;
           $employee_id = $counts . $id;
       } else if ($id > 99) {

           $counts = "00";
           $id = $id + 1;
           $employee_id = $counts . $id;
           
       } else if ($id > 999) {
           $counts = "0";
           $id = $id + 1;
           $employee_id = $counts . $id;
       }

       $id = $id + 1;
       $employee_id = $counts . $id;

       // now we get the auto generate employee_id and Start storing employee data
        $employee = new Employee();
        $employee->employee_id = $employee_id;
        $employee->name = trim($this->request->name);
        $employee->nrc = trim($this->request->nrc);
        $employee->phone = $this->request->phone;
        $employee->email = $this->request->email;
        $employee->gender = $this->request->gender;
        $employee->dateOfBirth = $this->request->dateOfBirth;
        $employee->address = $this->request->address;

        // got data as array and make strings to store it
        $languages = $this->request->language;
        $language = implode(', ', $languages);

        $employee->language = $language;

        $employee->career_id = $this->request->career;
        $employee->level_id = $this->request->level;

        //photo process
        $file = $this->request->file('photo');
        $fileName = uniqid() . $file->getClientOriginalName();
        $file->storeAs('public/employees/photos', $fileName); // store in storage's public folder
        $employee->photo = $fileName;

        $employee->created_by = Session::get('id'); // This comes from login route admin's id
        $employee->created_at = Carbon::now();
        $employee->save();
        

        //When employee is stored, we keep it's result and try to add the data to the pivot table => employee_programming_languages table

        $emp_prog = [];

        foreach ($this->request->prog_lang as $prog_lang) {

            $empProgs = [
                "employee_id" => $employee->id,
                "programming_language_id" => $prog_lang,
                "created_by" => Session::get('id'),
            ];

            array_push($emp_prog, $empProgs);
        }

        $prog_lang = DB::table('employees_programming_languages')->insert($emp_prog);

        // When both process are successed we proceed the storing data process
        
        if ($prog_lang && $employee) {

            return ['status' => true, 'error' => ''];
        }
        
        // When both or one of them failed we also proceed with the false data
        return ['status' => false, 'error' => 'Failed!'];
    }
}

