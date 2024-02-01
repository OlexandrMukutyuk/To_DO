<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskList;

class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $tasks = TaskList::where('user_id', $user_id)->get();

        return view('home', ['tasks' => $tasks]);

    }

    public function welcome(){
        return view('welcome');
    }

    public function createTask(){
        return view('createTask');
    }

    public function storeTask(Request $request){
        if (!Auth::check()) { return redirect()->route('home')->with('error', 'Користувач не авторизований!');}
        try {
            $request->validate([
                'task' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            TaskList::create([
                'user_id' => $user->id,
                'task' => $request->input('task'),
            ]);
            return redirect()->route('home')->with('success', 'Завдання створено успішно!');
        } catch (ValidationException $e) {
            return redirect()->route('home')->with('error', 'Неправильно заповнені данні!');
        } finally {
            return redirect()->route('home')->with('error', 'Помилка при додаванні у базу даних!');
        }
    }

    public function updateTask(Request $request,  $id){
        if (!Auth::check()) { return redirect()->route('home')->with('error', 'Користувач не авторизований!');}
        $task = TaskList::find($id);
        if (!$task) {return redirect()->route('home')->with('error', 'незнайдено такого запису у базі!');}
        try {
            $request->validate([
                'task' => 'required|string|max:255',
            ]);
            $task->update([
                'task' => $request->input('task'),
                'is_completed' => $request->input('completed'),
            ]);

            return redirect()->route('home')->with('success', 'Завдання створено Оновлено!');
        } catch (ValidationException $e) {
            return redirect()->route('home')->with('error', 'Неправильно заповнені данні!');
        } finally {
            return redirect()->route('home')->with('error', 'Помилка при додаванні у базу даних!');
        }
    }

}
