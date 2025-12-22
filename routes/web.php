<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\EntryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Master\MasterTopController;
use App\Http\Controllers\Master\ShopController as MasterShopController;
use App\Http\Controllers\Master\LinkController as MasterLinkController;
use App\Http\Controllers\Master\QuestionController as MasterQuestionController;
use App\Http\Controllers\Master\CategoryController as MasterCategoryController;
use Illuminate\Support\Facades\Route;

// 求職者向けページ（サイトマップ ID: 1, 2）
Route::get('/', [TopController::class, 'index'])->name('top.index'); // ID: 1 - TOP
Route::get('/index', [TopController::class, 'index'])->name('top.index.alt'); // 別名ルート

// 会社情報・規約関連
Route::prefix('company')->name('company.')->group(function () {
    Route::get('/', [CompanyController::class, 'index'])->name('index');
    Route::get('/terms', [CompanyController::class, 'terms'])->name('terms');
    Route::get('/policy', [CompanyController::class, 'policy'])->name('policy');
});

// 新しいUI用のルート
Route::get('/record', [RecordController::class, 'welcome'])->name('record.welcome'); // ウェルカムページ
Route::get('/record/howto', [RecordController::class, 'howto'])->name('record.howto'); // やり方説明
Route::get('/record/interview-preview', [RecordController::class, 'interviewPreview'])->name('record.interview-preview'); // 面接プレビュー
Route::get('/record/interview', [RecordController::class, 'interview'])->name('record.interview'); // 面接開始
Route::get('/record/confirm', [RecordController::class, 'confirm'])->name('record.confirm'); // 確認画面
Route::get('/record/error', [RecordController::class, 'error'])->name('record.error'); // エラーページ

// 既存のAPI用ルート
Route::post('/record/upload', [RecordController::class, 'upload'])->name('record.upload'); // 面接動画アップロード
Route::post('/record/preview', [RecordController::class, 'preview'])->name('record.preview'); // 動画プレビュー
Route::post('/record/submit', [RecordController::class, 'submit'])->name('record.submit'); // 最終送信
Route::post('/record/process-subtitles', [RecordController::class, 'processSubtitles'])->name('record.process-subtitles'); // 字幕処理
Route::post('/record/retake', [RecordController::class, 'retake'])->name('record.retake'); // 録り直し
Route::get('/record/video/{filename}', [RecordController::class, 'serveVideo'])->name('record.video'); // 動画配信
Route::get('/record/complete', [RecordController::class, 'complete'])->name('record.complete'); // 面接完了ページ

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// 店舗向け管理画面（サイトマップ ID: 5〜11）
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // ID: 5 - ダッシュボード（店舗管理画面TOP）
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ID: 6 - 面接URL発行
    Route::get('/create_link', [LinkController::class, 'create'])->name('link.create');
    Route::post('/create_link', [LinkController::class, 'store'])->name('link.store');

        // ID: 7, 8 - お知らせ一覧・詳細
    Route::get('/notice', [NoticeController::class, 'index'])->name('notice.index');
    Route::get('/notice/{id}', [NoticeController::class, 'show'])->name('notice.show');

    // ID: 9, 10 - 応募者一覧・詳細
    Route::get('/entry', [EntryController::class, 'index'])->name('entry.index');
    Route::get('/entry/{id}', [EntryController::class, 'show'])->name('entry.show');
    Route::get('/entry/{id}/interview', [EntryController::class, 'interview'])->name('entry.interview');
    Route::post('/entry/{id}/reject', [EntryController::class, 'reject'])->name('entry.reject');
    Route::post('/entry/{id}/pass', [EntryController::class, 'pass'])->name('entry.pass');
    Route::post('/entry/{id}/resend', [EntryController::class, 'resend'])->name('entry.resend');
    Route::post('/entry/{id}/burn-subtitles', [EntryController::class, 'burnSubtitles'])->name('entry.burn_subtitles');

    // ID: 11 - 各種設定（プロフィール）
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::patch('/setting', [SettingController::class, 'update'])->name('setting.update');
});

// 管理者向け機能（サイトマップ ID: 12〜19）
Route::prefix('master')->name('master.')->middleware(['auth', 'master.role'])->group(function () {
    // ID: 12 - マスターTOP
    Route::get('/', [MasterTopController::class, 'index'])->name('dashboard');

    // ID: 13, 14 - 登録店舗（リソースコントローラー）
    Route::resource('shop', MasterShopController::class)->only(['index', 'show']);

    // ID: 15, 16 - 面接URL（リソースコントローラー）
    Route::resource('link', MasterLinkController::class)->only(['index', 'show']);

    // マスター質問管理
    Route::resource('question', MasterQuestionController::class);

    // マスターカテゴリー管理
    Route::resource('category', MasterCategoryController::class);

    // ID: 17, 18, 19 - お知らせ管理
    Route::get('/notice', [\App\Http\Controllers\Master\NoticeController::class, 'index'])->name('notice.index');
    Route::get('/notice/create', [\App\Http\Controllers\Master\NoticeController::class, 'create'])->name('notice.create');
    Route::post('/notice', [\App\Http\Controllers\Master\NoticeController::class, 'store'])->name('notice.store');
    Route::get('/notice/{id}/edit', [\App\Http\Controllers\Master\NoticeController::class, 'edit'])->name('notice.edit');
    Route::patch('/notice/{id}', [\App\Http\Controllers\Master\NoticeController::class, 'update'])->name('notice.update');
    Route::delete('/notice/{id}', [\App\Http\Controllers\Master\NoticeController::class, 'destroy'])->name('notice.destroy');
});
