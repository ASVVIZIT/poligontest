<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'orderer_id' => $this->orderer_id,
            'executor_id' => $this->executor_id,
            'status' => $this->status,
            'title' => $this->title,
            'note' => $this->note,
            'created_at' => $this->created_at ?? '',
            'updated_at' => $this->updated_at ?? '',
            'deleted_at' => $this->deleted_at ?? '',
            'content' => $this->content,
            'content_short' => $this->content_short,
            'sum' => $this->sum,
        ];
    }
}
