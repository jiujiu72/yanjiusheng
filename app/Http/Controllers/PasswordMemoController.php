<?php

namespace App\Http\Controllers;

use App\Models\PasswordMemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PasswordMemoController extends Controller
{
    public function index()
    {
        $passwords = PasswordMemo::orderBy('category')->orderBy('site_name')->get();
        $categories = [
            'academic' => '学术平台',
            'social' => '社交媒体',
            'finance' => '金融支付',
            'email' => '邮箱通讯',
            'other' => '其他',
        ];

        return view('passwords.index', compact('passwords', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'url' => 'nullable|max:500',
            'username' => 'required|string|max:100',
            'password' => 'required|string|min:1|max:500',
            'category' => 'required|in:academic,social,finance,email,other',
            'notes' => 'nullable|string|max:500',
        ], [
            'site_name.required' => '请输入网站/应用名称',
            'username.required' => '请输入用户名/账号',
            'password.required' => '请输入密码',
            'category.required' => '请选择分类',
        ]);

        PasswordMemo::create([
            'site_name' => $validated['site_name'],
            'url' => $validated['url'],
            'username' => $validated['username'],
            'encrypted_password' => Crypt::encryptString($validated['password']),
            'category' => $validated['category'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('passwords.index')->with('success', '密码记录已安全保存！');
    }

    public function update(Request $request, PasswordMemo $passwordMemo)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'url' => 'nullable|max:500',
            'username' => 'required|string|max:100',
            'password' => 'nullable|string|min:1|max:500',
            'category' => 'required|in:academic,social,finance,email,other',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = [
            'site_name' => $validated['site_name'],
            'url' => $validated['url'],
            'username' => $validated['username'],
            'category' => $validated['category'],
            'notes' => $validated['notes'],
        ];

        if (!empty($validated['password'])) {
            $data['encrypted_password'] = Crypt::encryptString($validated['password']);
        }

        $passwordMemo->update($data);

        return redirect()->route('passwords.index')->with('success', '密码记录已更新！');
    }

    public function reveal(PasswordMemo $passwordMemo)
    {
        return response()->json([
            'password' => Crypt::decryptString($passwordMemo->encrypted_password),
        ]);
    }

    public function destroy(PasswordMemo $passwordMemo)
    {
        $passwordMemo->delete();
        return redirect()->route('passwords.index')->with('success', '密码记录已删除');
    }
}
