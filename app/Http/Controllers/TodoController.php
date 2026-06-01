<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = Todo::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('show_completed') && !$request->show_completed) {
            $query->where('completed', false);
        }

        $todos = $query->orderBy('completed')->orderBy('priority', 'desc')->orderBy('due_date')->get();
        $categories = Todo::distinct()->whereNotNull('category')->pluck('category');

        return view('todos.index', compact('todos', 'categories'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        Todo::create($validated);
        return redirect()->route('todos.index')->with('success', '待办已创建');
    }

    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $todo->update($validated);
        return redirect()->route('todos.index')->with('success', '待办已更新');
    }

    public function toggle(Todo $todo)
    {
        $todo->update(['completed' => !$todo->completed]);

        if ($todo->completed) {
            app(AchievementService::class)->checkAll();
        }

        return redirect()->route('todos.index')->with('success', $todo->completed ? '已完成' : '已取消完成');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', '待办已删除');
    }

    private function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'category' => 'nullable|string|max:50',
        ];
    }

    private function messages()
    {
        return [
            'title.required' => '待办事项不能为空',
            'title.max' => '待办事项不能超过255个字符',
            'description.max' => '描述不能超过1000个字符',
            'priority.required' => '请选择优先级',
            'priority.in' => '优先级选项无效',
            'due_date.date' => '截止日期格式无效',
            'category.max' => '分类不能超过50个字符',
        ];
    }
}
