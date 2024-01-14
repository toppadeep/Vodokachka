<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Http\Resources\PeriodResource;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periods = PeriodResource::collection(Period::all());
        return response()->json(['status' => 'success', 'periods' => $periods], 200);
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
        $validated = validator($request->all(), [
            "begin_date" => ['required', 'date', 'before:end_date'],
            "end_date" => ['required', 'date', 'after:begin_date']
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }else {

            Period::create([
                "begin_date" => $request->begin_date,
                "end_date" => $request->end_date
            ]);
    
            return response()->json(['status' => 'success'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $period = Period::find($id);

        if ($period) {
            $validated = validator($request->all(), [
                "begin_date" => ['nullable', 'date', 'before:end_date'],
                "end_date" => ['nullable', 'date', 'after:begin_date']
            ]);
    
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }else {
    
                $period->update($request->all());
        
                return response()->json(['status' => 'success'], 200);
            }
        }else {
            return response()->json([
                'status' => 'error',
                'response' => 'Период не найден'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $period = Period::find($id);
            if ($period) {
                $period->delete();
        
                return response()->json([
                    'status' => 'success',
                    'response' => 'Период удалён'
                ]);
            }else {
                return response()->json([
                    'status' => 'error',
                    'response' => 'Период не найден'
                ]);
            };

        }catch (QueryException $e) {

            return response()->json($e);
    
        }
    }
}
