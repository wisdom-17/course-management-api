<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'name' => $this->name,
            'startDate' => date('d/m/Y', strtotime($this->start_date)),
            'endDate' => date('d/m/Y', strtotime($this->end_date)),
            'teachingDays' => $this->teaching_days,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
            'deletedAt' => date('d/m/Y', strtotime($this->start_date)),
        ];
    }
}
