<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Security;
use App\Models\Crop;
use App\Models\PlantingSchedule;

class PlantingScheduleController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $search = trim((string) ($_GET['search'] ?? ''));
        $from = $this->dateFromQuery('date_from');
        $to = $this->dateFromQuery('date_to');

        $this->view('planting/index', [
            'items'     => PlantingSchedule::getAll($search, $from, $to),
            'search'    => $search,
            'date_from' => $from,
            'date_to'   => $to,
        ], 'Jadwal Tanam');
    }

    public function form(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $schedule = $id > 0 ? PlantingSchedule::find($id) : null;

        $this->view('planting/form', [
            'schedule' => $schedule,
            'crops'    => Crop::all(),
            'errors'   => [],
        ], $schedule ? 'Edit Jadwal Tanam' : 'Tambah Jadwal Tanam');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('planting/form', [
                'schedule' => $data,
                'crops'    => Crop::all(),
                'errors'   => $errors,
            ], 'Tambah Jadwal Tanam');
            return;
        }

        PlantingSchedule::create($data);
        $this->redirect('/planting-schedules');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0 || !PlantingSchedule::find($id)) {
            $this->redirect('/planting-schedules');
        }

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('planting/form', [
                'schedule' => ['id' => $id] + $data,
                'crops'    => Crop::all(),
                'errors'   => $errors,
            ], 'Edit Jadwal Tanam');
            return;
        }

        PlantingSchedule::update($id, $data);
        $this->redirect('/planting-schedules');
    }

    public function delete(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            PlantingSchedule::delete($id);
        }

        $this->redirect('/planting-schedules');
    }

    private function collect(): array
    {
        return [
            'crop_id'       => trim((string) ($_POST['crop_id'] ?? '')),
            'land_area'     => trim((string) ($_POST['land_area'] ?? '')),
            'planting_date' => trim((string) ($_POST['planting_date'] ?? '')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['crop_id'] === '' || !ctype_digit($data['crop_id']) || !Crop::find((int) $data['crop_id'])) {
            $errors['crop_id'] = ['Tanaman harus dipilih.'];
        }
        if (!is_numeric($data['land_area']) || (float) $data['land_area'] <= 0) {
            $errors['land_area'] = ['Luas lahan harus lebih dari 0.'];
        }
        if (!Security::isValidDate($data['planting_date'])) {
            $errors['planting_date'] = ['Tanggal tanam tidak valid.'];
        }

        return $errors;
    }
}
