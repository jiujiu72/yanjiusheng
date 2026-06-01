<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResearchProjectController;
use App\Http\Controllers\LiteratureController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\DrinkWaterController;
use App\Http\Controllers\CaffeineController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestroomController;
use App\Http\Controllers\WatchLogController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\PasswordMemoController;
use App\Http\Controllers\RestReminderController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\MeetingMinuteController;
use App\Http\Controllers\WeatherCommuteController;
use App\Http\Controllers\ItemBorrowingController;
use App\Http\Controllers\ConsumableApplicationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ResearchProjectController::class)->except(['show']);
Route::resource('literatures', LiteratureController::class);
Route::resource('todos', TodoController::class)->except(['show']);
Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
Route::resource('notes', NoteController::class);

Route::get('routines', [RoutineController::class, 'index'])->name('routines.index');
Route::post('routines', [RoutineController::class, 'store'])->name('routines.store');
Route::delete('routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');

Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');
Route::post('pomodoros', [PomodoroController::class, 'store'])->name('pomodoros.store');

// 饮水打卡
Route::get('drink-water', [DrinkWaterController::class, 'index'])->name('drink-water.index');
Route::post('drink-water', [DrinkWaterController::class, 'store'])->name('drink-water.store');
Route::delete('drink-water/{drinkWater}', [DrinkWaterController::class, 'destroy'])->name('drink-water.destroy');

// 咖啡茶饮
Route::get('caffeine', [CaffeineController::class, 'index'])->name('caffeine.index');
Route::post('caffeine', [CaffeineController::class, 'store'])->name('caffeine.store');
Route::delete('caffeine/{caffeine}', [CaffeineController::class, 'destroy'])->name('caffeine.destroy');

// 经费记账
Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::patch('expenses/{expense}/toggle', [ExpenseController::class, 'toggleReimbursed'])->name('expenses.toggle');
Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

// 通讯录
Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

// 周月复盘
Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// 如厕打卡
Route::get('restroom', [RestroomController::class, 'index'])->name('restroom.index');
Route::post('restroom', [RestroomController::class, 'store'])->name('restroom.store');
Route::delete('restroom/{restroom}', [RestroomController::class, 'destroy'])->name('restroom.destroy');

// 追剧追番打卡
Route::get('watch-logs', [WatchLogController::class, 'index'])->name('watch-logs.index');
Route::post('watch-logs', [WatchLogController::class, 'store'])->name('watch-logs.store');
Route::post('watch-logs/import', [WatchLogController::class, 'import'])->name('watch-logs.import');
Route::delete('watch-logs/{watchLog}', [WatchLogController::class, 'destroy'])->name('watch-logs.destroy');

// 成就勋章
Route::get('achievements', [AchievementController::class, 'index'])->name('achievements.index');
Route::post('achievements/check', [AchievementController::class, 'check'])->name('achievements.check');

// 密码备忘录
Route::get('passwords', [PasswordMemoController::class, 'index'])->name('passwords.index');
Route::post('passwords', [PasswordMemoController::class, 'store'])->name('passwords.store');
Route::put('passwords/{passwordMemo}', [PasswordMemoController::class, 'update'])->name('passwords.update');
Route::get('passwords/{passwordMemo}/reveal', [PasswordMemoController::class, 'reveal'])->name('passwords.reveal');
Route::delete('passwords/{passwordMemo}', [PasswordMemoController::class, 'destroy'])->name('passwords.destroy');

// 休息提醒设置
Route::get('rest-reminder/settings', [RestReminderController::class, 'settings'])->name('rest-reminder.settings');
Route::post('rest-reminder/settings', [RestReminderController::class, 'update'])->name('rest-reminder.update');

// 短句摘抄
Route::resource('quotes', QuoteController::class);
Route::patch('quotes/{quote}/favorite', [QuoteController::class, 'toggleFavorite'])->name('quotes.favorite');

// 会议纪要
Route::resource('meetings', MeetingMinuteController::class);

// 天气通勤
Route::get('weather-commute', [WeatherCommuteController::class, 'index'])->name('weather-commute.index');
Route::post('weather-commute', [WeatherCommuteController::class, 'update'])->name('weather-commute.update');
Route::get('weather-commute/weather-data', [WeatherCommuteController::class, 'weather'])->name('weather-commute.weather');

// 物品借用
Route::get('item-borrowings', [ItemBorrowingController::class, 'index'])->name('item-borrowings.index');
Route::post('item-borrowings', [ItemBorrowingController::class, 'store'])->name('item-borrowings.store');
Route::patch('item-borrowings/{itemBorrowing}/return', [ItemBorrowingController::class, 'markReturned'])->name('item-borrowings.return');
Route::delete('item-borrowings/{itemBorrowing}', [ItemBorrowingController::class, 'destroy'])->name('item-borrowings.destroy');

// 实验耗材申领
Route::get('consumable-applications', [ConsumableApplicationController::class, 'index'])->name('consumable-applications.index');
Route::post('consumable-applications', [ConsumableApplicationController::class, 'store'])->name('consumable-applications.store');
Route::patch('consumable-applications/{consumableApplication}/link-expense', [ConsumableApplicationController::class, 'linkExpense'])->name('consumable-applications.link-expense');
Route::delete('consumable-applications/{consumableApplication}', [ConsumableApplicationController::class, 'destroy'])->name('consumable-applications.destroy');
