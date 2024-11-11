<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'gender'=>$this->gender,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'created_at'=>date_format($this->created_at,"Y/m/d H:i:s")
        ];
    }
}
