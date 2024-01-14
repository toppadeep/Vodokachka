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

        $validated = validator($request->all(), [
            'period_id' => ['required', 'unique:pump_meter_records'],
            'amount_volume' => ['required', 'numeric', 'min:1', 'max:150']
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        }else {
            PumpMeterRecords::create([
                'period_id' => $request->period_id,
                'amount_volume' => $request->amount_volume,
            ]);

            return response()->json(['status' => 'success'], 201);
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
        $pump = PumpMeterRecords::find($id);

        if ($pump) {
            $validated = validator($request->all(), [
                'amount_volume' => ['required', 'numeric', 'min:1', 'max:150']
            ]);
    
            if ($validated->fails()) {
                return response()->json(['errors' => $validated->errors()]);
            }else {
                $pump->update($request->all());
    
                return response()->json(['status' => 'success'], 201);
            };
    
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
        $pump = PumpMeterRecords::find($id);

        if (!$pump) {
            return response()->json(['status' => 'error']);
        }else {
            $pump->delete();
            return response()->json(['status' => 'success', 'response' => 'Показания удалены']);
        }
    }
}
