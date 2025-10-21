<?php

namespace App\Http\Resources\Backend\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $username
 * @property mixed $password
 * @property mixed $role
 * @property mixed $status
 */
class AuthResource extends JsonResource
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
            'username' => $this->username,
            'password' => $this->password,
            'role' => $this->role,
            'status' => $this->status
        ];
    }
}
