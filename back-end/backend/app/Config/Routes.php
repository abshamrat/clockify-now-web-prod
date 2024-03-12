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
function getEndpoint($endpoint) {
    $prefix = '/api/v1';
    return $prefix.$endpoint;
}
// auth
$routes->post(getEndpoint('/authenticate'), 'AuthController::authenticate');

// users
$routes->post(getEndpoint('/users/register'), 'UsersController::register');
$routes->get(getEndpoint('/users/profile'), 'UsersController::userProfile', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/users/tracker-config'), 'UsersController::getUserTrackerConfig', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/settings/tracker-config'), 'SettingsController::getTrackerConfig', ['filter' => 'JWTAuthGuard']);
$routes->post(getEndpoint('/trackers/track'), 'TrackersController::track', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/users/(:any)/widget/todays-work-summary'), 'UserDashboardWidgetController::todaysWorkSummary/$1',['filter' => 'JWTAuthGuard']);
