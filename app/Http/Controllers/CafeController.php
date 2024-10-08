<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CafeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:cafe-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:cafe-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:cafe-edit', ['only' => ['update']]);
        $this->middleware('permission:cafe-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $cafes = Cafe::search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($cafes, 200, ['Get List Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        try {
            $cafes = Cafe::where('status','1')->get();
            return $this->sendResponse($cafes, 200, ['Get List Successfully.'], true);
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
                'item' => 'required|unique:cafes',
                'price' => 'required',
            ]);
            $cafe = Cafe::create($request->only(['item', 'price', 'status']));

            return $this->sendResponse($cafe, 200, ['Stored Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cafe $cafe)
    {
        try {
            return $this->sendResponse($cafe, 200, ['Data get successfully,'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cafe $cafe)
    {
        try {
            $this->validate($request, [
                'item' => "required|unique:cafes,item,{$cafe->id}",
                'price' => 'required',
            ]);
            $cafe->update($request->only(['item', 'price', 'status']));
            return $this->sendResponse($cafe, 200, ['Updated successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cafe $cafe)
    {
        try {
            $cafe->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
