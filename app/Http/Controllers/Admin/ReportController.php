<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReportController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.report.index', compact('users'));
    }


}