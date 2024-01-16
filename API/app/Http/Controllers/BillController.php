<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Http\Resources\BillResource;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bills = BillResource::collection(Bill::all());

        return response()->json([
            'status' => 'success',
            'bills' => $bills,
        ]);
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
            'resident_id' => ['required', 'integer'],
            'period_id' => ['required', 'integer'],
            'amount_rub' => ['required', 'min:1', 'max:100000'],
        ]);

       if ($validated->fails()) {
            return response()->json(['errors'=>$validated->errors()]);
       }else {
        Bill::create([
            "resident_id" => $request->resident_id,
            "period_id" => $request->period_id,
            "amount_rub" => $request->amount_rub,
        ]);

        return response()->json(['status' => 'success'], 201);
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
        $bill = Bill::find($id);

        if ($bill) {
            $validated = validator($request->all(), [
                "period_id" => ['nullable', 'integer'],
                "amount_rub" => ['nullable', 'numeric', 'min:1', 'max:100000'],
            ]);
    
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()], 422);
            }else {
    
                $bill->update($request->all());
        
                return response()->json(['status' => 'success'], 200);
            }
        }else {
            return response()->json([
                'status' => 'error',
                'response' => 'Счёт не найден'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $bill = Bill::find($id);

            if ($bill) {
                $bill->delete();
        
                return response()->json([
                    'status' => 'success',
                    'response' => 'Счёт удалён'
                ]);
            }else {
                return response()->json([
                    'status' => 'error', 
                    'response' => 'Счёт не найден'
                ]);
            };

        }catch (QueryException $e) {

            return response()->json($e);
    
        };
    }
}
