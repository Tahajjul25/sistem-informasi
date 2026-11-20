<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\Crop;

class CropController extends BaseController
{
    public function index(): void
    {
        Auth::requireLogin();
        $search = trim((string) ($_GET['search'] ?? ''));
        $crops = Crop::all($search);
        $this->view('crops/index', ['crops' => $crops, 'search' => $search], 'Manajemen Tanaman');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->requirePost();
        $this->requireCsrf();

        $data = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'age_days' => trim((string) ($_POST['age_days'] ?? '')),
            'water_requirement' => trim((string) ($_POST['water_requirement'] ?? '')),
        ];

        $errors = [];
        if ($data['name'] === '') $errors['name'] = ['Nama tanaman wajib diisi.'];
        if ($data['age_days'] === '' || !ctype_digit((string) $data['age_days'])) $errors['age_days'] = ['Umur tanaman harus angka hari.'];
        if ($data['water_requirement'] === '' || !is_numeric($data['water_requirement']) || (float) $data['water_requirement'] < 0) $errors['water_requirement'] = ['Kebutuhan air harus angka non-negatif.'];

        if ($errors) {
            $this->view('crops/form', ['errors' => $errors, 'crop' => $data], 'Tambah Tanaman');
            return;
        }

        Crop::create($data);
        $this->redirect('/crops');
    }

    public function edit(): void
    {
        Auth::requireLogin();
        $id = (int) ($_POST['id'] ?? 0);
        $crop = Crop::find($id);
        if (!$crop) {
            $this->redirect('/crops');
        }

        $this->requirePost();
        $this->requireCsrf();
        $data = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'age_days' => trim((string) ($_POST['age_days'] ?? '')),
            'water_requirement' => trim((string) ($_POST['water_requirement'] ?? '')),
        ];

        $errors = [];
        if ($data['name'] === '') $errors['name'] = ['Nama tanaman wajib diisi.'];
        if ($data['age_days'] === '' || !ctype_digit((string) $data['age_days'])) $errors['age_days'] = ['Umur tanaman harus angka hari.'];
        if ($data['water_requirement'] === '' || !is_numeric($data['water_requirement']) || (float) $data['water_requirement'] < 0) $errors['water_requirement'] = ['Kebutuhan air harus angka non-negatif.'];

        if ($errors) {
            $this->view('crops/form', ['errors' => $errors, 'crop' => ['id' => $id] + $data], 'Edit Tanaman');
            return;
        }

        Crop::update($id, $data);
        $this->redirect('/crops');
    }

    public function delete(): void
    {
        Auth::requireLogin();
        $this->requirePost();
        $this->requireCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Crop::delete($id);
        }

        $this->redirect('/crops');
    }

    public function form(): void
    {
        Auth::requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $crop = $id > 0 ? Crop::find($id) : null;
        $this->view('crops/form', ['crop' => $crop], $crop ? 'Edit Tanaman' : 'Tambah Tanaman');
    }
}
