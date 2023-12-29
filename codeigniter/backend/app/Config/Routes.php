<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('/', 'SignupController::index');
$routes->get('/signup', 'SignupController::index');
$routes->match(['get', 'post'], 'SignupController/store', 'SignupController::store');
$routes->match(['get', 'post'], 'SigninController/loginAuth', 'SigninController::loginAuth');
$routes->post('/authenticate', 'SigninController::authenticate');
$routes->get('/signin', 'SigninController::index');
$routes->get('/profile', 'ProfileController::index',['filter' => 'authGuard']);

// API Routes
// auth
$routes->post('/api/v1/authenticate', 'AuthController::authenticate');

// users
$routes->post('/api/v1/users/register', 'UsersController::register');
$routes->get('/api/v1/users/profile', 'UsersController::userProfile', ['filter' => 'JWTAuthGuard']);
$routes->get('/api/v1/settings/tracker-config', 'SettingsController::getTrackerConfig', ['filter' => 'JWTAuthGuard']);
