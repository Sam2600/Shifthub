<?php

namespace App\DBTransactions\Employee_project;

use App\Classes\DBTransaction;
use Illuminate\Support\Facades\DB;

// object param is $request that is get from the Request $request from controller

class Employee_projectDelete extends DBTransaction
{


    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function process()
    {
        // 1st we have to make sure that there is no employees that is connected with this project
        $projects = DB::table('employees_projects')->where('project_id', $this->request->project)->get()->toArray();


        if (count($projects) > 0) {

            return ['status' => false, 'error' => 'Failed!'];
            //Project removing is failed. Because some other employeers are doing job with that project.
        }

        // if the count is zero that means there is no employees that is working with that project name
        DB::table('projects')->where('id', $this->request->project)->delete();

        DB::table('employees_projects')->where('project_id', $this->request->project)->delete();

        return ['status' => true, 'error' => ''];
    }
}
