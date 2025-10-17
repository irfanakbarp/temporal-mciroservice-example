<?php

namespace App\Temporal\Activities;

use App\DTO\UserDTO;
use GuzzleHttp\Client;

class UserActivitiesImpl implements UserActivitiesInterface
{
    // public function createUser(array $userData): array
    // {
    //     $user = \App\Models\User::create([
    //         'name' => $userData['name'],
    //         'email' => $userData['email'],
    //         'password' => $userData['password'],
    //     ]);
    //     return [
    //         'id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //     ];
    // }

    public function createContact(UserDTO $userData): bool
    {
        $baseUri = env('CONTACT_SERVICE_URL', 'http://127.0.0.1:3001/api/v1/contact-svc');
        $client = new Client();
        $response = $client->post($baseUri.'/contacts/', [
            'json' => [
                'name' => $userData->name,
                'email' => $userData->email,
            ],
        ]);

        return $response->getStatusCode() === 201;
    }

    public function createLog(string $action, string $referenceId, string $referenceName): bool
    {
        $baseUri = env('LOG_SERVICE_URL', 'http://127.0.0.1:3002/api/v1/log-svc');
        $client = new Client();
        $response = $client->post($baseUri.'/logs/', [
            'json' => [
                'action' => $action,
                'reference_id' => $referenceId,
                'reference_name' => $referenceName,
            ],
        ]);
        return $response->getStatusCode() === 201;
    }
}
