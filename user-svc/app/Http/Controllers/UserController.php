<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $userDTO = new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );

        try {
            $serviceClient = ServiceClient::create('localhost:7233');
            $client = WorkflowClient::create($serviceClient);
            $workflow = $client->newWorkflowStub(
                UserWorkflowImpl::class,
                WorkflowOptions::new()->withTaskQueue('user-task-queue')
            );

            $run = $client->start($workflow, $userDTO);

            return response()->json([
                'user' => $user,
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
