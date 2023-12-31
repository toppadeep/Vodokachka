<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Http\Resources\ResidentResource;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residents = ResidentResource::collection(Resident::all());

        return response()->json([
            'status' => 'success',
            'residents' => $residents
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Resident::create([
            "fio" => $request->fio,
            "area" => $request->area,
            "start_date" => $request->start_date
        ]);
        

        return response()->json([
            'status' => 'success', 'response' => "Дачник подключен"
        ], 200);
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
    
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $resident = Resident::find($id);

        if (!$resident) {
            return response()->json([
                'status' => 'error', 'response' => "Дачник не найден"
            ], 200);
        }else {
            $resident->update($request->all());
            return response()->json([
                'status' => 'success', 'response' => "Данные обновлены"
            ], 200);
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resident = Resident::find($id);
        
        if ($resident) {
            $resident->delete();
    
            return response()->json([
                'status' => 'success',
                'response' => 'Дачник удалён'
            ]);
        }else {
            return response()->json([
                'status' => 'Not found',
                'response' => 'Пользователь не найден'
            ]);
        }
    }
}
