<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
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
            'title' => $this->title,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'isbn' => $this->isbn,
            'stock' => $this->stock,
            'description' => $this->description,
            'status' => $this->status,
            'cover' => $this->cover,
            'coverUrl' => $this->coverUrl(),
            'book_category' => $this->book_category
        ];
    }
}
