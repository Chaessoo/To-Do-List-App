<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class TaskController extends Controller
{
    //tampilkan semua task
    public function index()
{
    $users = User::with('tasks')->get();

    return response()->json([
        "messages" => "Data user beserta task-nya",
        "data" => $users
    ], 200);
}


    public function create(Request $request)
    {
        $validator = Validator::make($request->all() ,
        [
            "title" => "required|max:20",
            "description" => "required|max:255",
            "category_id" => "nullable|exists:categories,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "messages" => "Title or Description is Required",
                "error" => $validator->errors()
            ], 422);
        } else {
            $task = Tasks::create([
                "user_id" => Auth::id(),
                "category_id" => $request->category_id,
                "title" => $request->title,
                "description" => $request->description,
                "status" => TaskStatus::Pending, // default pakai Enum
            ]);
        }

        // dd(Auth::id(), Auth::user());


        return response()->json([
            "messages" => "Task Added Successfully",
            "data" => $task
        ], 200);
    }




    public function update(Request $request, $id) {
        $task = Tasks::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        if(!$task) {
            return response()->json([
                "messages" => "Task Not Found"
            ], 402);
        }


        $validator = Validator::make($request->all(), [
            "title" => "sometimes|required|max:20",
            "description" => "sometimes|required|max:255",
            "status" => "sometimes|required|in:pending,in_pending,completed",
            "category_id" => "nullable|exists:categories,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "messages" => "Validation Error",
                "error" => $validator->errors(),
            ], 404);
        }

        $task->update($request->only(["title","description","status","category_id"]));

        return response()->json([
            "messages" => "Task Updated Successfully"
        ], 200);
    }


    public function delete($id) {
        $data = Tasks::where('id', $id);
        if ($data) {
            $data->delete();
            return response()->json([
                "messages" => "Task Deleted Successfully"
            ], 200);
        } else {
            return response()->json([
                "messages" => "Task Not Found"
            ], 404);
        }
    }
}
