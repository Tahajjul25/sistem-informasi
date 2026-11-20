<?php

use App\Controllers\AuthController;
use App\Controllers\CropController;
use App\Controllers\DashboardController;
use App\Controllers\HarvestScheduleController;
use App\Controllers\PlantingScheduleController;
use App\Controllers\RainfallController;
use App\Controllers\ReportController;

$routes = [
    ['method' => 'GET', 'uri' => '/', 'controller' => DashboardController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/dashboard', 'controller' => DashboardController::class, 'methodName' => 'index'],

    ['method' => 'GET', 'uri' => '/login', 'controller' => AuthController::class, 'methodName' => 'login'],
    ['method' => 'POST', 'uri' => '/login', 'controller' => AuthController::class, 'methodName' => 'authenticate'],
    ['method' => 'GET', 'uri' => '/register', 'controller' => AuthController::class, 'methodName' => 'register'],
    ['method' => 'POST', 'uri' => '/register', 'controller' => AuthController::class, 'methodName' => 'store'],
    ['method' => 'POST', 'uri' => '/logout', 'controller' => AuthController::class, 'methodName' => 'logout'],

    ['method' => 'GET', 'uri' => '/crops', 'controller' => CropController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/crops/form', 'controller' => CropController::class, 'methodName' => 'form'],
    ['method' => 'POST', 'uri' => '/crops/create', 'controller' => CropController::class, 'methodName' => 'create'],
    ['method' => 'POST', 'uri' => '/crops/edit', 'controller' => CropController::class, 'methodName' => 'edit'],
    ['method' => 'POST', 'uri' => '/crops/delete', 'controller' => CropController::class, 'methodName' => 'delete'],

    ['method' => 'GET', 'uri' => '/planting-schedules', 'controller' => PlantingScheduleController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/planting-schedules/form', 'controller' => PlantingScheduleController::class, 'methodName' => 'form'],
    ['method' => 'POST', 'uri' => '/planting-schedules/create', 'controller' => PlantingScheduleController::class, 'methodName' => 'create'],
    ['method' => 'POST', 'uri' => '/planting-schedules/edit', 'controller' => PlantingScheduleController::class, 'methodName' => 'edit'],
    ['method' => 'POST', 'uri' => '/planting-schedules/delete', 'controller' => PlantingScheduleController::class, 'methodName' => 'delete'],

    ['method' => 'GET', 'uri' => '/harvest-schedules', 'controller' => HarvestScheduleController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/harvest-schedules/form', 'controller' => HarvestScheduleController::class, 'methodName' => 'form'],
    ['method' => 'POST', 'uri' => '/harvest-schedules/create', 'controller' => HarvestScheduleController::class, 'methodName' => 'create'],
    ['method' => 'POST', 'uri' => '/harvest-schedules/edit', 'controller' => HarvestScheduleController::class, 'methodName' => 'edit'],
    ['method' => 'POST', 'uri' => '/harvest-schedules/delete', 'controller' => HarvestScheduleController::class, 'methodName' => 'delete'],

    ['method' => 'GET', 'uri' => '/rainfall', 'controller' => RainfallController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/rainfall/form', 'controller' => RainfallController::class, 'methodName' => 'form'],
    ['method' => 'POST', 'uri' => '/rainfall/create', 'controller' => RainfallController::class, 'methodName' => 'create'],
    ['method' => 'POST', 'uri' => '/rainfall/edit', 'controller' => RainfallController::class, 'methodName' => 'edit'],
    ['method' => 'POST', 'uri' => '/rainfall/delete', 'controller' => RainfallController::class, 'methodName' => 'delete'],

    ['method' => 'GET', 'uri' => '/reports', 'controller' => ReportController::class, 'methodName' => 'index'],
    ['method' => 'GET', 'uri' => '/reports/export', 'controller' => ReportController::class, 'methodName' => 'exportCsv'],
];