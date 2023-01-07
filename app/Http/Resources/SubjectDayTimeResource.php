<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectDayTimeResource extends JsonResource
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
            'subjectId' => $this->subject_id,
            'day' => $this->day,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
