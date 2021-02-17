<?php

define('SERVICE_NAME', 'MarcService');
define('LOG_FILE', __DIR__.'\\'.SERVICE_NAME.".log");
define('PHP_PATH','php');

if (!debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)) {
    if ($argv[1] == 'create') {
        win32_create_service([
            'service' => SERVICE_NAME,
            'display' => "Marc Demo PHP Windows Service",
            'description' => "How PHP scripts can be a Windows Service",
            'path' => PHP_PATH,
            'params' => __FILE__.' run',]);
    } else if ($argv[1] == 'start') {
        win32_start_service(SERVICE_NAME);
    } else if ($argv[1] == 'stop') {
        win32_stop_service(SERVICE_NAME);
    } else if ($argv[1] == 'run') {
        win32_start_service_ctrl_dispatcher(SERVICE_NAME);
        win32_set_service_status(WIN32_SERVICE_RUNNING);

        while(TRUE) {
            sleep(10);
            switch (win32_get_last_control_message()) {
                case WIN32_SERVICE_CONTROL_PAUSE:
                    continue 2;
                case WIN32_SERVICE_CONTROL_STOP:
                    file_put_contents(LOG_FILE, "Marc Service STOPPED.".PHP_EOL, FILE_APPEND);
                    break 2;
                default:
                    file_put_contents(LOG_FILE, "Marc Service SUCCESS. ", FILE_APPEND);
                    file_put_contents(LOG_FILE, "Control Message is " . win32_get_last_control_message() . PHP_EOL, FILE_APPEND);
                    break;
            }
        }
    } else if ($argv[1] == 'delete') {
        win32_delete_service(SERVICE_NAME);
    } else if ($argv[1] == 'pause') {
        win32_pause_service(SERVICE_NAME);
    } else if ($argv[1] == 'continue') {
        win32_continue_service(SERVICE_NAME);
    }
}
