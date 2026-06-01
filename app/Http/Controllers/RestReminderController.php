<?php

namespace App\Http\Controllers;

use App\Models\RestReminderSetting;
use Illuminate\Http\Request;

class RestReminderController extends Controller
{
    public function settings()
    {
        $settings = RestReminderSetting::first();
        if (!$settings) {
            $settings = RestReminderSetting::create([
                'first_reminder_minutes' => 45,
                'second_reminder_minutes' => 90,
                'snooze_minutes' => 10,
                'enabled' => true,
            ]);
        }

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_reminder_minutes' => 'required|integer|min:10|max:180',
            'second_reminder_minutes' => 'required|integer|min:20|max:300',
            'snooze_minutes' => 'required|integer|min:5|max:30',
            'enabled' => 'boolean',
        ], [
            'first_reminder_minutes.required' => '请设置首次提醒时间',
            'first_reminder_minutes.min' => '首次提醒不能少于10分钟',
            'second_reminder_minutes.min' => '二次提醒不能少于20分钟',
        ]);

        $settings = RestReminderSetting::first();
        if (!$settings) {
            $settings = new RestReminderSetting();
        }

        $settings->fill($validated);
        $settings->enabled = $request->has('enabled');
        $settings->save();

        return redirect()->back()->with('success', '休息提醒设置已更新！');
    }
}
