<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ResidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       
        $currentDate = Carbon::now();
        $connectStartDate = Carbon::parse($this->start_date);
        $start_date = $connectStartDate->format('Y-m-d\Th:i:s');
        $diffDateConnected = $currentDate->diffForHumans($connectStartDate);
        
        

        return [
            'id' => $this->id,
            'fio' => $this->fio,
            'area' => $this->area,
            'start_date' => $start_date,
            'timeToConnected' => $diffDateConnected,
        ];
    }
}
