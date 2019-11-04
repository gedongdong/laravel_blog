<?php

/*
 * This file is part of the gedongdong/laravel_rbac_permission.
 *
 * (c) gedongdong <gedongdong2010@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

//以.white结尾的别名为不需要授权的路由

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::get('login', 'LoginController@index')->name('admin.login.white');
    Route::post('login', 'LoginController@login')->name('admin.login.post.white');
    Route::post('logout', 'LoginController@logout')->name('admin.logout.white');

    Route::middleware(['login', 'menu'])->group(function () {
        Route::get('/', 'AdminController@index')->name('admin.index.white');
        Route::get('modify_pwd', 'AdminController@modifyPwd')->name('admin.modify_pwd.white');
        Route::post('new_pwd', 'AdminController@newPwd')->name('admin.new_pwd.white');
        Route::get('forbidden', function () {
            return view('admin.403');
        })->name('admin.forbidden.white');

        Route::middleware('auth.can')->group(function () {
            Route::post('/upload', 'UploadController@upload')->name('admin.upload');

            Route::get('/user', 'UserController@index')->name('admin.user.index');
            Route::get('/user/create', 'UserController@create')->name('admin.user.create');
            Route::post('/user/store', 'UserController@store')->name('admin.user.store');
            Route::post('/user/status', 'UserController@status')->name('admin.user.status');
            Route::get('/user/edit', 'UserController@edit')->name('admin.user.edit');
            Route::post('/user/update', 'UserController@update')->name('admin.user.update');
            Route::post('/user/reset', 'UserController@reset')->name('admin.user.reset');

            Route::get('/permission', 'PermissionController@index')->name('admin.permission.index');
            Route::get('/permission/create', 'PermissionController@create')->name('admin.permission.create');
            Route::post('/permission/store', 'PermissionController@store')->name('admin.permission.store');
            Route::get('/permission/edit', 'PermissionController@edit')->name('admin.permission.edit');
            Route::post('/permission/update', 'PermissionController@update')->name('admin.permission.update');
            Route::post('/permission/delete', 'PermissionController@delete')->name('admin.permission.delete');

            Route::get('/roles', 'RolesController@index')->name('admin.roles.index');
            Route::get('/roles/create', 'RolesController@create')->name('admin.roles.create');
            Route::post('/roles/store', 'RolesController@store')->name('admin.roles.store');
            Route::get('/roles/edit', 'RolesController@edit')->name('admin.roles.edit');
            Route::post('/roles/update', 'RolesController@update')->name('admin.roles.update');
            Route::post('/roles/delete', 'RolesController@delete')->name('admin.roles.delete');

            Route::get('/menu', 'MenuController@index')->name('admin.menu.index');
            Route::get('/menu/create', 'MenuController@create')->name('admin.menu.create');
            Route::post('/menu/store', 'MenuController@store')->name('admin.menu.store');
            Route::get('/menu/edit', 'MenuController@edit')->name('admin.menu.edit');
            Route::post('/menu/update', 'MenuController@update')->name('admin.menu.update');
            Route::post('/menu/delete', 'MenuController@delete')->name('admin.menu.delete');

            Route::get('/category', 'CategoryController@index')->name('category.index');
            Route::get('/category/create', 'CategoryController@create')->name('category.create');
            Route::post('/category/store', 'CategoryController@store')->name('category.store');
            Route::get('/category/edit', 'CategoryController@edit')->name('category.edit');
            Route::post('/category/update', 'CategoryController@update')->name('category.update');
            Route::post('/category/delete', 'CategoryController@delete')->name('category.delete');
            Route::post('/category/status', 'CategoryController@status')->name('category.status');

            Route::get('/links', 'LinksController@index')->name('links.index');
            Route::get('/links/create', 'LinksController@create')->name('links.create');
            Route::post('/links/store', 'LinksController@store')->name('links.store');
            Route::get('/links/edit', 'LinksController@edit')->name('links.edit');
            Route::post('/links/update', 'LinksController@update')->name('links.update');
            Route::post('/links/delete', 'LinksController@delete')->name('links.delete');
            Route::post('/links/status', 'LinksController@status')->name('links.status');

            Route::get('/tags', 'TagsController@index')->name('tags.index');
            Route::get('/tags/create', 'TagsController@create')->name('tags.create');
            Route::post('/tags/store', 'TagsController@store')->name('tags.store');
            Route::post('/tags/delete', 'TagsController@delete')->name('tags.delete');

            Route::get('/lunbo', 'LunboController@index')->name('lunbo.index');
            Route::get('/lunbo/create', 'LunboController@create')->name('lunbo.create');
            Route::post('/lunbo/store', 'LunboController@store')->name('lunbo.store');
            Route::get('/lunbo/edit', 'LunboController@edit')->name('lunbo.edit');
            Route::post('/lunbo/update', 'LunboController@update')->name('lunbo.update');
            Route::post('/lunbo/delete', 'LunboController@delete')->name('lunbo.delete');
            Route::post('/lunbo/status', 'LunboController@status')->name('lunbo.status');

            Route::get('/config/index', 'ConfigController@index')->name('config.index');
            Route::post('/config/store', 'ConfigController@store')->name('config.store');

            Route::get('/post', 'PostController@index')->name('post.index');
            Route::get('/post/create', 'PostController@create')->name('post.create');
            Route::post('/post/store', 'PostController@store')->name('post.store');
            Route::get('/post/edit', 'PostController@edit')->name('post.edit');
            Route::post('/post/update', 'PostController@update')->name('post.update');
            Route::post('/post/delete', 'PostController@delete')->name('post.delete');
            Route::post('/post/status', 'PostController@status')->name('post.status');
        });
    });
});

//前端路由
Route::middleware(['switch', 'common'])->namespace('Index')->group(function () {
    Route::get('/', 'IndexController@index')->name('index.white');
    Route::get('/cate', 'CategoryController@index')->name('category.white');
});
