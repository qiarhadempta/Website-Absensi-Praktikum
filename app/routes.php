<?php
return [
    ['GET', '/', 'AuthController@showLogin'],
    ['GET', '/login', 'AuthController@showLogin'],
    ['POST', '/login', 'AuthController@login'],
    ['POST', '/logout', 'AuthController@logout'],
    ['GET', '/password/change', 'AuthController@showChangePassword'],
    ['POST', '/password/change', 'AuthController@changePassword'],
    ['GET', '/dashboard', 'DashboardController@index'],

    // Modul Admin (Dosen Pengampu Jarkom)
    ['GET', '/admin/users', 'UserController@index'],
    ['GET', '/admin/users/form', 'UserController@form'],
    ['POST', '/admin/users/save', 'UserController@save'],
    ['POST', '/admin/users/reset-password', 'UserController@resetPassword'],
    ['POST', '/admin/users/toggle-active', 'UserController@toggleActive'],
    ['GET', '/admin/course', 'CourseController@index'],
    ['POST', '/admin/course/save', 'CourseController@save'],
    ['POST', '/admin/course/sync', 'CourseController@sync'],
    ['GET', '/admin/audit-logs', 'AuditLogController@index'],
];