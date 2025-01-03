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
// $routes->post('/authenticate', 'SigninController::authenticate');
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
// $routes->get(getEndpoint('/users/(:any)/widget/todays-work-summary'), 'UserDashboardWidgetController::todaysWorkSummary/$1',['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/users/widget/summary'), 'UserDashboardWidgetController::summary', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/users/widget/user-last-activities'), 'UserDashboardWidgetController::userLastActivityLogs', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/users/activities'), 'UserDashboardWidgetController::userActivityByDate', ['filter' => 'JWTAuthGuard']);
// timesheet
$routes->get(getEndpoint('/timesheet/activity-types'), 'TimesheetController::getActivityTypes', ['filter' => 'JWTAuthGuard']);
$routes->post(getEndpoint('/timesheet/add-manual-time'), 'TimesheetController::addManualTime', ['filter' => 'JWTAuthGuard']);

// leave
$routes->post(getEndpoint('/leave'), 'LeaveController::addLeaveRequest', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/leave'), 'LeaveController::getLeaveRequest', ['filter' => 'JWTAuthGuard']);
$routes->get(getEndpoint('/leave/org/settings'), 'LeaveController::getOrgLeaveSettings', ['filter' => 'JWTAuthGuard']);

