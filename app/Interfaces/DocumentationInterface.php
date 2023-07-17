<?php

namespace App\Interfaces;

interface DocumentationInterface {

    public function getFilenameOfDocumentEmpProjectTableByEmployeeProjectId($id);

    public function getFilenameOfDocumentEmpProjectTableById($id);

    public function getTableJoinedQueryByEmployeeId($id);

    public function getTableJoinedQueryByEmployeeIdAndTodayDate($id, $todayDate); // where start_date <= today date and end date >= today date

    public function getTableJoinedQueryByEmployeeIdAndTodayDateIsgreaterThanStartDate($id, $todayDate);

    public function getTableJoinedQueryByEmployeeIdAndTodayDateIsLessThanStartDate($id, $todayDate);
}


