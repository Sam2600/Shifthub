<?php

namespace App\Repositories;

use App\Interfaces\ProjectInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectRepository implements ProjectInterface {

     /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of projects table
     */

    public function getAllProjects()
    {
        return Project::all();
    }

    public function getProjectById($id)
    {
        return DB::table("projects")->where("id", $id);
    }
}
