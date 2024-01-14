<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Rate;

class PeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */
    
    public function toArray(Request $request): array
    {
    
        $connectStartDate = Carbon::parse($this->begin_date, 'UTC');
        $connectEndDate = Carbon::parse($this->end_date, 'UTC');
        $begin_date = $connectStartDate->format('Y-m-d\Th:i:s');
        $end_date =  $connectEndDate->format('Y-m-d\Th:i:s');
        $month = $connectStartDate->translatedFormat('F, Y');
        $hasInRate = Rate::where('period_id',$this->id)->exists();
        
        return [
            'id' => $this->id,
            'begin_date' =>  $begin_date,
            'end_date' => $end_date,
            'month' => $month,
            'hasInRate' => $hasInRate,
        ];
    }
}
