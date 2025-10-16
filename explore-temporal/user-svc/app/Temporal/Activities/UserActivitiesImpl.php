<?php

namespace App\Temporal\Activities;

use GuzzleHttp\Client;

class UserActivitiesImpl implements UserActivitiesInterface
{
    public function createUser(array $userData): array
    {
        $user = \App\Models\User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'],
        ]);
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function createContact(array $userData): bool
    {
        $client = new Client();
        $response = $client->post('http://172.69.26.180:3000/api/v1/contact-svc/contacts', [
            'json' => [
                'name' => $userData['name'],
                'email' => $userData['email'],
            ],
        ]);

        return $response->getStatusCode() === 201;
    }

    public function createLog(string $action, string $referenceId, string $referenceName): bool
    {
        $client = new Client();
        $response = $client->post('http://172.69.193.199:3002/api/logs/', [
            'json' => [
                'action' => $action,
                'reference_id' => $referenceId,
                'reference_name' => $referenceName,
            ],
        ]);
        return $response->getStatusCode() === 201;
    }
}
