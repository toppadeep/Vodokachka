<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rate;
use App\Http\Resources\RateResource;
use Illuminate\Validation\Rule;

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

        $validated = validator($request->all(), [
            'period_id' => [ 'required', 'unique:rates' ],
            'amount_price' => [ 'required', 'numeric', 'min:1', 'max:10000' ]
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        }else {
            Rate::create([
                'period_id' => $request->period_id,
                'amount_price' => $request->amount_price,
            ]);

            return response()->json([
                'status' => 'success',
                'response' => 'Новый тариф создан'
            ]);
        };
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
            $validated = validator($request->all(), [
                'period_id' => [ 'required', Rule::unique('rates')->ignore($rate) ],
                'amount_price' => [ 'required', 'numeric', 'min:1', 'max:10000' ]
            ]);
    
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()]);
            }else {
                $rate->update($request->all());
    
                return response()->json([
                    'status' => 'success',
                    'response' => 'Данные обновлены'
                ]);
            };
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
        try {
            $rate = Rate::find($id);
            if ($rate) {
                $rate->delete();
        
                return response()->json([
                    'status' => 'success',
                    'response' => 'Тариф удалён'
                ]);
            }else {
                return response()->json([
                    'status' => 'error',
                    'response' => 'Тариф не найден'
                ]);
            };

        }catch (QueryException $e) {

            return response()->json($e);
    
        }
    }
}
