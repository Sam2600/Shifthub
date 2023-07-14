<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Interfaces\AdminInterface;

class AdminRepository implements AdminInterface{

    /**
     * @author KaungHtetSan
     * @date 21/06/2023
     * @param integer => id The admin's ID
     * @return array => query data of admin
     */

    // Get the admin data with id from the admin table
    public function getAdminById($id)
    {
        return Admin::where('id', '=', $id)->first();
    }

}
