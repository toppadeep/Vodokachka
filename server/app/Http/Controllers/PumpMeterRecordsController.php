<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PumpMeterRecords;
use App\Http\Resources\PumpMeterRecordsResource;

class pumpMeterRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pumps = PumpMeterRecordsResource::collection(PumpMeterRecords::all());

        return response()->json([
            'status' => 'success',
            'pumps' => $pumps,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pump = PumpMeterRecords::create([
            'period_id' => $request->period_id,
            'amount_volume' => $request->amount_volume,
        ]);

        if ($pump) {
            return response()->json([
                'status' => 'success',
                'response' => 'Показания добавлены'
            ], 200);
        }else {
            return response()->json([
                'status' => 'error',
                'response' => 'Измените период'
            ]);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pump = Pump::find($id);

        if ($pump) {
            $pump->update([
                'period_id' => $request->period_id,
                'amount_volume' => $request->amount_volume,
            ]);
    
            return response()->json([
                'status' => 'success',
                'response' => 'Показания обновлены'
            ]);
        }else {
            return response()->json([
                'status' => 'error',
                'response' => 'Показания счётчика не найдены'
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
