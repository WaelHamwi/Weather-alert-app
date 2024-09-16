<?php
// check-class.php

$loader = require __DIR__.'/vendor/autoload.php';
$files = $loader->getClassMap();
print_r($files['App\Http\Livewire\ManageLocationsComponent'] ?? 'Class not found');
