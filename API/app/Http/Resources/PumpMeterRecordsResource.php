<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Period;
use Carbon\Carbon;

class PumpMeterRecordsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $period = Carbon::parse(Period::find($this->period_id)->begin_date)->translatedFormat('F, Y');

        return [
            'id' => $this->id,
            'period_id' => $period,
            'amount_volume' => $this->amount_volume
        ];
    }
}
