<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::orderBy('id', "DESC")->get();
        return view('app', compact('todos'));
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'title' => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) return back()->withErrors($validator);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        Todo::create($data);

        return redirect('/')->with('created', "New Todo was created");
    }

    public function update()
    {
        Todo::find(request()->id)->update(['isDone' => 1, 'updated_at' => now()]);
        return redirect('/')->with('updated', 'Mark as completed Successfully');
    }

    public function destory(Todo $todo)
    {
        $todo->delete();
        return redirect('/')->with('deleted', 'Deleted Successfully');
    }
}
