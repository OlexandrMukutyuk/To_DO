<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\TaskList;
use App\Models\TokenList;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(['word' => 'перевірка Api']);
    }

    public function login(Request $request)
    {
        if (!$request->filled(['email', 'password'])) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }

        try {
            $request->validateWithBag('api', [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }

        $user = Auth::user();
        $tokenList = TokenList::where('user_id', $user->id)->first();

        if ($tokenList) {
            $tokenValue = $tokenList->token;
        } else {
            $randomBytes = random_bytes(6);
            $randomString = bin2hex($randomBytes);

            TokenList::create([
                'user_id' => $user->id,
                'token' => $randomString,
            ]);
            $tokenValue = $randomString;
        }
        return response()->json(['token' => $tokenValue,'id' => $user->id], 200);
    }

    public function getTask(Request $request)
    {
        if (!$request -> filled(['token', 'id'])) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }
        try {
            $request -> validateWithBag('api', [
                'id' => 'required',
                'token' => 'required|size:12',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        if (!TokenList::where('user_id', $request->id)->where('token', $request->token)->first()) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }
        $tasks = TaskList::where('user_id', $request->id)->get();
        return response()->json(['task' => $tasks], 200);
    }

    public function storeTask(Request $request)
    {
        if (!$request->filled(['token', 'id', 'task'])) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }

        try {
            $request->validateWithBag('api', [
                'id' => 'required',
                'token' => 'required|size:12',
                'task' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        if (!TokenList::where('user_id', $request->id)->where('token', $request->token)->first()) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }
        $task = TaskList::create([
            'user_id' => $request->id,
            'task' => $request->task,
        ]);
        return response()->json(['status' => 'succes','task' => $task], 200);
    }
    public function updateTask(Request $request)
    {
        if (!$request->filled(['token', 'id', 'task','is_completed','task_id'])) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }

        try {
            $request->validateWithBag('api', [
                'id' => 'required',
                'token' => 'required|size:12',
                'task_id' => 'required',
                'task' => 'required|string|max:255',
                'is_completed' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        if (!TokenList::where('user_id', $request->id)->where('token', $request->token)->first()) {
            return response()->json(['error' => 'Неправильні дані аутентифікації'], 401);
        }
        $task = TaskList::find($request->task_id);
        if ($task) {
            $task->update([
                'task' => $request->task,
                'is_completed' => $request->is_completed,
            ]);
            return response()->json(['status' => 'succes','task' => $task], 200);
        } else {
            return response()->json(['error' => 'Неправильні дані запису'], 401);
        }
    }
}
