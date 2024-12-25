<?php
// Waktu sekarang
$current_time = time();

// Waktu pukul 8 pagi hari ini
$today_8am = strtotime("today 8:00");

// Jika sekarang masih sebelum pukul 8 pagi, ambil pukul 8 pagi kemarin
if ($current_time < $today_8am) {
    $last_8am = strtotime("yesterday 8:00");
} else {
    $last_8am = $today_8am;
}

// Menampilkan detik waktu terakhir pukul 8 pagi
echo "Detik terakhir pukul 8 pagi: " . $last_8am . " " . date("Y-m-d H:i:s", $last_8am);
