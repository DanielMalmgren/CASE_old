<?php

Auth::routes();

//Route::get('/login',                            'HomeController@login');

Route::get('/',                                 'HomeController@index');
Route::get('/about',                            'HomeController@about');
Route::get('/logout',                           'HomeController@logout');

//TrackController
Route::get('/tracks',                           'TrackController@index');
Route::get('/track/{track}',                    'TrackController@show');

//LessonController
Route::get('/lessons/create/{track}',           'LessonController@create')->middleware('permission:manage lessons');
Route::post('/lessons',                         'LessonController@store')->middleware('permission:manage lessons');
Route::get('/lessons/{lesson}',                 'LessonController@show');
Route::get('/lessons/{lesson}/edit',            'LessonController@edit')->middleware('permission:manage lessons');
Route::put('/lessons/{lesson}',                 'LessonController@update')->middleware('permission:manage lessons');
Route::get('/lessons/{lesson}/editquestions',   'LessonController@editquestions')->middleware('permission:manage lessons');
Route::put('/lessons/{lesson}/vote',            'LessonController@vote');
Route::get('/lessons/{lesson}/finish',          'LessonController@finish');
Route::post('/lessons/reorder',                 'LessonController@reorder')->middleware('permission:manage lessons');

//TestController
Route::get('/test/{lesson}',                    'TestController@show');
Route::post('/test/storeResponse',              'TestController@store');

//QuestionController
Route::get('/test/question/create',             'QuestionController@create')->middleware('permission:manage lessons');
Route::post('/test/question',                   'QuestionController@store')->middleware('permission:manage lessons');
Route::get('/test/question/{question}',         'QuestionController@show');
Route::get('/test/question/{question}/edit',    'QuestionController@edit')->middleware('permission:manage lessons');
Route::put('/test/question/{question}',         'QuestionController@update')->middleware('permission:manage lessons');
Route::delete('/test/question/{question}',      'QuestionController@destroy')->middleware('permission:manage lessons');
Route::post('/test/question/reorder',           'QuestionController@reorder')->middleware('permission:manage lessons');

//TestResultController
Route::get('/test/result/{test_session}',       'TestResultController@show');

//FeedbackController
Route::get('/feedback',                         'FeedbackController@create');
Route::post('/feedback',                        'FeedbackController@post');

//UsersControler
//Route::get('/userinfo/{user?}',                 'UsersController@show');
Route::get('/users/create',                     'UsersController@create')->middleware('permission:manage users');
Route::post('/users',                           'UsersController@store')->middleware('permission:manage users');
Route::get('/users',                            'UsersController@index')->middleware('permission:manage users');
Route::get('/exportusers',                      'UsersController@export')->middleware('permission:manage users');
Route::delete('/user/{user}',                   'UsersController@destroy')->middleware('permission:manage users');
Route::get('/select2users',                     'UsersController@select2');

//ActiveTimeController
Route::post('/activetime',                      'ActiveTimeController@store');

//TimeSummaryController
Route::get('/timesummary',                      'TimeSummaryController@show')->middleware('permission:export ESF report');
Route::get('/timesummaryajax/{rel_month}',      'TimeSummaryController@ajax')->middleware('permission:export ESF report');
Route::get('/timesummarywpdetails/{workplace}/{year}/{month}', 'TimeSummaryController@wpdetails')->middleware('permission:export ESF report');
Route::get('/exporttimesummary',                'TimeSummaryController@export')->middleware('permission:export ESF report');

//ProjectTimeController
Route::get('/projecttime',                      'ProjectTimeController@index');
Route::get('/projecttime/create',               'ProjectTimeController@create')->middleware('permission:edit workplaces');
Route::get('/projecttime/createsingleuser',     'ProjectTimeController@createsingleuser');
Route::post('/projecttime',                     'ProjectTimeController@store');
Route::get('/projecttime/{project_time}/edit',  'ProjectTimeController@edit');
Route::put('/projecttime/{project_time}',       'ProjectTimeController@update');
Route::get('/projecttimeajax/{workplace}',      'ProjectTimeController@ajax');

//TimeAttestController
Route::get('/timeattest/create',                'TimeAttestController@create')->middleware('permission:manage time attests');
Route::post('/timeattest',                      'TimeAttestController@store')->middleware('permission:manage time attests');
Route::get('/timeattestajaxuserlist/{workplace}/{year}/{month}', 'TimeAttestController@ajaxuserlist')->middleware('permission:manage time attests');
Route::get('/timeattestajaxuserdetails/{user}/{year}/{month}', 'TimeAttestController@ajaxuserdetails')->middleware('permission:manage time attests');

//TimeAttestLevel1Controller
Route::get('/timeattestlevel1/create',          'TimeAttestLevel1Controller@create');
Route::post('/timeattestlevel1',                'TimeAttestLevel1Controller@store');
Route::get('/manualattestxls',                  'TimeAttestLevel1Controller@manualattestxls');

//FirstLoginController
Route::get('/firstlogin',                       'FirstLoginController@show');
Route::post('storefirstloginlanguage',          'FirstLoginController@storeLanguage');
Route::post('storegdpraccept',                  'FirstLoginController@storeGdprAccept');

//SettingsController
Route::get('/settings/{user?}',                 'SettingsController@edit');
Route::post('storesettings/{user}',             'SettingsController@store');
Route::post('storelanguage',                    'SettingsController@storeLanguage');

//WorkplaceController
Route::get('/workplace/create',                 'WorkplaceController@create')->middleware('permission:add workplaces');
Route::post('/workplace',                       'WorkplaceController@store')->middleware('permission:add workplaces');
Route::get('/workplace',                        'WorkplaceController@edit')->middleware('permission:edit workplaces');
Route::put('/workplace/{workplace}',            'WorkplaceController@update')->middleware('permission:edit workplaces');
Route::get('/workplaceajax/{workplace}',        'WorkplaceController@ajax')->middleware('permission:edit workplaces');
Route::delete('/workplace/{workplace}',         'WorkplaceController@destroy')->middleware('permission:add workplaces');

//SessionController
Route::get('/sessions',                         'SessionController@index')->middleware('permission:manage users');

//StatisticsController
Route::get('/statistics',                       'StatisticsController@index');
Route::get('/statistics/ajaxchart/{chartid}',   'StatisticsController@ajaxchart');

//AnnouncementsController
Route::resource('announcements',                'AnnouncementsController');

//SearchController
Route::get('/select2search',                    'SearchController@select2');
