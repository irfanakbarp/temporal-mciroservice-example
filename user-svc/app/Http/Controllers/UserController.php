<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Temporal\Workflows\UserWorkflowImpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ];

        try {
            $serviceClient = ServiceClient::create('localhost:7233');
            $client = WorkflowClient::create($serviceClient);
            $workflow = $client->newWorkflowStub(
                UserWorkflowImpl::class,
                WorkflowOptions::new()->withTaskQueue('user-task-queue')
            );

            $run = $client->start($workflow, $userData);

            return response()->json([
                'message' => 'User creation workflow started',
                'workflow_id' => $run->getExecution()->getID(),
                'run_id' => $run->getExecution()->getRunID(),
            ], 202);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to start workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
