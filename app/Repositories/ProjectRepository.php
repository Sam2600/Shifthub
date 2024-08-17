<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Interfaces\ProjectInterface;

class ProjectRepository implements ProjectInterface
{

    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @return array => query data of projects table
     */

    public function getAllProjects()
    {
        return DB::table('projects')->get();
    }

    public function getProjectById($id)
    {
        return DB::table("projects")->where("id", $id);
    }
}
