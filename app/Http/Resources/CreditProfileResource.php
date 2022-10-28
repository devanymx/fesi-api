<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditProfileResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $warning = "Theres no warning to show";

        if ($this->cutoff_date < now()) {
            $warning = 'Your credit has been cut off';
        }
        if ($this->due_date < now()) {
            $warning = 'This credit is overdue';
        }
        if ($this->due_date->diffInDays(now()) < 5) {
            $warning = 'This credit is about to expire';
        }
        if ($this->due_date->diffInDays(now()) < 2) {
            $warning = 'This credit about to expire';
        }
        if ($this->due_date->diffInDays(now()) < 1) {
            $warning = 'This credit is expired';
        }

        return [
            'id' => $this->id,
            'client_name' => $this->client->name . ' ' . $this->client->last_name,
            'credit_limit' => $this->limit,
            'current_balance' => $this->balance,
            'current_capacity' => $this->limit - $this->balance,
            'warning' => $warning,
            'cutoff_date' => $this->cutoff_date->format('d/m/Y'),
            'due_date' => $this->due_date->format('d/m/Y'),
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
