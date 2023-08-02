<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    //
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users');
    }
}
