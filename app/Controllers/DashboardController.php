<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\Crop;
use App\Models\HarvestSchedule;
use App\Models\PlantingSchedule;
use App\Models\RainfallData;

class DashboardController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $plantings = PlantingSchedule::getAll();
        $harvests = HarvestSchedule::all();
        $rainfallRows = RainfallData::getAll();

        // Grafik: 30 data terbaru, diurutkan dari tanggal terlama ke terbaru.
        $chartRows = array_reverse(array_slice($rainfallRows, 0, 30));

        $upcoming = array_values(array_filter(
            $harvests,
            static fn (array $h): bool => empty($h['actual_harvest_date'])
        ));

        $this->view('dashboard/index', [
            'totalCrops'       => Crop::count(),
            'totalPlantings'   => count($plantings),
            'totalHarvests'    => count($harvests),
            'totalRainfall'    => count($rainfallRows),
            'latestPlantings'  => array_slice($plantings, 0, 5),
            'upcomingHarvests' => array_slice($upcoming, 0, 5),
            'rainfallLabels'   => array_column($chartRows, 'measurement_date'),
            'rainfallValues'   => array_map('floatval', array_column($chartRows, 'amount_mm')),
        ], 'Dashboard');
    }
}
