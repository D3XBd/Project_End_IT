<?php
if (!file_exists("exchange_state.txt")) {
    echo "IDLE";
    exit;
}

$status = trim(file_get_contents("exchange_state.txt"));
echo $status === "" ? "IDLE" : $status;