<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CustomerPostRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class AdminTasksController extends Controller
{


    public function index()
    {
        $data = array();
        $db = DB::table('tasks')->join('task_statuses', 'tasks.id', '=', 'task_statuses.task_id')
            ->join('locations', 'locations.id', '=', 'tasks.location_id')->orderBy('tasks.created_at', 'DESC');
        $statuses = ['requested', 'agent_assigned', 'picked', 'on_way', 'completed'];
        foreach ($statuses as $status) {
            $data[$status] = $db->where("task_statuses.$status", '!=', null)->paginate(10);
        }
        return $this->respondWithSuccess($data);
    }

    public function completed()
    {
        $db = Task::join('task_statuses', 'tasks.id', '=', 'task_statuses.task_id')
            ->where("task_statuses.completed", '!=', null)
            ->orderBy('tasks.created_at', 'DESC')
            ->with(['customer'])
            ->paginate(10);
        return $this->respondWithSuccess(['data' => $db]);
    }

    public function assigned()
    {
        $db = Task::join('task_statuses', 'tasks.id', '=', 'task_statuses.task_id')
            ->where("task_statuses.agent_assigned", '!=', null)
            ->orderBy('tasks.created_at', 'DESC')
            ->with(['customer'])
            ->paginate(10);
        return $this->respondWithSuccess(['data' => $db]);
    }

    public function unassigned()
    {
        $db = Task::join('task_statuses', 'tasks.id', '=', 'task_statuses.task_id')
            ->where("task_statuses.requested", '!=', null)
            ->orderBy('tasks.created_at', 'DESC')
            ->with(['customer'])
            ->paginate(10);
        return $this->respondWithSuccess(['data' => $db]);
    }
    public function all()
    {
        $db = Task::join('task_statuses', 'tasks.id', '=', 'task_statuses.task_id')
            ->orderBy('tasks.created_at', 'DESC')
            ->with(['customer'])
            ->paginate(10);
        return $this->respondWithSuccess(['data' => $db]);
    }

    public function show($id)
    {
        $tasks = Task::findOrFail($id);
        return $this->respondWithSuccess(['data' => $tasks]);
    }

    public function showTasks($id)
    {
        $tasks = Customer::findOrFail($id);
        $tasks = $tasks->tasks()->paginate(10);
        return $this->respondWithSuccess(['data' => $tasks]);
    }

    public function updateStatus($id)
    {
        $tasks = Task::findOrFail($id);
        $statuses = ['requested', 'agent_assigned', 'picked', 'on_way', 'completed'];
        $status_readable = 'Requested';
        $new_status = null;
        $task_status = $tasks->status;
        try {
            foreach ($statuses as $_index => $status) {
                if ($task_status->{$status} === null) {
                    $new_status = $status;
                    $status_readable = ['Request Sent', 'Agent Assigned', 'Picked Up', 'On the Way', 'Completed'][$_index];
                    break;
                }
            }
        } catch (\Exception $e) {
            TaskStatus::create(['requested' => now(), 'status_readable' => 'Request Sent', 'task_id' => $tasks->id]);
        }
        if ($new_status) {
            $tasks->status->{$new_status} = now();
            $tasks->status->status_readable = $status_readable;
            $tasks->status->save();
        }
        $res = $tasks;//->with('status')->first();
        return $this->respondWithSuccess(['data' => $res]);
    }

    public function store(CustomerPostRequest $request)
    {
        $image = moveFile($request->image);
        $data = $request->all();
        $data['image'] = $image;
        $tasks = Customer::create($data);
        return $this->respondWithSuccess(['data' => $tasks]);
    }

    public function update(CustomerPostRequest $request, $id)
    {
        $tasks = Customer::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = moveFile($request->image);
            $data['image'] = $image;
        }
        $tasks->update($data);
        $tasks->save();
        return $this->respondWithSuccess(['data' => $tasks]);
    }

    public function destroy($request, Task $tasks)
    {
        $tasks = $tasks->findOrFail($request);
        $tasks->delete();
        return $this->respondWithSuccess(['data' => $tasks, 'message' => 'Deleted successfully']);
    }

}
