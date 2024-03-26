<?php

namespace App\Http\Controllers;

use App\Jobs\CreateSalaryJob;
use App\Models\ImportCsv;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportCsvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $csv = ImportCsv::with(['imports' => function($query) {
                $query->select('name', 'expected_hrs','expected_min', 'earned_hrs', 'earned_min',
                'overtime_hrs','overtime_min','loan_deduction','fine_deduction','cafe_deduction','wfh','bonus','month_salary');
            }, 'imports.user'])->latest()->paginate(10);;
            return $this->sendResponse($csv, 200, ['CSV List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $importCsv = ImportCsv::findOrFail($id);
            $importCsv->load('imports.user');
            return $this->sendResponse($importCsv, 200, ['CSV List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImportCsv $importCsv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImportCsv $importCsv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $ImportCsv = ImportCsv::findOrFail($id);
            $ImportCsv->delete();
            return $this->sendResponse(null, 200, ['CSV Deleted Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Generate Salary resource from storage.
     */

    public function salaryGenerate(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
             dispatch(new CreateSalaryJob($request->id));
            return $this->sendResponse(null, 200, ['Salary Generate successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

}
