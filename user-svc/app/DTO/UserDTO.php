<?php

namespace App\DTO;

class UserDTO
{
    public string $id;
    public string $name;
    public string $email;

    public function __construct(string $id, string $name, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Convert Eloquent User to DTO
     */
    public static function fromModel(\App\Models\User $user): self
    {
        return new self(
            (string) $user->id,
            $user->name,
            $user->email
        );
    }

    /**
     * Convert DTO to Array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
