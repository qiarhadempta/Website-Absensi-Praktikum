<?php
return [
    ['GET', '/', 'AuthController@showLogin'],
    ['GET', '/login', 'AuthController@showLogin'],
    ['POST', '/login', 'AuthController@login'],
    ['POST', '/logout', 'AuthController@logout'],
    ['GET', '/password/change', 'AuthController@showChangePassword'],
    ['POST', '/password/change', 'AuthController@changePassword'],
    ['GET', '/dashboard', 'DashboardController@index'],
];