<?php

return [
    'db' => 'gandalf_test',
    'workers-dir' => __DIR__ . '/app/Drunken',
    'tasks-clear-period' => '-24 hours',
    'log_path' => __DIR__ . '/storage/logs/drunken.log',
];
