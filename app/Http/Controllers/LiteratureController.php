<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use Illuminate\Http\Request;

class LiteratureController extends Controller
{
    public function index(Request $request)
    {
        $query = Literature::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('authors', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $literatures = $query->orderBy('created_at', 'desc')->get();
        return view('literatures.index', compact('literatures'));
    }

    public function create()
    {
        return view('literatures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        Literature::create($validated);
        return redirect()->route('literatures.index')->with('success', '文献已添加');
    }

    public function show(Literature $literature)
    {
        return view('literatures.show', compact('literature'));
    }

    public function edit(Literature $literature)
    {
        return view('literatures.edit', compact('literature'));
    }

    public function update(Request $request, Literature $literature)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $literature->update($validated);
        return redirect()->route('literatures.index')->with('success', '文献已更新');
    }

    public function destroy(Literature $literature)
    {
        $literature->delete();
        return redirect()->route('literatures.index')->with('success', '文献已删除');
    }

    private function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'authors' => 'nullable|string|max:500',
            'journal' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:2100',
            'doi' => 'nullable|string|max:255',
            'abstract' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:5000',
            'status' => 'required|in:unread,reading,finished',
            'rating' => 'required|integer|min:0|max:5',
            'tags' => 'nullable|string|max:255',
        ];
    }

    private function messages()
    {
        return [
            'title.required' => '论文标题不能为空',
            'title.max' => '论文标题不能超过255个字符',
            'authors.max' => '作者信息不能超过500个字符',
            'journal.max' => '期刊名称不能超过255个字符',
            'year.integer' => '年份必须为整数',
            'year.min' => '年份不能早于1900',
            'year.max' => '年份不能晚于2100',
            'doi.max' => 'DOI不能超过255个字符',
            'abstract.max' => '摘要不能超过2000个字符',
            'notes.max' => '笔记不能超过5000个字符',
            'status.required' => '请选择阅读状态',
            'status.in' => '阅读状态无效',
            'rating.required' => '请填写评分',
            'rating.min' => '评分最低为0',
            'rating.max' => '评分最高为5',
            'tags.max' => '标签不能超过255个字符',
        ];
    }
}
