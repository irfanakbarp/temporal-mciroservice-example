<?php

namespace App\Temporal\Workflows;

use App\DTO\UserDTO;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface UserWorkflowInterface
{
    #[WorkflowMethod(name: "UserWorkflowImpl.run")]
    public function run(UserDTO $userData);
}
