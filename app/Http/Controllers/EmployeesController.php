<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\StoreEmployeesRequest;
use App\Http\Requests\UpdateEmployeesRequest;
use Illuminate\Contracts\Encryption\DecryptException;

class EmployeesController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    public function dashboard(Request $request, $encryptedEmployeeId)
    {
        try {

            $employee_id = Crypt::decryptString($encryptedEmployeeId);
            $authenticatedEmployee = Employees::where('id', $employee_id)->first();
            $vitalsQuery = $authenticatedEmployee->vitals()->orderBy('year');
            $selectedYear = $request->input('selectedYear', date('Y'));
            if ($selectedYear) {
                $vitalsQuery->where('year', $selectedYear);
            }

            $wellnessCoordinator = $authenticatedEmployee->id == 989;

    
            $vitals = $vitalsQuery->get();
    
            $existingVitals = $vitals
                ->where('month', now()->format('m'))
                ->where('year', now()->year)
                ->isNotEmpty();
    
            $vitalExistLastMonth = $vitals
                ->where('month', now()->subMonth()->format('m'))
                ->where('year', now()->subMonth()->year)
                ->isNotEmpty();
    
            $allYears = $authenticatedEmployee->vitals->pluck('year')->unique()->toArray();

            $groupedVitals = $vitals->groupBy('year');
    
            $monthsWithNoRecords = collect(range(1, date('n')))
                ->filter(function ($month) use ($authenticatedEmployee, $selectedYear) {
                    return !$authenticatedEmployee->vitals()
                        ->where('month', str_pad($month, 2, '0', STR_PAD_LEFT))
                        ->where('year', $selectedYear)
                        ->exists();
                });

    
            return view('dashboard', [
                'employee' => $authenticatedEmployee,
                'vitals' => $vitals,
                'vitalsExist' => $existingVitals,
                'years' => $allYears, // Use all years instead of $years
                'groupedVitals' => $groupedVitals,
                'selectedYear' => $selectedYear, // Add selectedYear for displaying in the view
                'encryptedEmployeeId' => $encryptedEmployeeId,
                'vitalExistLastMonth' => $vitalExistLastMonth,
                'monthsWithNoRecords' => $monthsWithNoRecords,
                'wellnessCoordinator' => $wellnessCoordinator,
            ]);
        } catch (DecryptException $e) {
            return redirect()->route('index')->with('error', 'Unknown QR Code');
        }
    }
    


    /**
     * Display a listing of the resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employees $employees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employees $employees)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeesRequest $request, Employees $employees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employees $employees)
    {
        //
    }
}
