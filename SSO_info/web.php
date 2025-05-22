<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\TeamMappingController;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccessControlList;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\BugtrackingController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BurndownChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NFRController;
use App\Http\Controllers\TVTController;
use App\Http\Controllers\CIGController;
use App\Http\Controllers\ProductFeatureController;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

*/// Route for NFR

Route::get('/nfr', [NFRController::class, 'index'])->name('nfr.index');
Route::get('/nfr/general/create', [NFRController::class, 'create'])->name('nfr.create');
Route::post('/nfr', [NFRController::class, 'store'])->name('nfr.store');
Route::get('/nfr/general/{general_nfr_id}', action: [NFRController::class, 'show'])->name('nfr.show');
Route::get('/nfr/general/{general_nfr_id}/details', [NFRController::class, 'viewGeneral'])->name('nfr.viewGeneral');
Route::get('/nfr/general/{general_nfr_id}/specific/{nfr_id}/details', [NFRController::class, 'viewSpecific'])->name('nfr.viewSpecific');
Route::get('/nfr/general/{general_nfr_id}/create', [NFRController::class, 'createSpecific'])->name('nfr.createSpecific');
Route::post('/nfr/general/{general_nfr_id}/specific', [NFRController::class, 'storeSpecific'])->name('nfr.storeSpecific');
Route::get('/nfr/general/{general_nfr_id}/edit', [NFRController::class, 'edit'])->name('nfr.edit');
Route::put('/nfr/general/{general_nfr_id}', [NFRController::class, 'update'])->name('nfr.update');
Route::get('/nfr/general/{general_nfr_id}/specific/{nfr_id}', [NFRController::class, 'editSpecific'])->name('nfr.editSpecific');
Route::put('/nfr/general/{general_nfr_id}/specific/{nfr_id}', [NFRController::class, 'updateSpecific'])->name('nfr.updateSpecific');
Route::delete('/nfr/general/{general_nfr_id}/destroy', [NFRController::class, 'destroy'])->name('nfr.destroy');
Route::delete('/nfr/general/{general_nfr_id}/specific/{nfr_id}/destroy', [NFRController::class, 'destroySpecific'])->name('nfr.destroySpecific');
Route::get('/get-general-nfr/{user_story_id}', [NFRController::class, 'getGeneralNFR']);
Route::get('/get-specific-nfr/{general_nfr_id}', [NFRController::class, 'getSpecificNFR']);
Route::get('/nfr/general/{general_nfr_id}/specific/{nfr_id}/details', [NFRController::class, 'viewSpecific'])->name('nfr.viewSpecific');

// Route for TVT
Route::get('/tvt', [TVTController::class, 'index'])->name('tvt.index');
Route::get('/tvt/show/{proj_id}', 'TVTController@show')->name('tvt.show');
Route::get('/tvt/create/{proj_ID}', 'TVTController@create')->name('tvt.create');
Route::post('/tvt', 'TVTController@store')->name('tvt.store');
Route::get('tvt/edit/{tvt_id}', 'TVTController@edit')->name('tvt.edit');
Route::patch('tvt/update/{tvt_id}', [TVTController::class, 'update'])->name('tvt.update');
Route::get('/tvt/destroy/{tvt_id}', [TVTController::class, 'destroy'])->name('tvt.destroy');
Route::get('tvt/user-stories/{sprint_id}', [TVTController::class, 'getUserStoriesBySprint']);
Route::get('tvt/qaw/{userStoryId}', [TVTController::class, 'getQAWByUserStory']);
Route::get('/tvt/means/{userStoryId}', [TVTController::class, 'getMeans']);
Route::get('/tvt/nfr/{qawId}', [TVTController::class, 'getNfrByQaw']);

// Route for CIG
Route::get('/cig', [CIGController::class, 'index'])->name('cig.index');
Route::get('cig/create/{proj_id}', [CIGController::class, 'create'])->name('cig.create');
Route::post('/cig', 'CIGController@store')->name('cig.store');
Route::get('/radar-data/{proj_id}/{sprint_id?}', [CIGController::class, 'getRadarData']);
Route::get('/cig/{proj_id}/generate-report', [CIGController::class, 'generateXlsxReport'])->name('generate.xlsx.report');
Route::post('/upload-radar-chart', [CIGController::class, 'uploadRadarChart']);

Route::get('/send-whatsapp', [TeamController::class, 'sendWhatsAppMessage']);
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();


Route::get('/send-test-email', function () {
    Mail::to('test@example.com')->send(new TestMail());
    return 'Test email has been sent!';
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

//Route for Project Actions
Route::get('projects', 'ProjectController@index')->name('project.index');
Route::get('projects/create', 'ProjectController@create')->name('projects.create');
Route::get('projects/{project}/edit', 'ProjectController@edit')->name('projects.edit');
Route::post('projects', 'ProjectController@store')->name('projects.store');
Route::post('projects/{project}', 'ProjectController@update')->name('projects.update');
Route::get('projects/{project}/destroy', 'ProjectController@destroy')->name('projects.destroy');

//Route for sprint
Route::get('sprint', 'SprintController@index')->name('sprints.index');
Route::get('sprint/create/{proj_name}', 'SprintController@create')->name('sprints.create');
Route::get('sprint/{sprint_id}/edit', 'SprintController@edit')->name('sprints.edit');
Route::post('sprint', 'SprintController@store')->name('sprints.store');
Route::post('sprint/{sprint}', 'SprintController@update')->name('sprints.update');
Route::get('sprint/{sprint}/destroy', 'SprintController@destroy')->name('sprints.destroy');
Route::get('sprint/{sprint}/end_sprint', 'SprintController@endSprint')->name('sprints.endSprint');
Route::post('sprint/{sprint_id}/add-items', [SprintController::class, 'addItemsToSprint'])->name('sprint.addItems');

//Route for Project List
Route::get('profeature', 'ProductFeatureController@index')->name('profeature.index');
Route::get('projects', 'ProductFeatureController@indexReloaded')->name('project.newIndex');
Route::get('/projects/{id}', 'ProductFeatureController@details')->name('projects.details');
//Main Sprint Page
Route::get('profeature/{proj_name}', 'ProductFeatureController@index2')->name('profeature.index2');
// Route::post('profeature/{proj_name}', 'ProductFeatureController@index2')->name('profeature.index2');
Route::get('profeature/userstory/{sprint_id}', 'ProductFeatureController@index3')->name('profeature.index3');
Route::get('profeature/create', 'ProductFeatureController@create')->name('profeature.create');
Route::get('profeature/{profeature}/edit', 'ProductFeatureController@edit')->name('profeature.edit');
Route::get('sprint/{sprint_id}/edit2', 'ProductFeatureController@edit2')->name('profeature.edit2');


//Route for chart
Route::get('chart/{sprint_id}', 'ChartController@index')->name('chart.index');
Route::get('/create-burndown', 'BurndownChartController@create');
// Route::get('/create-burnup', 'BurnupChartController@create');

//Route for menu
Route::get('/menu', 'MenuController@showMenu');

//Route for team

Route::get('team', 'TeamController@index')->name('team.index');
Route::get('teams/create', 'TeamController@create')->name('teams.create');
Route::get('teams/show', 'TeamController@show')->name('teams.show');
Route::get('teams/{team}/edit', 'TeamController@edit')->name('teams.edit');
Route::post('teams', 'TeamController@store')->name('teams.store');
Route::post('teams/{team}', 'TeamController@update')->name('teams.update');
Route::get('teams/{team}/destroy', 'TeamController@destroy')->name('teams.destroy');
Route::get('teams','TeamController@search');
// Route::post('/send-invitation-email', 'TeamController@sendInvitationEmail')->name('send.invitation.email');
Route::get('teams/sendmail','TeamController@sendMail')->name('Team.invitationEmailTest');

Route::post('/getTeamMembers', [TeamMappingController::class, 'getTeamMembers'])->name('getTeamMembers');
Route::post('/getTeamMembers1', [TeamMappingController::class, 'getTeamMembers1'])->name('getTeamMembers1');
Route::get('teams','TeamController@search');

//Route for Defect Feature
Route::get('deffeature', 'DefectFeatureController@index')->name('deffeature.index');
//Route::get('deffeature/add', 'DefectFeatureController@add')->name('deffeature.add');
Route::get('deffeature/create', 'DefectFeatureController@create')->name('deffeature.create');
Route::get('deffeature/{deffeature}/edit', 'DefectFeatureController@edit')->name('deffeature.edit');
Route::post('deffeature', 'DefectFeatureController@store')->name('deffeature.store');
Route::post('deffeature/{deffeature}', 'DefectFeatureController@update')->name('deffeature.update');
Route::post('deffeature/{deffeature}/destroy', 'DefectFeatureController@destroy')->name('deffeature.destroy');


Route::group(['middleware' => 'auth'], function () {
    Route::get('tasks', 'TaskController@index')->name('tasks.index');
    Route::post('tasks', 'TaskController@store')->name('tasks.store');
    Route::put('tasks/sync', 'TaskController@sync')->name('tasks.sync');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('status', 'StatusController@index')->name('status.index');
    Route::get('status/{proj_ID}', 'StatusController@indexProjectStatus')->name('status.project');
    Route::get('statuses/create/{proj_ID}', 'StatusController@create')->name('statuses.create');
    Route::get('statuses/{status}/edit', 'StatusController@edit')->name('statuses.edit');
    Route::post('status', 'StatusController@store')->name('statuses.store');
    Route::put('statuses/{status}', 'StatusController@update')->name('statuses.update');
    Route::get('statuses/{status}/destroy', 'StatusController@destroy')->name('statuses.destroy');
});

// //Route for status
// Route::get('statuses', 'StatusController@index')->name('status.index');
// Route::get('statuses/create', 'StatusController@create')->name('statuses.create');
// Route::get('statuses/{status}/edit', 'StatusController@edit')->name('statuses.edit');
// Route::post('statuses', 'StatusController@store')->name('statuses.store');
// Route::post('statuses/{status}', 'StatusController@update')->name('statuses.update');
// Route::get('statuses/{status}/destroy', 'StatusController@destroy')->name('statuses.destroy');

//Route for role
Route::get('role', 'RoleController@index')->name('roles.index');
Route::get('roles/create', 'RoleController@create')->name('roles.create');
Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
Route::post('roles', 'RoleController@store')->name('roles.store');
Route::post('roles/{role}', 'RoleController@update')->name('roles.update');
Route::get('roles/{role}/destroy', 'RoleController@destroy')->name('roles.destroy');
Route::post('/roles', [RoleController::class, 'createRole'])->name('roles.create');
Route::post('/roles/assign', [RoleController::class, 'assignRole'])->name('roles.assign');

//Route for adding users
Route::post('/add-user', [UsersController::class, 'addUser'])->name('add_user');

// Route to display the ACL management page
Route::get('/pages/access-control-list', [AccessControlList::class, 'index'])->name('access-control-list');
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
Route::put('/update-user/{id}', [UsersController::class, 'update'])->name('update_user');
Route::post('/assign-role', [AccessControlList::class, 'assignRole'])->name('access-control-list.assignRole');
Route::post('/assign-permission', [AccessControlList::class, 'assignPermission'])->name('access-control-list.assignPermission');
Route::post('/saveAccessControl', [TeamMappingController::class, 'saveAccessControl'])->name('saveAccessControl');

//Route for Dashboard
Route::get('/layouts/app2', [DashboardController::class, 'index'])->name('layouts.app2');

//Route for Coding Standard
Route::get('codestand', 'CodingStandardController@index')->name('codestand.index');
Route::get('codestand/create', 'CodingStandardController@create')->name('codestand.create');
Route::get('codestand/show', 'CodingStandardController@show')->name('codestand.show');
Route::get('codestand/{codestand}/edit', 'CodingStandardController@edit')->name('codestand.edit');
Route::post('codestand', 'CodingStandardController@store')->name('codestand.store');
Route::post('codestand/{codestand}', 'CodingStandardController@update')->name('codestand.update');
Route::get('codestand/{codestand}/destroy', 'CodingStandardController@destroy')->name('codestand.destroy');

//Route for attachment
//Route::get('uploadfile','UploadFileController@index')->name('uploadfile.index');
//Route::post('/uploadfile','UploadFileController@showUploadFile');
Route::get('attachment', 'AttachmentController@index')->name('attachment.index');
//Route::get('attachment', 'AttachmentController@index')->name('attachment.index');
Route::get('attachment/create', 'AttachmentController@createForm')->name('attachment.createForm');
Route::post('attachments', 'AttachmentController@fileUpload')->name('attachment.fileUpload');
Route::get('attachments/{attachment}/destroy', 'AttachmentController@destroy')->name('attachments.destroy');

//Route for Team Mapping (Assign Team Member to Team)
//Route::get('teammapping', 'TeamMappingController@index')->name('teammapping.index');

//view team members
Route::get('teammappings/{team_name}', 'TeamMappingController@index')->name('teammapping.index');
Route::get('teammappings/{team_name}/create', 'TeamMappingController@create')->name('teammappings.create');
Route::get('teammappings/show', 'TeamMappingController@show')->name('teammappings.show');
Route::get('teammappings/{teammapping_id}/edit', 'TeamMappingController@edit')->name('teammappings.edit');
Route::post('teammappings', 'TeamMappingController@store')->name('teammappings.store');
Route::get('teammappings/{teammapping}/destroy', 'TeamMappingController@destroy')->name('teammappings.destroy');
Route::get('teammappings','TeamMappingController@search')->name('teammappings.search');
Route::get('teammappings', 'TeamMappingController@getUsers');
Route::post('getUsers', 'TeamMappingController@getUsers')->name('getUsers.post');
Route::post('teammappings/{teammapping}/updateRole', 'TeamMappingController@updateRole')->name('teammappings.updateRole');

//Route for user stories
Route::get('userstory', 'UserStoryController@getID')->name('userstory.getID');
Route::get('userstory', 'UserStoryController@index')->name('userstory.index');
Route::get('userstory/{sprint_id}/create', 'UserStoryController@create')->name('userstory.create');
Route::get('userstory/{userstory}/edit', 'UserStoryController@edit')->name('userstory.edit');
Route::get('userstory/{userstory}/viewDetails', 'UserStoryController@viewDetails')->name('userstory.viewDetails');
Route::post('userstory', 'UserStoryController@store')->name('userstory.store');
Route::post('userstory/{userstory}', 'UserStoryController@update')->name('userstory.update');
Route::get('userstory/{userstory}/destroy', 'UserStoryController@destroy')->name('userstory.destroy');
//backlog for userstories
Route::get('userstory/backlog/{sprint_id}', 'UserStoryController@backlog')->name('userstory.backlog');
Route::get('userstory/backlog/assign/{sprint_id}/{userstory}', 'UserStoryController@assignUserstory')->name('userstory.assign');



//Route for backlog
Route::get('backlog/{proj_id}', 'ProductFeatureController@backlog')->name('backlog.index');
Route::get('backlog/{proj_id}/create', 'UserStoryController@createBacklog')->name('backlog.create');
Route::get('backlog/{userstory}/edit', 'UserStoryController@editBacklog')->name('backlog.edit');
Route::post('backlog', 'UserStoryController@storeBacklog')->name('backlog.store');
Route::post('backlog/{userstory}', 'UserStoryController@updateBacklog')->name('backlog.update');
Route::get('backlog/{userstory}/destroy', 'UserStoryController@destroy')->name('backlog.destroy');

//Route for backlog test
Route::get('backlogTest/{proj_id}', 'BacklogController@index')->name('backlogTest.index');

//Route for Task Assign
//Kanban Board
Route::get('sprint/task', 'TaskController@kanbanBoard')->name('tasks.kanban');
//create comment at kanban - /tasks/${taskId}/createKanbanComment (put)
Route::post('/tasks/{task_id}/createKanbanComment', 'TaskController@createKanbanComment');
Route::put('/tasks/{id}', 'TaskController@updateKanbanBoard');
Route::get('/tasks/{task_id}/description', 'TaskController@getTaskDescription')->name('tasks.description');
Route::get('tasks/{task_id}/comment/{sprint_id}', 'TaskController@viewCommentList')->name('tasks.viewCommentList');
Route::get('/tasks/{task_id}/comment/create/{sprint_id}', 'TaskController@createComment')->name('tasks.createComment');
Route::put('/tasks/{task_id}/comment', 'TaskController@storeComment')->name('tasks.storeComment');
Route::delete('/tasks/comment/{comment_id}', 'TaskController@deleteComment')->name('tasks.deleteComment');
Route::get('/tasks/{comment_id}/editComment', [TaskController::class, 'editComment'])->name('tasks.editComment');
Route::put('/tasks/{comment_id}/updateComment', [TaskController::class, 'updateComment'])->name('tasks.updateComment');
Route::put('/tasks/{comment}/updateKanbanComment', [TaskController::class, 'updateKanbanComment'])->name('tasks.updateKanbanComment');

//Main Task Page
// Route::get('task/{u_id}', 'TaskController@index')->name('tasks.index');
Route::get('tasks/{userstory_id}', 'TaskController@index')->name('tasks.index');
Route::get('task/{userstory}/create', 'TaskController@create')->name('tasks.create');
Route::get('task/{id}/edit', 'TaskController@edit')->name('tasks.edit');
Route::post('task/{task}', 'TaskController@update')->name('tasks.update');
Route::get('task/{task}/destroy', 'TaskController@destroy')->name('tasks.destroy');

//Route for UCD
Route::get('ucd/{sprint_id}', 'UCDController@index')->name('ucd.index');


//Route for security feature
Route::get('secfeatures', 'SecurityFeatureController@index')->name('secfeature.index');
Route::get('secfeatures/create', 'SecurityFeatureController@create')->name('secfeature.create');
Route::get('secfeatures/{secfeature}/edit', 'SecurityFeatureController@edit')->name('secfeature.edit');
Route::post('secfeatures', 'SecurityFeatureController@store')->name('secfeature.store');
Route::post('secfeatures/{secfeature}', 'SecurityFeatureController@update')->name('secfeature.update');
Route::get('secfeatures/{secfeature}/destroy', 'SecurityFeatureController@destroy')->name('secfeature.destroy');

//Route for Performance Feature
Route::get('perfeatures', 'PerformanceFeatureController@index')->name('perfeature.index');
Route::get('perfeatures/create', 'PerformanceFeatureController@create')->name('perfeature.create');
Route::get('perfeatures/{perfeature}/edit', 'PerformanceFeatureController@edit')->name('perfeature.edit');
Route::post('perfeatures', 'PerformanceFeatureController@store')->name('perfeature.store');
Route::post('perfeatures/{perfeature}', 'PerformanceFeatureController@update')->name('perfeature.update');
Route::get('perfeatures/{perfeature}/destroy', 'PerformanceFeatureController@destroy')->name('perfeature.destroy');


//Route for role
Route::get('role', 'RoleController@index')->name('role.index');
Route::get('roles/create', 'RoleController@create')->name('roles.create');
Route::get('roles/{role}/edit', 'RoleController@edit')->name('roles.edit');
Route::get('/users', [UsersController::class, 'showAllUsers'])->name('users.index');
Route::post('roles', 'RoleController@store')->name('roles.store');
Route::patch('roles/{role}', 'RoleController@update')->name('roles.update');
Route::get('roles/{role}/destroy', 'RoleController@destroy')->name('roles.destroy');
Route::delete('/roles/{role}', 'RoleController@destroy')->name('roles.destroy');


//Route for Coding Standard
Route::get('codestand', 'CodingStandardController@index')->name('codestand.index');
Route::get('codestand/create', 'CodingStandardController@create')->name('codestand.create');
Route::get('codestand/show', 'CodingStandardController@show')->name('codestand.show');
Route::get('codestand/{codestand}/edit', 'CodingStandardController@edit')->name('codestand.edit');
Route::post('codestand', 'CodingStandardController@store')->name('codestand.store');
Route::post('codestand/{codestand}', 'CodingStandardController@update')->name('codestand.update');
Route::get('codestand/{codestand}/destroy', 'CodingStandardController@destroy')->name('codestand.destroy');

//Route for attachment
//Route::get('uploadfile','UploadFileController@index')->name('uploadfile.index');
//Route::post('/uploadfile','UploadFileController@showUploadFile');
Route::get('attachment', 'AttachmentController@index')->name('attachment.index');
//Route::get('attachment', 'AttachmentController@index')->name('attachment.index');
Route::get('attachment/create', 'AttachmentController@createForm')->name('attachment.createForm');
Route::post('attachments', 'AttachmentController@fileUpload')->name('attachment.fileUpload');
Route::get('attachments/{attachment}/destroy', 'AttachmentController@destroy')->name('attachments.destroy');

//Route for Team Mapping (Assign Team Member to Team)
//Route::get('teammapping', 'TeamMappingController@index')->name('teammapping.index');

//view team members
Route::get('teammappings/{team_name}', 'TeamMappingController@index')->name('teammapping.index');
Route::get('teammappings/{team_name}/create', 'TeamMappingController@create')->name('teammappings.create');
Route::get('teammappings/show', 'TeamMappingController@show')->name('teammappings.show');
Route::get('teammappings/{teammapping_id}/edit', 'TeamMappingController@edit')->name('teammappings.edit');
Route::post('teammappings', 'TeamMappingController@store')->name('teammappings.store');
Route::get('teammappings/{teammapping}/destroy', 'TeamMappingController@destroy')->name('teammappings.destroy');
Route::get('teammappings','TeamMappingController@search')->name('teammappings.search');
Route::get('teammappings', 'TeamMappingController@getUsers');
Route::post('getUsers', 'TeamMappingController@getUsers')->name('getUsers.post');

//Route for user stories
Route::get('userstory', 'UserStoryController@getID')->name('userstory.getID');
Route::get('userstory/{proj_id}', 'UserStoryController@index')->name('userstory.index');
Route::get('userstory/{sprint_id}/create', 'UserStoryController@create')->name('userstory.create');
Route::get('userstory/{userstory}/edit', 'UserStoryController@edit')->name('userstory.edit');
Route::post('userstory', 'UserStoryController@store')->name('userstory.store');
Route::post('userstory/{userstory}', 'UserStoryController@update')->name('userstory.update');
Route::get('userstory/{userstory}/destroy', 'UserStoryController@destroy')->name('userstory.destroy');
Route::patch('/userstory/{id}/updateStatus', 'UserStoryController@updateStatus')->name('userstory.updateStatus');
//backlog for userstories
Route::get('userstory/backlog/{sprint_id}', 'UserStoryController@backlog')->name('userstory.backlog');
Route::get('userstory/backlog/assign/{sprint_id}/{userstory}', 'UserStoryController@assignUserstory')->name('userstory.assign');



//Route for backlog
Route::get('backlog/{proj_id}', 'ProductFeatureController@backlog')->name('backlog.index');
Route::get('backlog/{proj_id}/create', 'UserStoryController@createBacklog')->name('backlog.create');
Route::get('backlog/{userstory}/edit', 'UserStoryController@editBacklog')->name('backlog.edit');
Route::post('backlog', 'UserStoryController@storeBacklog')->name('backlog.store');
Route::post('backlog/{userstory}', 'UserStoryController@updateBacklog')->name('backlog.update');
Route::get('backlog/{userstory}/destroy', 'UserStoryController@destroy')->name('backlog.destroy');

//Route for Task Assign
//Kanban Board
Route::get('sprint/task', 'TaskController@indexKanbanBoard')->name('tasks.kanban');
Route::get('kanban/{proj_id}', 'TaskController@viewKanbanBoard')->name('tasks.viewkanban');
Route::put('/tasks/{id}', 'TaskController@updateKanbanBoard');

//Kanban Page
Route::get('/{proj_id}/kanbanBoard', 'TaskController@kanbanIndex')->name('sprint.kanbanPage');

Route::post('/addStatus', 'StatusController@createStatus')->name('kanban.createStatus');
Route::put('/updateStatus', 'StatusController@updateStatus')->name('kanban.updateStatus');
Route::put('/updateTaskStatus', 'StatusController@updateTaskStatus')->name('kanban.updateTaskStatus');
Route::delete('/deleteStatus', 'StatusController@deleteStatus')->name('kanban.deleteStatus');
Route::post('/createTask', 'TaskController@createTask')->name('kanban.createTask');
Route::delete('/deleteTask', 'TaskController@deleteTask')->name('kanban.deleteTask');
Route::get('/updateTask/{taskId}', 'TaskController@updateTaskPage')->name('kanban.updateTaskPage');




//Main Task Page
Route::get('task/{u_id}', 'TaskController@index')->name('tasks.index');
// Route::get('tasks/{userstory_id}', 'TaskController@index')->name('tasks.index');
Route::get('task/{userstory}/create', 'TaskController@create')->name('tasks.create');
Route::get('task/{id}/edit', 'TaskController@edit')->name('tasks.edit');
Route::post('task/{task}', 'TaskController@update')->name('tasks.update');
Route::get('task/{task}/destroy', 'TaskController@destroy')->name('tasks.destroy');
// Route::get('task/{userstory_id}', 'TaskController@indexCalendar')->name('tasks.calendarTask');
Route::get('task/{userstory_id}/calendarTask', 'TaskController@indexCalendar')->name('tasks.calendarTask');

//Route for security feature
Route::get('secfeatures', 'SecurityFeatureController@index')->name('secfeature.index');
Route::get('secfeatures/create', 'SecurityFeatureController@create')->name('secfeature.create');
Route::get('secfeatures/{secfeature}/edit', 'SecurityFeatureController@edit')->name('secfeature.edit');
Route::post('secfeatures', 'SecurityFeatureController@store')->name('secfeature.store');
Route::post('secfeatures/{secfeature}', 'SecurityFeatureController@update')->name('secfeature.update');
Route::get('secfeatures/{secfeature}/destroy', 'SecurityFeatureController@destroy')->name('secfeature.destroy');

//Route for Performance Feature
Route::get('perfeatures', 'PerformanceFeatureController@index')->name('perfeature.index');
Route::get('perfeatures/create', 'PerformanceFeatureController@create')->name('perfeature.create');
Route::get('perfeatures/{perfeature}/edit', 'PerformanceFeatureController@edit')->name('perfeature.edit');
Route::post('perfeatures', 'PerformanceFeatureController@store')->name('perfeature.store');
Route::post('perfeatures/{perfeature}', 'PerformanceFeatureController@update')->name('perfeature.update');
Route::get('perfeatures/{perfeature}/destroy', 'PerformanceFeatureController@destroy')->name('perfeature.destroy');



//Route for delete Mapping
Route::post('mapping/destroy', 'MappingController@destroy')->name('mapping.destroy');




// Route for Comments
Route::post('/forum/{forum_id}/comments', 'CommentController@store')->name('comments.store');

//Route for Calendar
Route::get('/calendar/index', 'CalendarController@index')->name('calendar.index');
Route::get('/calendar/create', 'CalendarController@create')->name('calendar.create');
Route::post('/calendar', 'CalendarController@store')->name('calendar.store');
Route::patch('/calendar/{id}', 'CalendarController@update')->name('calendar.update');
Route::delete('/calendar/{id}', 'CalendarController@destroy')->name('calendar.destroy');

//Route for BugTracking
Route::get('/bugtrack/{projectId}', 'BugtrackingController@index')->name('bugtrack.index');
Route::get('/bugtrack/create/{projectId?}', 'BugtrackingController@create')->name('bugtrack.create');
Route::post('/bugtrack/{projectId}', 'BugtrackingController@store')->name('bugtrack.store');
Route::put('/bugtrack/{bugId}/update-status', 'BugtrackingController@updateStatus')->name('bugtrack.update_status');
Route::get('/bugtrack/{projectId}/{bugtrackId}/view', 'BugtrackingController@view')->name('bugtrack.view');
Route::get('/bugtrack/{projectId}/{bugtrackId}/view/report', 'BugtrackingController@generateReport')->name('bugtrack.generate');
Route::get('/bugtrack/{projectId}/{bugtrackId}/view/notify', 'BugtrackingController@notify')->name('bugtrack.notify');


// Route for Forum
Route::get('/forum/{projectId}', 'ForumController@index')->name('forum.index');
Route::get('/forum/create/{projectId?}', 'ForumController@create')->name('forum.create');
Route::post('/forum/{projectId}', 'ForumController@store')->name('forum.store');
Route::get('/forum/{projectId}/{forumPostId}/view', 'ForumController@view')->name('forum.view');
Route::get('/forum/{forumPost}/edit', 'ForumController@edit')->name('forum.edit');
Route::put('/forum/{forumPost}', 'ForumController@update')->name('forum.update');
Route::delete('/forum/{forumPost}', 'ForumController@destroy')->name('forum.destroy');

//route for burn down chart
Route::get('/{proj_id}/{sprint_id}/burn-down-chart', 'BurnDownChartController@index')->name('burnDown.index');
Route::get('/{proj_id}/burn-down-chart', 'BurnDownChartController@getBurndownData')->name('burnDown.getBurndownData');
// Route::get('/{sprint_id}/burn-down-chart', 'BurnDownChartController@index')->name('burnDown.index');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

Route::get('/auth/login', [LoginController::class, 'index'])->name('auth-login');
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::get('/auth/forgot-password', [ForgotPasswordController::class, 'index'])->name('auth-reset-password');



Route::get('/bugtrack/calculate-scores', 'BugtrackingController@calculateAllBugScores')->name('bugtrack.calculate');
Route::post('/update-score/{projectId}', 'BugtrackingController@updateScore')->name('bugtrack.updateScore');
Route::get('/bugtrack/createScore/{projectId}', 'BugtrackingController@createScore')->name('bugtrack.createScore');
Route::post('/comments/{comment_id}/update', 'CommentController@update')->name('comments.update');
Route::post('/comments/{comment_id}/delete', 'CommentController@delete')->name('comments.delete');
Route::post('/comments/{id}/reply', 'CommentController@storeReply')->name('comments.reply');
Route::post('/calendar/status-update', 'CalendarController@statusUpdate')->name('calendar.statusUpdate');


//Routes for partial loading of page contents
Route::get('/roles', [RoleController::class, 'indexPartial']);


// URL::forceScheme('https');

// SSO Routes
Route::get('login/sso', [App\Http\Controllers\Auth\SSOController::class, 'showLoginForm'])->name('sso.login.form');
Route::post('login/sso', [App\Http\Controllers\Auth\SSOController::class, 'login'])->name('sso.login');
Route::get('login/sso/direct', [App\Http\Controllers\Auth\SSOController::class, 'handleDirectLogin'])->name('sso.login.direct');