<?php
return [
    ['GET', '/', 'AuthController@showLogin'],
    ['GET', '/login', 'AuthController@showLogin'],
    ['POST', '/login', 'AuthController@login'],
    ['POST', '/logout', 'AuthController@logout'],
    ['GET', '/dashboard', 'DashboardController@index'],
];