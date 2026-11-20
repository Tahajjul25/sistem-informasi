<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Security;
use App\Models\RainfallData;

class RainfallController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();

        $from = $this->dateFromQuery('date_from');
        $to = $this->dateFromQuery('date_to');

        $this->view('rainfall/index', [
            'items'     => RainfallData::getAll($from, $to),
            'date_from' => $from,
            'date_to'   => $to,
        ], 'Curah Hujan');
    }

    public function form(): void
    {
        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $item = $id > 0 ? RainfallData::find($id) : null;

        $this->view('rainfall/form', [
            'item'   => $item,
            'errors' => [],
        ], $item ? 'Edit Curah Hujan' : 'Tambah Curah Hujan');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('rainfall/form', ['item' => $data, 'errors' => $errors], 'Tambah Curah Hujan');
            return;
        }

        RainfallData::create($data);
        $this->redirect('/rainfall');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0 || !RainfallData::find($id)) {
            $this->redirect('/rainfall');
        }

        $data = $this->collect();
        $errors = $this->validate($data);

        if ($errors) {
            $this->view('rainfall/form', ['item' => ['id' => $id] + $data, 'errors' => $errors], 'Edit Curah Hujan');
            return;
        }

        RainfallData::update($id, $data);
        $this->redirect('/rainfall');
    }

    public function delete(): void
    {
        Auth::requireLogin();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            RainfallData::delete($id);
        }

        $this->redirect('/rainfall');
    }

    private function collect(): array
    {
        return [
            'measurement_date' => trim((string) ($_POST['measurement_date'] ?? '')),
            'amount_mm'        => trim((string) ($_POST['amount_mm'] ?? '')),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (!Security::isValidDate($data['measurement_date'])) {
            $errors['measurement_date'] = ['Tanggal pengukuran tidak valid.'];
        }
        if (!is_numeric($data['amount_mm']) || (float) $data['amount_mm'] < 0) {
            $errors['amount_mm'] = ['Curah hujan harus berupa angka 0 atau lebih.'];
        }

        return $errors;
    }
}
