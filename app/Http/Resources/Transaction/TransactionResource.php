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
            'borrow_date' => $this->borrow_date,
            'return_date' => $this->return_date,
            'penalty' => $this->penalty,
            'is_status' => $this->is_status(),
            'admin' => $this->admin,
            'user' => $this->user,
        ];
    }
}