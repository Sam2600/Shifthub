<?php

namespace App\DBTransactions\Employee;

use Carbon\Carbon;
use App\Models\Employee;
use App\Classes\DBTransaction;
use App\Traits\HelperFunctions;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Session;


class EmployeeStore extends DBTransaction
{
    use HelperFunctions;

    public function __construct(protected EmployeeInterface $employeeInterface, protected $request) {}

    /**
     * @author KaungHtetSan
     * @create 2023/6/21
     * @update 2024/8/17
     * @return array result
     */
    public function process()
    {
        $count = $this->employeeInterface->getTotalCountOfEmployees();

        $employee_id = $this->autoIncreasedEmployeeID($count);

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
        $programming_languages = $this->request->prog_lang;
        $employee->programming_languages = implode(', ', $programming_languages);

        // got data as array and make strings to store it
        $languages = $this->request->language;
        $employee->languages = implode(', ', $languages);

        $employee->career = $this->request->career;
        $employee->level = $this->request->level;

        //photo process
        $file = $this->request->file('photo');
        $fileName = uniqid() . $file->getClientOriginalName();
        $file->storeAs('public/employees/photos', $fileName); // store in storage's public folder
        $employee->photo = $fileName;

        $employee->created_by = Session::get('id'); // This comes from login route admin's id
        $employee->created_at = Carbon::now();
        $employee->save();

        return $employee ? ['status' => true, 'error' => ''] : ['status' => false, 'error' => 'Failed!'];
    }
}
