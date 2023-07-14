<?php

namespace App\Interfaces;

interface EmployeeInterface {

    public function getAllEmployees();

    public function getEmployeeById($id);

    public function AllEmployees();

    public function getEmployeeProgramming_language_id($id);

    public function AllEmployeesWithTrashed();

    public function getDistinctValues();

    public function getNames();
}
