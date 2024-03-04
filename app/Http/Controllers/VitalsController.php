<?php

namespace App\Http\Controllers;

use App\Models\Vitals;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class VitalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store($employee_id, Request $request)
    {
        $authenticatedEmployee = Employees::where('id', $employee_id)->first();

        if (!$authenticatedEmployee) {
            return redirect()->route('index')->with('error', 'Invalid Employee ID');
        }

        $validatedData = $request->validate([
            'month' => 'required',
            'pulse_rate' => 'required|numeric',
            'body_temperature' => 'required|numeric',
            'respiratory_rate' => 'required|numeric',
            'bp' => 'required|string',
            'bmi' => 'required|numeric',
        ]);

        if ($authenticatedEmployee->vitals()->where('month', $validatedData['month'])->exists()) {
            return redirect()->back()->with('error', 'Vitals already recorded for the selected month.');
        }

        $authenticatedEmployee->vitals()->create([
            'month' => $validatedData['month'],
            'year' => now()->year,
            'pulse_rate' => $validatedData['pulse_rate'],
            'body_temperature' => $validatedData['body_temperature'],
            'respiratory_rate' => $validatedData['respiratory_rate'],
            'bp' => $validatedData['bp'],
            'bmi' => $validatedData['bmi'],
        ]);

        return redirect()->back()->with('success', 'Vitals recorded successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Vitals $vitals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vitals $vitals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Vitals $vital, Request $request)
    {
        $validatedData = $request->validate([
            'pulse_rate' => 'required|numeric',
            'body_temperature' => 'required|numeric',
            'respiratory_rate' => 'required|numeric',
            'bp' => 'required|string',
            'bmi' => 'required|numeric',
        ]);

        // Update the vital record with the validated data
        $vital->update($validatedData);

        return redirect()->back()->with('success', 'Vitals updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vitals $vital)
    {

        $vital->delete();

        return redirect()->back()->with('success', 'Vitals record deleted successfully.');
    }

    /* public function filterVitals(Request $request)
    {
        $selectedYear = $request->input('selectedYear');

        // Use $selectedYear to filter vitals or perform any other required logic

        return view('dashboard', ['selectedYear' => $selectedYear]);
    } */

}
