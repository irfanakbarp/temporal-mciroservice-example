<?php

namespace App\Temporal\Workflows;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface UserWorkflowInterface
{
    #[WorkflowMethod(name: "UserWorkflowImpl.run")]
    public function run(array $userData);
}
