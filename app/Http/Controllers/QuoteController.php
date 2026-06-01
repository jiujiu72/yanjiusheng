<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('favorited')) {
            $query->where('favorited', true);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $quotes = $query->orderBy('favorited', 'desc')->orderBy('created_at', 'desc')->get();
        $categories = Quote::distinct()->whereNotNull('category')->where('category', '!=', '')->pluck('category');

        return view('quotes.index', compact('quotes', 'categories'));
    }

    public function create()
    {
        return view('quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        Quote::create($validated);
        return redirect()->route('quotes.index')->with('success', '短句已收录');
    }

    public function show(Quote $quote)
    {
        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        return view('quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate($this->rules(), $this->messages());
        $quote->update($validated);
        return redirect()->route('quotes.index')->with('success', '短句已更新');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('quotes.index')->with('success', '短句已删除');
    }

    public function toggleFavorite(Quote $quote)
    {
        $quote->update(['favorited' => !$quote->favorited]);
        return redirect()->back()->with('success', $quote->favorited ? '已收藏' : '已取消收藏');
    }

    private function rules()
    {
        return [
            'content' => 'required|string',
            'source' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
        ];
    }

    private function messages()
    {
        return [
            'content.required' => '短句内容不能为空',
            'source.max' => '来源不能超过255个字符',
            'category.max' => '分类不能超过50个字符',
            'tags.max' => '标签不能超过255个字符',
        ];
    }
}
