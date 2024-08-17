<?php

namespace App\DBTransactions\Employee;

use App\Classes\DBTransaction;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class EmployeeUpdate extends DBTransaction
{

    protected $request;
    protected $id;

    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }


    /**
     * @author KaungHtetSan
     * @create 06/21/2023
     * @param  no param .. just use the class's $id variable and $requset variable into the constructor
     * @return array
     */
    public function process()
    {

        $employee = Employee::find($this->id);

        // 1st we need to check if there is input new photo or not.. if there is we update it and delete old photo
        if (request()->hasFile('photo')) {

            $oldPhoto = storage_path('app/public/employees/photos/' . $employee->photo);

            if (file_exists($oldPhoto)) {

                unlink($oldPhoto);
            }

            $file = $this->request->file('photo');
            $fileName = uniqid() . $file->getClientOriginalName();
            $file->storeAs('public/employees/photos', $fileName);
            $employee->photo = $fileName;
        }

        $employee->name = trim($this->request->name);
        $employee->email = $this->request->email;
        $employee->phone = $this->request->phone;
        $employee->dateOfBirth = $this->request->dateOfBirth;
        $employee->nrc = trim($this->request->nrc);
        $employee->gender = $this->request->gender;
        $employee->address = $this->request->address;

        // got data as array and make strings to store it
        $programming_languages = $this->request->prog_lang;
        $employee->programming_languages = implode(', ', $programming_languages);

        // got data as array and make strings to store it
        $languages = $this->request->language;
        $employee->languages = implode(', ', $languages);

        $employee->career = $this->request->career;
        $employee->level = $this->request->level;

        $employee->updated_by = Session::get('id');
        $employee->updated_at = Carbon::now();
        $employee->update();

        return $employee ? ['status' => true, 'error' => ''] : ['status' => false, 'error' => 'Failed!'];
    }
}


        /* This is Old style complicated and too much code steps to check it but more clean in table which is barely use as main table

        $countOfReqArr = count($resultsArray); // [1,2,3]
        $countOfOldArr = count($prog_langs); //   [6]

        dd($resultsArray, $prog_langs, $final);

        if ($countOfReqArr > $countOfOldArr) {

            foreach($prog_langs as $prog_lang) {
                in_array($prog_lang, $final);
            }


            $new_prog_langs = [];

            foreach ($final as $fin) {

                $arr =  [
                    "employee_id" => $fin->id,
                    "programming_language_id" => $fin,
                    "created_by" => Session::get('id'),
                    "updated_by" => Session::get('id'),
                ];

                array_push($new_prog_langs, $arr);
            }

            DB::table('employees_programming_languages')->where('employee_id', '=', $employee->id)->insert($new_prog_langs);
            DB::commit();
            return redirect()->route('employees.index')->with('updateMessage', "Employee Id $employee->id is updated successfully");

        } else if ($countOfOldArr > $countOfReqArr) {

            DB::table('employees_programming_languages')->whereIn('programming_language_id', $final)->delete();
            DB::commit();
            return redirect()->route('employees.index')->with('updateMessage', "Employee Id $employee->id is updated successfully");
        }
        */
