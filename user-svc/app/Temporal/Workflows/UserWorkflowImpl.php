<?php

namespace App\Temporal\Workflows;

use App\DTO\UserDTO;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;
use App\Temporal\Activities\UserActivitiesInterface;

class UserWorkflowImpl implements UserWorkflowInterface
{
    private UserActivitiesInterface $activities;

//    public function __construct()
//    {
//        $this->activities = Workflow::newActivityStub(
//            UserActivitiesInterface::class,
//            ActivityOptions::new()
//                ->withStartToCloseTimeout(60)
//                ->withRetryOptions(
//                    RetryOptions::new()
//                        ->withMaximumAttempts(3)
//                        ->withInitialInterval(1)
//                        ->withBackoffCoefficient(2.0)
//                )
//        );
//    }

//    public function run(array $userData)
//    {
//        $user = yield $this->activities->createUser($userData);
//
//        yield $this->activities->createContact($user);
//
//        yield $this->activities->createLog("User {$user['email']} created successfully");
//
//        return $user;
//    }

    public function run(UserDTO $userData)
    {
        $activities = Workflow::newActivityStub(
            UserActivitiesInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(60)
                ->withRetryOptions(
                    RetryOptions::new()
                    ->withMaximumAttempts(5)
                    ->withInitialInterval(\DateInterval::createFromDateString('5 seconds'))
                    ->withBackoffCoefficient(2.0)
                    ->withMaximumInterval(\DateInterval::createFromDateString('180 seconds'))
                )
        );

        // $user = yield $activities->createUser($userData);
        yield $activities->createLog(
            "created",
            $userData->id,
            "users"
        );
        yield $activities->createContact($userData);

        return $userData;
    }
}
