<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
        $begin_date = $connectStartDate->translatedFormat('d F, Y, H:i');
        $end_date =  $connectEndDate->translatedFormat('d F, Y, H:i');
        $month = $connectStartDate->translatedFormat('F, Y');

        return [
            'id' => $this->id,
            'begin_date' =>  $begin_date,
            'end_date' => $end_date,
            'month' => $month
        ];
    }
}
