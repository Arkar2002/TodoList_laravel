<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✍️</text></svg>">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Tailwind css -->
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-200 p-4">

    <div class="mx-auto py-8 px-6 bg-white rounded-xl lg:w-2/4">
        <h1 class="font-bold text-5xl text-center mb-8">Todo App</h1>

        @if (session('created'))
            <div class="bg-blue-100 border border-blue-500 text-blue-700 px-4 py-3 flex items-center justify-start"
                role="alert">
                <span
                    class="text-white rounded-full bg-indigo-500 uppercase px-2 py-1 text-xs font-bold mr-3">New</span>
                <p class="font-bold flex-1">{{ session('created') }}</p>
            </div>
        @elseif (session('updated'))
            <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path
                        d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                </svg>
                <p>{{ session('updated') }}</p>
            </div>
        @elseif (session('deleted'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block">{{ session('deleted') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <form class="flex flex-col space-y-4" action="/" method="POST">
                @csrf
                <input type="text" name="title" placeholder="Todo"
                    class="py-3 px-4 bg-gray-100 rounded-xl focus:outline-none focus:ring focus:ring-blue-400 focus:shadow-lg focus:-translate-y-0.5 transition-all">
                @if ($errors->any())
                    <small class="ms-2 font-bold text-sm text-rose-500 italic">Title is required.</small>
                @endif

                <textarea name="description" placeholder="Todo Description"
                    class="py-3 px-4 bg-gray-100 rounded-xl focus:outline-none focus:ring focus:ring-blue-400 focus:shadow-lg focus:-translate-y-0.5 transition-all overflow-auto resize-none"></textarea>

                <button
                    class="w-28 py-4 px-6 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all">Add</button>
            </form>
        </div>

        <hr>

        <div class="mt-2">
            @foreach ($todos as $todo)
                <div @class([
                    'py-4 flex items-center border-b border-grey-300 px-3',
                    $todo->isDone === 1 ? 'bg-green-200 text-stone-600' : '',
                ])>
                    <div class="flex-1 pe-8">
                        <h3 class="text-lg font-semibold">{{ $todo->title }}</h3>
                        <p class="text-gray-500">{{ $todo->description }}</p>
                        @if ($todo->isDone)
                            <small class="italic">Completed
                                {{ $todo->updated_at->diffForHumans() }}</small>
                        @else
                            <small>Created {{ $todo->created_at->diffForHumans() }}</small>
                        @endif
                    </div>

                    <div class="flex space-x-3">
                        @if (!$todo->isDone)
                            <form action="/" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $todo->id }}">
                                <button class="p-2 bg-green-500 text-white rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <form action="/{{ $todo->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="p-2 bg-red-500 text-white rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
