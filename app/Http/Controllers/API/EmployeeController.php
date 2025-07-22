<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function getEmployeesByDivision()
    {
        $user = Auth::user();
        $employees = Employee::where('div_id', $user->div_id)->get();

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }
}
