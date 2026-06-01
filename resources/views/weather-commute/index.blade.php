@extends('layouts.app')

@section('title', '天气通勤 - 研究生自我管理系统')

@section('styles')
<style>
    .weather-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 16px;
    }
    .weather-card .weather-temp { font-size: 3rem; font-weight: 700; }
    .weather-card .weather-desc { font-size: 1.1rem; opacity: 0.9; }
    .weather-alert {
        border-left: 4px solid;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        animation: fadeIn 0.3s ease;
    }
    .weather-alert-rain { border-left-color: #0d6efd; background: #e7f1ff; }
    .weather-alert-heat { border-left-color: #dc3545; background: #fff5f5; }
    .weather-alert-cold { border-left-color: #0dcaf0; background: #f0fbff; }
    .weather-alert-wind { border-left-color: #6c757d; background: #f8f9fa; }
    .commute-time-display {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    @keyframes fadeIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cloud-sun"></i> 天气通勤提醒</h4>
</div>

{{-- 天气提醒区 --}}
<div id="weatherAlerts" class="mb-4"></div>

{{-- 天气展示卡片 --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="weather-card p-4" id="weatherCard">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="weather-temp" id="weatherTemp">--°C</div>
                    <div class="weather-desc" id="weatherDesc">加载中...</div>
                    <div class="mt-2 small opacity-75">
                        <span id="weatherFeels"></span>
                        <span id="weatherHumidity" class="ms-3"></span>
                        <span id="weatherWind" class="ms-3"></span>
                    </div>
                </div>
                <div class="text-end">
                    <i class="bi bi-geo-alt-fill"></i> <span id="weatherCity">{{ $settings->city ?? '定位中...' }}</span>
                    <div class="small opacity-75 mt-1" id="weatherUpdateTime"></div>
                    <div class="small opacity-75" id="weatherCitySource"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-clock"></i> 今日通勤</h6>
                <div class="row text-center mt-3">
                    <div class="col-6">
                        <div class="text-muted small">去实验室</div>
                        <div class="commute-time-display">{{ $settings->morning_commute ?? '08:30' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">返回</div>
                        <div class="commute-time-display">{{ $settings->evening_commute ?? '21:00' }}</div>
                    </div>
                </div>
                @if($settings->notes)
                <div class="mt-3 p-2 bg-light rounded small">
                    <i class="bi bi-sticky"></i> {{ $settings->notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 通勤设置 --}}
<div class="card">
    <div class="card-header">
        <i class="bi bi-gear"></i> 通勤设置
    </div>
    <div class="card-body">
        <form action="{{ route('weather-commute.update') }}" method="POST">
            @csrf
            @submitToken

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">城市</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                           value="{{ old('city', $settings->city ?? 'Beijing') }}" placeholder="如：Beijing、Shanghai">
                    <small class="text-muted">使用英文城市名或拼音</small>
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">早上出发时间</label>
                    <input type="time" name="morning_commute" class="form-control @error('morning_commute') is-invalid @enderror"
                           value="{{ old('morning_commute', $settings->morning_commute ?? '08:30') }}">
                    @error('morning_commute')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">晚上返回时间</label>
                    <input type="time" name="evening_commute" class="form-control @error('evening_commute') is-invalid @enderror"
                           value="{{ old('evening_commute', $settings->evening_commute ?? '21:00') }}">
                    @error('evening_commute')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">提前提醒（分钟）</label>
                    <input type="number" name="reminder_minutes_before" class="form-control @error('reminder_minutes_before') is-invalid @enderror"
                           value="{{ old('reminder_minutes_before', $settings->reminder_minutes_before ?? 30) }}" min="5" max="120">
                    @error('reminder_minutes_before')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">高温阈值（°C）</label>
                    <input type="number" name="heat_threshold" class="form-control @error('heat_threshold') is-invalid @enderror"
                           value="{{ old('heat_threshold', $settings->heat_threshold ?? 35) }}" min="25" max="50">
                    @error('heat_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">低温阈值（°C）</label>
                    <input type="number" name="cold_threshold" class="form-control @error('cold_threshold') is-invalid @enderror"
                           value="{{ old('cold_threshold', $settings->cold_threshold ?? 0) }}" min="-20" max="10">
                    @error('cold_threshold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">通勤备注</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                          rows="2" placeholder="如：记得带实验室门禁卡、下雨天走地下通道...">{{ old('notes', $settings->notes ?? '') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex gap-4 flex-wrap">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="enabled" class="form-check-input" id="commuteEnabled"
                                   {{ old('enabled', $settings->enabled ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="commuteEnabled">启用通勤提醒</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="rain_alert" class="form-check-input" id="rainAlert"
                                   {{ old('rain_alert', $settings->rain_alert ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="rainAlert">雨天提醒</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="heat_alert" class="form-check-input" id="heatAlert"
                                   {{ old('heat_alert', $settings->heat_alert ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="heatAlert">高温提醒</label>
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="cold_alert" class="form-check-input" id="coldAlert"
                                   {{ old('cold_alert', $settings->cold_alert ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="coldAlert">低温提醒</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> 保存设置
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    var settings = {
        enabled: {{ ($settings->enabled ?? true) ? 'true' : 'false' }},
        notes: @json($settings->notes ?? ''),
        morningCommute: '{{ $settings->morning_commute ?? "08:30" }}',
        eveningCommute: '{{ $settings->evening_commute ?? "21:00" }}',
        reminderBefore: {{ $settings->reminder_minutes_before ?? 30 }},
    };

    var weatherCard = document.getElementById('weatherCard');
    var alertsContainer = document.getElementById('weatherAlerts');

    fetch('{{ route("weather-commute.weather") }}')
        .then(function(r) {
            if (!r.ok) return r.json().then(function(d) { throw new Error(d.error || '请求失败'); });
            return r.json();
        })
        .then(function(data) {
            document.getElementById('weatherTemp').textContent = data.temp_c + '°C';
            document.getElementById('weatherDesc').textContent = data.description;
            document.getElementById('weatherFeels').textContent = '体感 ' + data.feels_like + '°C';
            document.getElementById('weatherHumidity').textContent = '湿度 ' + data.humidity + '%';
            document.getElementById('weatherWind').textContent = '风速 ' + data.wind_speed + 'km/h';
            document.getElementById('weatherUpdateTime').textContent = '更新于 ' + new Date().toLocaleTimeString('zh-CN', {hour:'2-digit', minute:'2-digit'});

            if (data.city) {
                document.getElementById('weatherCity').textContent = data.city;
            }
            if (data.city_source === 'auto') {
                document.getElementById('weatherCitySource').textContent = 'IP自动定位';
            }

            if (!settings.enabled) return;

            var alerts = data.alerts || [];

            // Check if near commute time and append commute reminder
            var now = new Date();
            var currentMin = now.getHours() * 60 + now.getMinutes();
            var morningParts = settings.morningCommute.split(':');
            var eveningParts = settings.eveningCommute.split(':');
            var morningMin = parseInt(morningParts[0]) * 60 + parseInt(morningParts[1]);
            var eveningMin = parseInt(eveningParts[0]) * 60 + parseInt(eveningParts[1]);

            var nearMorning = currentMin >= (morningMin - settings.reminderBefore) && currentMin <= morningMin;
            var nearEvening = currentMin >= (eveningMin - settings.reminderBefore) && currentMin <= eveningMin;

            if (nearMorning || nearEvening) {
                var direction = nearMorning ? '去实验室' : '返回';
                var commuteMsg = '距离' + direction + '还有不到' + settings.reminderBefore + '分钟，请做好出行准备！';
                if (alerts.length > 0) {
                    commuteMsg += ' 注意当前天气状况。';
                }
                if (settings.notes) {
                    commuteMsg += ' 备注：' + settings.notes;
                }
                alerts.unshift({
                    type: 'commute',
                    icon: 'bi-alarm-fill',
                    title: '通勤提醒 — ' + direction,
                    message: commuteMsg
                });
            }

            alerts.forEach(function(alert) {
                var div = document.createElement('div');
                var alertType = alert.type || 'rain';
                if (alertType === 'commute') alertType = alerts.length > 1 ? alerts[1].type : 'rain';
                div.className = 'weather-alert weather-alert-' + alertType;
                div.innerHTML = '<strong><i class="bi ' + alert.icon + '"></i> ' + alert.title + '</strong><br>' + alert.message;
                alertsContainer.appendChild(div);
            });
        })
        .catch(function(err) {
            document.getElementById('weatherTemp').textContent = '--';
            document.getElementById('weatherDesc').textContent = err.message || '天气数据加载失败';
            weatherCard.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
        });
})();
</script>
@endsection
