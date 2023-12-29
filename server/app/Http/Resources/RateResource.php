<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Period;
use Carbon\Carbon;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $currentPeriod = Period::find($this->period_id)->begin_date;
        $period =  Carbon::parse($currentPeriod)->translatedFormat('F, Y');
        $month =  Carbon::parse($currentPeriod)->translatedFormat('F, Y');
        $create_date = Carbon::parse($this->create_date)->translatedFormat('d F, Y, H:i');;

        //$end = new Carbon('last day of last month');

        return [
            'id' => $this->id,
            'period_id' => $period,
            'month' => $month,
            'amount_price' => $this->amount_price,
            'create_date' => $create_date
        ];
    }
}
