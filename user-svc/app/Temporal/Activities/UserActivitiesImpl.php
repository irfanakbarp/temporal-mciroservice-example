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
        $baseUri = env('CONTACT_SERVICE_URL', 'http://localhost:3000/api/contacts/');
        $client = new Client();
        $response = $client->post($baseUri, [
            'json' => [
                'name' => $userData['name'],
                'email' => $userData['email'],
            ],
        ]);

        return $response->getStatusCode() === 201;
    }

    public function createLog(string $action, string $referenceId, string $referenceName): bool
    {
        $baseUri = env('LOG_SERVICE_URL', 'http://localhost:3002/api/logs/');
        $client = new Client();
        $response = $client->post($baseUri, [
            'json' => [
                'action' => $action,
                'reference_id' => $referenceId,
                'reference_name' => $referenceName,
            ],
        ]);
        return $response->getStatusCode() === 201;
    }
}
