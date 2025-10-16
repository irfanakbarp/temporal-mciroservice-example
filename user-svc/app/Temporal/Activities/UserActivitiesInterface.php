<?php

namespace App\Temporal\Activities;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: "User.")]
interface UserActivitiesInterface
{
    #[ActivityMethod(name: "createUser")]
    public function createUser(array $userData): array;

    #[ActivityMethod(name: "createContact")]
    public function createContact(array $userData): bool;

    #[ActivityMethod(name: "createLog")]
    public function createLog(string $action, string $referenceId, string $referenceName): bool;
}
