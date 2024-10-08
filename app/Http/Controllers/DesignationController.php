<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:designation-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:designation-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:designation-edit', ['only' => ['update']]);
        $this->middleware('permission:designation-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $designations = Designation::search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($designations, 200, ['Get List Successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|unique:designations',
                'department_id' => 'required',
            ]);
            $designation = Designation::create($request->only(['title', 'description', 'status', 'department_id']));

            return $this->sendResponse($designation, 200, ['Stored Successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        try {
            return $this->sendResponse($designation, 200, ['Data get successfully,'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $this->validate($request, [
            'title' => "required|unique:designations,title,{$designation->id}",
            'department_id' => 'required',
        ]);
        try {
            $designation->update($request->only(['title', 'description', 'status', 'department_id']));
            return $this->sendResponse($designation, 200, ['Updated successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
