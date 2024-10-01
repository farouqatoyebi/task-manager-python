<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManageTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskManagementController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $tasks = null;

        try {
            // Fetch tasks via API
            $tasks = $this->sendRequest('GET', env('API_URL').'/tasks');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        return view('index', compact('tasks'));
    }

    public function store(ManageTaskRequest $request)
    {
        try {
            $create_task = $this->sendRequest('POST', env('API_URL').'/tasks', [
                'title' => $request->task_name,
                'description' => $request->task_desc,
                'status' => $request->task_status ? 'completed' : 'pending',
            ]);

            if ($create_task->success) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => $create_task->message
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => 'We could not complete your request at this time. Please try again!'
        ], 401);
    }

    public function update(ManageTaskRequest $request, int $task_id)
    {
        try {
            $update_task = $this->sendRequest('PUT', env('API_URL').'/tasks/'.$task_id, [
                'title' => $request->task_name,
                'description' => $request->task_desc,
                'status' => $request->task_status ? 'completed' : 'pending',
            ]);

            if ($update_task->success) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => $update_task->message
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => 'We could not mark the requested task as completed at this time. Please try again!'
        ], 401);
    }

    public function markAsCompleted(Request $request, int $task_id)
    {
        try {
            $update_task = $this->sendRequest('PUT', env('API_URL').'/tasks/'.$task_id, [
                'status' => 'completed'
            ]);

            if ($update_task->success) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => $update_task->message
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => 'We could not mark the requested task as completed at this time. Please try again!'
        ], 401);
    }

    public function destroy(Request $request, $task_id)
    {
        try {
            $delete_task = $this->sendRequest('DELETE', env('API_URL').'/tasks/'.$task_id);

            if ($delete_task->success) {
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => $delete_task->message
                ], 200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }

        return response()->json([
            'success' => false,
            'code' => 401,
            'message' => 'We could not delete the requested task at this time. Please try again!'
        ], 401);
    }

    private function sendRequest($method, $url, $data = [])
    {
        require_once(base_path().'/vendor/autoload.php');
        $client = new \GuzzleHttp\Client();

        $options_array = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ];

        if (count($data)) {
            $options_array['body'] = json_encode($data);
        }

        $api_request = $client->request($method, $url, $options_array);

        return $this->convertToObject($api_request->getBody()->getContents());
    }


    private function convertToObject($data)
    {
        if ($data) {
            if (!is_object($data)) {
                if (is_string($data)) {
                    $data = json_decode($data);
                }

                if (is_array($data)) {
                    $data = (object) $data;
                }
            }
        }

        return $data;
    }
}
