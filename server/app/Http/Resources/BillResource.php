<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Resident;
use App\Models\Period;
use Carbon\Carbon;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $begin_date = Carbon::parse(Resident::find($this->resident_id)->start_date)->translatedFormat('d F, Y, H:i');
        $end_date = Carbon::parse(Period::find($this->period_id)->end_date)->translatedFormat('d F, Y, H:i');
        $month = Carbon::parse(Period::find($this->period_id)->end_date)->translatedFormat('F, Y');

        return [
            'id' => $this->id,
            'resident_id' => Resident::find($this->resident_id)->fio,
            'period_start' => $begin_date,
            'period_end' => $end_date,
            'amount_rub' => $this->amount_rub,
            'month' => $month
        ];
    }
}
