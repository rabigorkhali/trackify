<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\System\ConfigController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\ProfileController;
use App\Http\Controllers\System\PageController;
use App\Http\Controllers\System\PostCategoryController;
use App\Http\Controllers\System\PostController;
use App\Http\Controllers\System\TestimonialController;
use App\Http\Controllers\System\TeamController;
use App\Http\Controllers\System\ContactUsController;
use App\Http\Controllers\System\EventController;
use App\Http\Controllers\System\MenuController;
use App\Http\Controllers\System\ResourceMonitorController;
use App\Http\Controllers\System\RedirectionController;
use App\Http\Controllers\System\ActivityController;
use App\Http\Controllers\System\PartnerController;
use App\Http\Controllers\System\FileManagerController;
use App\Http\Controllers\Public\IndexController;
use App\Http\Controllers\System\SliderController;
use App\Http\Controllers\System\NewsletterSubscriptionController;
use App\Http\Controllers\System\EmailController;
use App\Http\Controllers\System\SmsController;
use App\Http\Controllers\System\ProjectController;
use App\Http\Controllers\System\TicketController;
use App\Http\Controllers\System\TicketStatusController;
use App\Http\Controllers\System\TicketCommentController;
use App\Http\Controllers\System\TicketLabelController;
use App\Http\Controllers\System\TicketChecklistController;
use App\Http\Controllers\System\TicketWatcherController;
use App\Http\Controllers\System\TimeLogController;
use App\Http\Controllers\System\NotificationController;

//Route::get('/', function () {
//    return view('index');
//});


Auth::routes();
Route::get('admin', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);
Route::post('admin', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
/*FRONTEND ROUTE*/
if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
    $pages = \App\Models\Page::where('status', 1)->get();
    foreach ($pages as $page) {
        Route::get('/' . $page->slug, [IndexController::class, 'pageDirectUrl'])->name('page' . $page->slug);
    }
}
Route::get('/', [IndexController::class, 'index'])->name('index');
/*FRONTEND ROUTE*/
Route::prefix(getSystemPrefix())->middleware(['auth', 'permission.routes', 'log'])->group(function () {
    Route::get('/generate-sitemap', [HomeController::class, 'generateSitemap'])->name('generateSitemap');
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/admin', [HomeController::class, 'index'])->name('home.index');
    Route::get('/login', [HomeController::class, 'index'])->name('home.index');
    Route::get('/admin', [HomeController::class, 'index'])->name('home.index');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/backup-database', [HomeController::class, 'backupDatabase'])->name('backup.database');
    Route::get('/backup-project', [HomeController::class, 'backupProject'])->name('backup.project');
    Route::resource('/profile', ProfileController::class)->except(['show']);
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change.password');
    Route::put('/change-password', [ProfileController::class, 'changePasswordUpdate'])->name('change.password.update');
    Route::resource('/configs', ConfigController::class)->except(['show']); //configs.index, configs.create, configs.store, configs.show, configs.edit, configs.update, configs.destroy
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class, ['except' => ['show']]);
    Route::resource('/pages', PageController::class, ['except' => ['show']]);
    Route::resource('/post-categories', PostCategoryController::class, ['except' => ['show']]);
    Route::resource('/posts', PostController::class, ['except' => ['show']]);
    Route::resource('/testimonials', TestimonialController::class);
    Route::resource('/teams', controller: TeamController::class);
    Route::get('/file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
    Route::resource('/contact-us', ContactUsController::class);
    Route::resource('/events', EventController::class, ['except' => ['show']]);
    Route::get('/events/delete-gallery/{id}', [EventController::class, 'deleteGallery'])->name('deleteGallery');
    Route::resource('/menus', MenuController::class, ['except' => ['show']]);
    Route::get('monitor', [ResourceMonitorController::class, 'index'])->name('monitor.index');
    Route::resource('/redirections', RedirectionController::class);
    Route::resource('/activities', ActivityController::class);
    Route::resource('/partners', PartnerController::class);
    Route::post('/ckeditor-upload', [FileManagerController::class, 'ckeditorUpload'])->name('ckeditor.upload')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::resource('/sliders', SliderController::class);
    Route::resource('/newsletter-subscriptions', NewsletterSubscriptionController::class);
    Route::resource('/emails', EmailController::class);
    Route::resource('/sms', SmsController::class);
    // Project Management Routes
    Route::resource('/projects', ProjectController::class);
    
    // Ticket Status Management
    Route::resource('/ticket-statuses', TicketStatusController::class);
    Route::post('/ticket-statuses-update-order', [TicketStatusController::class, 'updateOrder'])->name('ticket-statuses.update-order');
    
    // Global Kanban Board
    Route::get('/kanban', [TicketController::class, 'globalKanban'])->name('kanban.index');
    
    // Nested Tickets under Projects
    Route::prefix('projects/{project}')->group(function () {
        Route::get('/tickets-kanban', [TicketController::class, 'kanban'])->name('tickets.kanban');
        Route::get('/tickets/{ticket}/show', [TicketController::class, 'show'])->name('tickets.show');
        Route::resource('/tickets', TicketController::class);
    });
    
    Route::post('/tickets-update-status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('/tickets-update-assignee', [TicketController::class, 'updateAssignee'])->name('tickets.update-assignee');
    Route::post('/ticket-comments', [TicketCommentController::class, 'store'])->name('ticket-comments.store');
    Route::delete('/ticket-comments/{ticketComment}', [TicketCommentController::class, 'destroy'])->name('ticket-comments.destroy');
    
    // Label Management
    Route::resource('/ticket-labels', TicketLabelController::class);
    Route::post('/tickets/{ticket}/labels', [TicketController::class, 'attachLabel'])->name('tickets.attach-label');
    Route::delete('/tickets/{ticket}/labels/{label}', [TicketController::class, 'detachLabel'])->name('tickets.detach-label');
    
    // Checklist Management
    Route::post('/ticket-checklists', [TicketChecklistController::class, 'store'])->name('ticket-checklists.store');
    Route::put('/ticket-checklists/{checklist}', [TicketChecklistController::class, 'update'])->name('ticket-checklists.update');
    Route::delete('/ticket-checklists/{checklist}', [TicketChecklistController::class, 'destroy'])->name('ticket-checklists.destroy');
    
    // Watcher Management
    Route::post('/ticket-watchers', [TicketWatcherController::class, 'store'])->name('ticket-watchers.store');
    Route::delete('/ticket-watchers', [TicketWatcherController::class, 'destroy'])->name('ticket-watchers.destroy');
    
    // Time Log Management
    Route::post('/time-logs', [TimeLogController::class, 'store'])->name('time-logs.store');
    Route::put('/time-logs/{timeLog}', [TimeLogController::class, 'update'])->name('time-logs.update');
    Route::delete('/time-logs/{timeLog}', [TimeLogController::class, 'destroy'])->name('time-logs.destroy');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
});
