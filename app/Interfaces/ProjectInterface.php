<?php

namespace App\Interfaces;

interface ProjectInterface
{

    public function getAllProjects();

    public function getProjectById($id);
}
