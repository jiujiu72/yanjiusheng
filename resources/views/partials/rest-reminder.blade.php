{{-- 休息提醒模态框 --}}
<div class="modal fade" id="restReminderModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-body text-center p-5" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                <div style="font-size:3rem;" class="mb-3" id="restReminderEmoji">🧘</div>
                <h4 class="text-white mb-3">该休息一下啦！</h4>
                <p class="text-white-50 mb-2" id="restReminderMessage">久坐伤身！站起来活动一下吧</p>
                <p class="text-white-50 small mb-4">
                    你已经连续学习了 <strong class="text-white" id="restReminderElapsed">0</strong> 分钟
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light btn-sm px-4" id="restReminderSnooze">
                        <i class="bi bi-clock-history"></i> 稍后提醒
                    </button>
                    <button type="button" class="btn btn-warning btn-sm px-4" id="restReminderReset">
                        <i class="bi bi-emoji-smile"></i> 我去休息了
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 休息提醒设置模态框 --}}
<div class="modal fade" id="restReminderSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rest-reminder.update') }}" method="POST">
                @csrf
                @submitToken
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bell"></i> 休息提醒设置</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">首次提醒时间（分钟）</label>
                        <input type="number" name="first_reminder_minutes" class="form-control"
                               value="{{ $restReminderFirst ?? 45 }}" min="10" max="180">
                        <small class="text-muted">连续学习多少分钟后首次提醒（10-180分钟）</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">二次提醒时间（分钟）</label>
                        <input type="number" name="second_reminder_minutes" class="form-control"
                               value="{{ $restReminderSecond ?? 90 }}" min="20" max="300">
                        <small class="text-muted">连续学习多少分钟后强制提醒（20-300分钟）</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">稍后提醒间隔（分钟）</label>
                        <input type="number" name="snooze_minutes" class="form-control"
                               value="{{ $restReminderSnooze ?? 10 }}" min="5" max="30">
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="enabled" class="form-check-input" id="reminderEnabled"
                               {{ ($restReminderEnabled ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="reminderEnabled">启用休息提醒</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存设置</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 休息提醒计时器逻辑 --}}
<script>
(function() {
    var settings = {
        enabled: {{ ($restReminderEnabled ?? true) ? 'true' : 'false' }},
        first: {{ $restReminderFirst ?? 45 }} * 60 * 1000,
        second: {{ $restReminderSecond ?? 90 }} * 60 * 1000,
        snooze: {{ $restReminderSnooze ?? 10 }} * 60 * 1000,
    };

    if (!settings.enabled) return;

    var storageKey = 'restReminder_startTime';
    var startTime = localStorage.getItem(storageKey);
    if (!startTime) {
        startTime = Date.now();
        localStorage.setItem(storageKey, startTime);
    } else {
        startTime = parseInt(startTime);
    }

    var firstShown = false;
    var secondShown = false;
    var modal = null;

    var messages = [
        { emoji: '🧘', text: '久坐伤身！站起来活动一下吧' },
        { emoji: '☕', text: '你已经学习很久了，休息一下效率更高哦' },
        { emoji: '💧', text: '该喝杯水了！保持水分很重要' },
        { emoji: '🌿', text: '闭上眼睛休息20秒，看看远处的绿色' },
        { emoji: '💪', text: '做个深呼吸，放松肩膀和颈椎' },
        { emoji: '🚶', text: '起来走动走动，活动一下手腕' },
        { emoji: '🪟', text: '望望窗外，让眼睛放松一下' },
        { emoji: '❤️', text: '学习固然重要，健康更加重要' },
        { emoji: '🛤️', text: '休息是为了走更远的路' },
        { emoji: '⭐', text: '你今天已经很棒了，休息一下吧' },
        { emoji: '🎵', text: '听首歌放松一下，回来效率翻倍' },
        { emoji: '🌸', text: '劳逸结合才能事半功倍哦' },
    ];

    function showReminder() {
        var msg = messages[Math.floor(Math.random() * messages.length)];
        var elapsed = Math.round((Date.now() - startTime) / 60000);

        document.getElementById('restReminderEmoji').textContent = msg.emoji;
        document.getElementById('restReminderMessage').textContent = msg.text;
        document.getElementById('restReminderElapsed').textContent = elapsed;

        if (!modal) {
            modal = new bootstrap.Modal(document.getElementById('restReminderModal'));
        }
        modal.show();
    }

    setInterval(function() {
        var elapsed = Date.now() - startTime;
        if (!firstShown && elapsed >= settings.first) {
            firstShown = true;
            showReminder();
        }
        if (!secondShown && elapsed >= settings.second) {
            secondShown = true;
            showReminder();
        }
    }, 30000);

    document.getElementById('restReminderSnooze').addEventListener('click', function() {
        startTime = Date.now() - settings.first + settings.snooze;
        localStorage.setItem(storageKey, startTime);
        firstShown = false;
        secondShown = false;
        if (modal) modal.hide();
    });

    document.getElementById('restReminderReset').addEventListener('click', function() {
        startTime = Date.now();
        localStorage.setItem(storageKey, startTime);
        firstShown = false;
        secondShown = false;
        if (modal) modal.hide();
    });
})();
</script>
