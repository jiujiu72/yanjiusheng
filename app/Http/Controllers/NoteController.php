<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $notes = $query->orderBy('pinned', 'desc')->orderBy('updated_at', 'desc')->get();
        $categories = Note::distinct()->whereNotNull('category')->pluck('category');

        return view('notes.index', compact('notes', 'categories'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $validated['pinned'] = $request->has('pinned');
        Note::create($validated);
        return redirect()->route('notes.index')->with('success', '笔记已创建');
    }

    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $validated['pinned'] = $request->has('pinned');
        $note->update($validated);
        return redirect()->route('notes.index')->with('success', '笔记已更新');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')->with('success', '笔记已删除');
    }

    private function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'pinned' => 'nullable',
        ];
    }

    private function messages()
    {
        return [
            'title.required' => '笔记标题不能为空',
            'title.max' => '标题不能超过255个字符',
            'content.required' => '笔记内容不能为空',
            'category.max' => '分类不能超过50个字符',
            'tags.max' => '标签不能超过255个字符',
        ];
    }
}
