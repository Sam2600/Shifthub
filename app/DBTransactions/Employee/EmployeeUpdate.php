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

            if(file_exists($oldPhoto)) {

                unlink($oldPhoto);

            }

            $file = $this->request->file('photo');
            $fileName = uniqid() . $file->getClientOriginalName();
            $file->storeAs('public/employees/photos', $fileName);
            $employee->photo = $fileName;
        }

        /*
        get file from data

        $file = $this->request->file('photo');
        $fileName = uniqid() . $file->getClientOriginalName();
        $file->storeAs('public/', $fileName);
        $employee->photo = $fileName;

        */


        $employee->name = $this->request->name;
        $employee->email = $this->request->email;
        $employee->phone = $this->request->phone;
        $employee->dateOfBirth = $this->request->dateOfBirth;
        $employee->nrc = $this->request->nrc;
        $employee->gender = $this->request->gender;
        $employee->address = $this->request->address;

        $languages = $this->request->language;
        $language = implode(', ', $languages);

        $employee->language = $language;

        $employee->career_id = $this->request->career;
        $employee->level_id = $this->request->level;

        $employee->updated_by = Session::get('id');
        $employee->updated_at = Carbon::now();
        $employee->update();


        // From now we are try to update the programming languages in the pivot table (emp_program_lang_id)
        // First we check the requested programming languages are the same with old programming languages
        // By making two array and check with some function array_diff()

        // Firstly we get the query data from employees_programming_languages table
        $results = DB::table('employees_programming_languages')->where('employee_id', '=', $employee->id)->get();
        $result = $results->toArray();

        if (count($result) <= 0) {

            return ['status' => false, 'error' => 'Failed!'];
        }

        // making array to check thats get from query data ( $results)
        $resultsArray = [];

        foreach ($results as $result) {
            array_push($resultsArray, $result->programming_language_id);
        }

        // This is requested programming language array
        $prog_langs = $this->request->prog_lang;

        // Now we compare two array to decide that we should update or not the programming_lang_id
        $resultOne = array_diff($resultsArray, $prog_langs);
        $resultTwo = array_diff($prog_langs, $resultsArray);
        $final = array_merge($resultOne, $resultTwo);

        //$final = []; => if merged two array are equal
        //$final = [0 => 3, 1 => 5] if these two are not equal
        // This is where updated and old array are same it means there is no updated about prog_lang_id
        $result = count($final);

        if ($result = 0) {

            return ['status' => true, 'error' => ''];
        }

        DB::table('employees_programming_languages')->where('employee_id', $employee->id)->delete();

        $new_prog_langs = [];

        foreach ($prog_langs as $fin) {
            $arr =  [
                "employee_id" => $employee->id,
                "programming_language_id" => $fin,
                "created_by" => Session::get('id'),
                "updated_by" => Session::get('id'),
            ];

            array_push($new_prog_langs, $arr);
        }

        $result = DB::table('employees_programming_languages')->where('employee_id', '=', $employee->id)->insert($new_prog_langs);

        if ($result) {

            return ['status' => true, 'error' => ''];
        }

        return ['status' => false, 'error' => 'Failed!'];
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
