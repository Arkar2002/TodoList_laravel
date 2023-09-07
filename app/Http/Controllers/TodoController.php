<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::when(request('searchKey'), function ($todo) {
            $searchKey = request('searchKey');
            return $todo
                ->orWhere('title', 'like', "%$searchKey%")
                ->orWhere("description", 'like', "%$searchKey%");
        })
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return view('app', compact('todos'));
    }

    public function store(Request $request)
    {
        $this->validationCheck($request);

        $data = $this->requestData($request);

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

    public function destoryAll()
    {
        Todo::truncate();
        return redirect('/')->with("deletedAll", "All Deleted Successfully");
    }

    private function requestData($request)
    {
        return [
            'title' => $request->title,
            'description' => $request->description,
        ];
    }

    private function validationCheck($request)
    {
        $valdation = [
            'title' => 'required|unique:todos,title|min:3',
            'description' => 'nullable',
        ];

        $valdationMessage = [
            'title.unique' => "Title Name was taken"
        ];

        Validator::make($request->all(), $valdation, $valdationMessage)->validate();
    }
}
