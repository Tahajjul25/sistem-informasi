<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Security;
use App\Models\HarvestSchedule;
use App\Models\PlantingSchedule;

class HarvestScheduleController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $from = $this->dateFromQuery('date_from');
        $to = $this->dateFromQuery('date_to');

        $this->view('harvest/index', [
            'items'     => HarvestSchedule::all($from, $to),
            'date_from' => $from,
            'date_to'   => $to,
        ], 'Jadwal Panen');
    }

    public function form(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $id > 0 ? HarvestSchedule::find($id) : null;

        $this->view('harvest/form', [
            'item'     => $item,
            'plantings' => PlantingSchedule::getAll(),
            'errors'   => [],
        ], $item ? 'Edit Jadwal Panen' : 'Tambah Jadwal Panen');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('harvest/form', [
                'item'      => $data,
                'plantings' => PlantingSchedule::getAll(),
                'errors'    => $errors,
            ], 'Tambah Jadwal Panen');
            return;
        }

        HarvestSchedule::create($this->prepare($data));
        $this->redirect('/harvest-schedules');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0 || !HarvestSchedule::find($id)) {
            $this->redirect('/harvest-schedules');
        }

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('harvest/form', [
                'item'      => ['id' => $id] + $data,
                'plantings' => PlantingSchedule::getAll(),
                'errors'    => $errors,
            ], 'Edit Jadwal Panen');
            return;
        }

        HarvestSchedule::update($id, $this->prepare($data));
        $this->redirect('/harvest-schedules');
    }

    public function delete(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            HarvestSchedule::delete($id);
        }

        $this->redirect('/harvest-schedules');
    }

    private function collect(): array
    {
        return [
            'planting_schedule_id'   => trim((string) ($_POST['planting_schedule_id'] ?? '')),
            'estimated_harvest_date' => trim((string) ($_POST['estimated_harvest_date'] ?? '')),
            'actual_harvest_date'    => trim((string) ($_POST['actual_harvest_date'] ?? '')),
            'notes'                  => trim((string) ($_POST['notes'] ?? '')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (
            $data['planting_schedule_id'] === ''
            || !ctype_digit($data['planting_schedule_id'])
            || !PlantingSchedule::find((int) $data['planting_schedule_id'])
        ) {
            $errors['planting_schedule_id'] = ['Jadwal tanam harus dipilih.'];
        }
        if (!Security::isValidDate($data['estimated_harvest_date'])) {
            $errors['estimated_harvest_date'] = ['Tanggal perkiraan panen tidak valid.'];
        }
        if ($data['actual_harvest_date'] !== '' && !Security::isValidDate($data['actual_harvest_date'])) {
            $errors['actual_harvest_date'] = ['Tanggal aktual panen tidak valid.'];
        }

        return $errors;
    }

    /** Menyiapkan data untuk disimpan: tanggal aktual kosong menjadi NULL dan status dihitung otomatis. */
    private function prepare(array $data): array
    {
        $actual = $data['actual_harvest_date'] !== '' ? $data['actual_harvest_date'] : null;

        if ($actual !== null) {
            $status = 'Sudah dipanen';
        } elseif ($data['estimated_harvest_date'] <= date('Y-m-d')) {
            $status = 'Siap panen';
        } else {
            $status = 'Belum waktunya panen';
        }

        $data['actual_harvest_date'] = $actual;
        $data['notes'] = $data['notes'] !== '' ? $data['notes'] : null;
        $data['status'] = $status;

        return $data;
    }
}
