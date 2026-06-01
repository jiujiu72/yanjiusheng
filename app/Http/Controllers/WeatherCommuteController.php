<?php

namespace App\Http\Controllers;

use App\Models\CommuteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherCommuteController extends Controller
{
    private const WEATHER_DESCRIPTIONS = [
        113 => '晴', 116 => '多云', 119 => '阴天', 122 => '阴',
        143 => '雾', 176 => '局部小雨', 179 => '局部小雪', 182 => '局部冻雨',
        185 => '局部冻雾', 200 => '局部雷阵雨', 227 => '吹雪', 230 => '暴风雪',
        248 => '大雾', 260 => '冻雾', 263 => '毛毛雨', 266 => '小雨',
        281 => '冻毛毛雨', 284 => '冻雨', 293 => '局部小雨', 296 => '小雨',
        299 => '中雨', 302 => '中到大雨', 305 => '大雨', 308 => '暴雨',
        311 => '冻雨', 314 => '中等冻雨', 317 => '大冻雨', 320 => '小雪',
        323 => '局部小雪', 326 => '小雪', 329 => '中雪', 332 => '中到大雪',
        335 => '大雪', 338 => '暴雪', 350 => '冰粒', 353 => '阵雨',
        356 => '中到大阵雨', 359 => '大暴雨', 362 => '小冻雨', 365 => '中冻雨',
        368 => '局部小雪', 371 => '中到大雪', 374 => '小冰粒', 377 => '中冰粒',
        386 => '局部雷阵雨', 389 => '雷暴雨', 392 => '局部雷阵雪', 395 => '大雷阵雪',
    ];

    public function index()
    {
        $settings = CommuteSetting::first() ?? new CommuteSetting();
        return view('weather-commute.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'morning_commute' => 'required|date_format:H:i',
            'evening_commute' => 'required|date_format:H:i',
            'reminder_minutes_before' => 'required|integer|min:5|max:120',
            'heat_threshold' => 'required|integer|min:25|max:50',
            'cold_threshold' => 'required|integer|min:-20|max:10',
            'notes' => 'nullable|string|max:500',
        ], [
            'city.required' => '城市不能为空',
            'morning_commute.required' => '请设置早上出发时间',
            'evening_commute.required' => '请设置晚上返回时间',
            'reminder_minutes_before.required' => '请设置提前提醒时间',
        ]);

        $validated['rain_alert'] = $request->has('rain_alert');
        $validated['heat_alert'] = $request->has('heat_alert');
        $validated['cold_alert'] = $request->has('cold_alert');
        $validated['enabled'] = $request->has('enabled');

        $settings = CommuteSetting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            CommuteSetting::create($validated);
        }

        Cache::forget('weather_data_' . $validated['city']);

        return redirect()->route('weather-commute.index')->with('success', '通勤设置已保存');
    }

    public function weather(Request $request)
    {
        $settings = CommuteSetting::first();

        $detectedCity = $this->detectCityFromIp($request);
        $city = $detectedCity ?? ($settings->city ?? 'Beijing');
        $citySource = $detectedCity ? 'auto' : 'manual';

        $data = Cache::remember('weather_data_' . $city, 1800, function () use ($city) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(15)
                    ->withHeaders(['Accept-Language' => 'zh-CN'])
                    ->get("https://wttr.in/{$city}", [
                        'format' => 'j1',
                        'lang' => 'zh',
                    ]);

                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['current_condition'])) {
                        return $json;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('Weather API failed: ' . $e->getMessage());
            }
            return null;
        });

        if (!$data || !isset($data['current_condition'][0])) {
            Cache::forget('weather_data_' . $city);
            return response()->json(['error' => '无法获取天气数据，请检查城市名是否正确'], 500);
        }

        $current = $data['current_condition'][0];
        $weatherCode = (int)($current['weatherCode'] ?? 0);
        $tempC = (int)($current['temp_C'] ?? 0);
        $windSpeed = (int)($current['windspeedKmph'] ?? 0);
        $humidity = (int)($current['humidity'] ?? 0);
        $feelsLike = (int)($current['FeelsLikeC'] ?? $tempC);

        $description = $this->getWeatherDescription($current, $weatherCode);

        $isRain = $this->isRainy($weatherCode);
        $isHot = $tempC >= ($settings->heat_threshold ?? 35);
        $isCold = $tempC <= ($settings->cold_threshold ?? 0);
        $isWindy = $windSpeed >= 40;

        $alerts = $this->generateAlerts($isRain, $isHot, $isCold, $isWindy, $tempC, $windSpeed, $description, $settings);

        $result = [
            'temp_c' => $tempC,
            'feels_like' => $feelsLike,
            'humidity' => $humidity,
            'description' => $description,
            'wind_speed' => $windSpeed,
            'weather_code' => $weatherCode,
            'is_rain' => $isRain,
            'is_hot' => $isHot,
            'is_cold' => $isCold,
            'is_windy' => $isWindy,
            'alerts' => $alerts,
            'city' => $city,
            'city_source' => $citySource,
        ];

        return response()->json($result);
    }

    private function detectCityFromIp(Request $request)
    {
        $ip = $request->ip();

        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.')) {
            return null;
        }

        return Cache::remember('ip_city_' . $ip, 3600, function () use ($ip) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(5)
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,city',
                        'lang' => 'zh-CN',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? '') === 'success' && !empty($data['city'])) {
                        return $data['city'];
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('IP geolocation failed: ' . $e->getMessage());
            }
            return null;
        });
    }

    private function getWeatherDescription($current, $weatherCode)
    {
        if (!empty($current['lang_zh'][0]['value']) && $current['lang_zh'][0]['value'] !== $current['weatherDesc'][0]['value']) {
            return $current['lang_zh'][0]['value'];
        }

        if (isset(self::WEATHER_DESCRIPTIONS[$weatherCode])) {
            return self::WEATHER_DESCRIPTIONS[$weatherCode];
        }

        return $current['weatherDesc'][0]['value'] ?? '未知';
    }

    private function generateAlerts($isRain, $isHot, $isCold, $isWindy, $tempC, $windSpeed, $description, $settings)
    {
        $alerts = [];

        if ($isRain && ($settings->rain_alert ?? true)) {
            $alerts[] = [
                'type' => 'rain',
                'icon' => 'bi-cloud-rain-fill',
                'title' => '雨天出行提醒',
                'message' => "当前天气「{$description}」，出门请携带雨伞！路面湿滑注意安全。"
                    . ($settings->notes ? " 备注：{$settings->notes}" : ''),
            ];
        }

        if ($isHot && ($settings->heat_alert ?? true)) {
            $alerts[] = [
                'type' => 'heat',
                'icon' => 'bi-thermometer-sun',
                'title' => '高温预警',
                'message' => "当前气温 {$tempC}°C，请注意防暑降温！建议携带饮用水，避免长时间户外暴晒。",
            ];
        }

        if ($isCold && ($settings->cold_alert ?? true)) {
            $alerts[] = [
                'type' => 'cold',
                'icon' => 'bi-thermometer-snow',
                'title' => '低温提醒',
                'message' => "当前气温 {$tempC}°C，请注意保暖！建议添加外套、围巾等防寒衣物。",
            ];
        }

        if ($isWindy) {
            $windLevel = $windSpeed >= 60 ? '强风' : '大风';
            $alerts[] = [
                'type' => 'wind',
                'icon' => 'bi-wind',
                'title' => "{$windLevel}提醒",
                'message' => "当前风速 {$windSpeed}km/h，外出请注意防风，骑车通勤请小心安全。",
            ];
        }

        return $alerts;
    }

    private function isRainy($code)
    {
        $rainCodes = [
            176, 185, 200, 263, 266, 281, 284, 293, 296, 299, 302, 305, 308,
            311, 314, 317, 320, 350, 353, 356, 359, 362, 365, 386, 389, 392, 395,
        ];
        return in_array((int)$code, $rainCodes);
    }
}
