<?php

namespace App\Temporal\Workflows;

use Illuminate\Support\Facades\Log;
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

    public function run(array $userData)
    {
        $activities = Workflow::newActivityStub(
            UserActivitiesInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(60)
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withMaximumAttempts(3)
                        ->withInitialInterval(1)
                        ->withBackoffCoefficient(2.0)
                )
        );

        $user = yield $activities->createUser($userData);
        Log::info($user);
        yield $activities->createContact($user);
        yield $activities->createLog(
            "created",
            $user['id'],
            "users"
        );

        return $user;
    }
}
