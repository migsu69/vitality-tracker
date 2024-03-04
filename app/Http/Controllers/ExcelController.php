<?php
// app/Http/Controllers/ExcelController.php

namespace App\Http\Controllers;

use App\Exports\VitalsExport;
use App\Models\Vitals;
use App\Models\Employees;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ExcelController extends Controller
{
    public function export(Request $request)
    {
        $startMonth = 1; // Replace with the start month
        $endMonth = 12; // Replace with the end month
        $selectedYear = $request->input('selectedYear', date('Y'));

        $vitalsRecords = Vitals::whereBetween('month', [$startMonth, $endMonth])
                                ->where('year', '=', $selectedYear)
                                ->get();

        $rowData = [];

        foreach ($vitalsRecords as $record) {
            $employeeRecord = Employees::select('last_name', 'first_name')
                                        ->where('id', $record->employee_id)
                                        ->first();

            // Convert numeric month to month name
            $monthName = Carbon::create()->month($record->month)->format('F');

            $rowData[] = [

                'first_name' => $employeeRecord->first_name,
                'last_name' => $employeeRecord->last_name,
                'month' => $monthName,
                'year' => $record->year,
                'pulse_rate' => $record->pulse_rate,
                'body_temperature' => $record->body_temperature,
                'respiratory_rate' => $record->respiratory_rate,
                'bp' => $record->bp,
                'bmi' => $record->bmi,

            ];
        }

        // Sort the data by employee_id and month
        $sortedData = (new VitalsExport($rowData))->sortByMonth();

        // Create a new array with the combined data and add the table name in the first row
        $finalData = [
            ['table_name' => 'First Name', 'Last Name', 'Month', 'Year', 'Pulse Rate', 'Body Temperature', 'Respiratory Rate', 'Blood Pressure', 'Body Mass Index'],
            $sortedData,
        ];

        return Excel::download(new VitalsExport($finalData), 'Vitality_tracker.xlsx');
    }
}

