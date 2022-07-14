<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'return_date' => $this->return_date,
            'penalty' => $this->penalty,
            'is_status' => $this->is_status(),
            'status' => $this->status,
            'admin' => $this->admin,
            'user' => $this->user,
            'detail' => $this->transaction_detail,
        ];
    }
}
