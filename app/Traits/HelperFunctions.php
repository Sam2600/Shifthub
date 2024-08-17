<?php

namespace App\Traits;

trait HelperFunctions
{

    /**
     * @author Kaung Htet San
     * @created 2024/8/17
     * @return string autoIncreasedID
     */
    public function autoIncreasedEmployeeID(int $count): string
    {
        return str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    }
}
