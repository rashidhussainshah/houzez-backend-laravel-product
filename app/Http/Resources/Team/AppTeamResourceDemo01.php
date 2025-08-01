<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppTeamResourceDemo01 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'designation' => $this->designation,
            'image' => $this->image,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
