<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rate;
use App\Http\Resources\RateResource;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = RateResource::collection(Rate::all());

        return response()->json([
            'status' => 'success',
            'rates' => $rates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Rate::create([
            'period_id' => $request->period_id,
            'amount_price' => $request->amount_price,
        ]);

        return response()->json([
            'status' => 'success',
            'response' => 'Новый тариф создан'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rate = Rate::find($id);

        if ($rate) {
            $rate->update([
                'period_id' => $request->period_id,
                'amount_price' => $request->amount_price,
            ]);
    
            return response()->json([
                'status' => 'success',
                'response' => 'Данные обновлены'
            ]);
        }else {
            return response()->json([
                'status' => 'error',
                'response' => 'Тариф не найден'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
