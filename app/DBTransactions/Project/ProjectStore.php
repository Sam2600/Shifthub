<?php

namespace App\DBTransactions\Project;

use App\Classes\DBTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectStore extends DBTransaction
{
    /**
     * @author KaungHtetSan
     * @create 06/21/2023
     * @return array
     */

    public function process()
    {
        // 1st we need to check if there is a project with that name is already or not by making sure with ingore caseSensitivity

        $checkProject = DB::table('projects')->where(DB::raw('lower(name)'),strtolower(request()->input('project')))->get()->toArray();
        $count = count($checkProject);

        if($count != 0) {
            return ['status' => false, 'error' => "Projects cannot be duplicated"];
        }

        $result = DB::table('projects')->insert([
            "name" => request()->get('project'),
            "created_by" => Session::get('id'), // This comes from login route admin's id
            "updated_by" => Session::get('id'), // This comes from login route admin's id
            "created_at" => Carbon::now()
        ]);

        if ($result) {

            return ['status' => true, 'error' => ''];
        }
        return ['status' => false, 'error' => 'Failed!'];
    }
}
