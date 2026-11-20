<?php
// Cadangan bila .htaccess root tidak aktif: arahkan ke folder public.
$dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$dir = implode('/', array_map('rawurlencode', explode('/', $dir)));
header('Location: ' . $dir . '/public/');
exit;
