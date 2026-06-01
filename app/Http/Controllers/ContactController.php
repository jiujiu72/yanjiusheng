<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $groupLabels = [
            'advisor' => '导师',
            'senior' => '师兄/师姐',
            'peer' => '同级同门',
            'junior' => '师弟/师妹',
            'other' => '其他',
        ];

        $contacts = Contact::orderBy('name')->get()->groupBy('group');

        $orderedContacts = collect();
        foreach (array_keys($groupLabels) as $key) {
            if (isset($contacts[$key])) {
                $orderedContacts[$key] = $contacts[$key];
            }
        }

        return view('contacts.index', compact('orderedContacts', 'groupLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'group' => 'required|in:advisor,senior,peer,junior,other',
            'phone' => ['nullable', 'string', 'regex:/^1[3-9]\d{9}$/'],
            'email' => 'nullable|email|max:100',
            'wechat' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_\-]+$/',
            'research_direction' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:200',
        ], [
            'name.required' => '姓名不能为空',
            'name.min' => '姓名至少需要2个字符',
            'name.max' => '姓名不能超过50个字符',
            'group.required' => '请选择分组',
            'group.in' => '分组类型无效',
            'phone.regex' => '手机号格式错误，请输入11位有效手机号（如13812345678）',
            'email.email' => '邮箱格式不正确',
            'email.max' => '邮箱不能超过100个字符',
            'wechat.max' => '微信号不能超过50个字符',
            'wechat.regex' => '微信号只能包含字母、数字、下划线和短横线',
            'research_direction.max' => '研究方向不能超过100个字符',
            'note.max' => '备注不能超过200个字符',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', '联系人已添加');
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'group' => 'required|in:advisor,senior,peer,junior,other',
            'phone' => ['nullable', 'string', 'regex:/^1[3-9]\d{9}$/'],
            'email' => 'nullable|email|max:100',
            'wechat' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_\-]+$/',
            'research_direction' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:200',
        ], [
            'name.required' => '姓名不能为空',
            'name.min' => '姓名至少需要2个字符',
            'name.max' => '姓名不能超过50个字符',
            'group.required' => '请选择分组',
            'group.in' => '分组类型无效',
            'phone.regex' => '手机号格式错误，请输入11位有效手机号（如13812345678）',
            'email.email' => '邮箱格式不正确',
            'email.max' => '邮箱不能超过100个字符',
            'wechat.max' => '微信号不能超过50个字符',
            'wechat.regex' => '微信号只能包含字母、数字、下划线和短横线',
            'research_direction.max' => '研究方向不能超过100个字符',
            'note.max' => '备注不能超过200个字符',
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', '联系人已更新');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', '联系人已删除');
    }
}
