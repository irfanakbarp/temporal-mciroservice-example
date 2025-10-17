<?php

namespace App\Temporal\Activities;

use App\DTO\UserDTO;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: "User.")]
interface UserActivitiesInterface
{
    // #[ActivityMethod(name: "createUser")]
    // public function createUser(array $userData): array;

    #[ActivityMethod(name: "createContact")]
    public function createContact(UserDTO $userData): bool;

    #[ActivityMethod(name: "createLog")]
    public function createLog(string $action, string $referenceId, string $referenceName): bool;
}
