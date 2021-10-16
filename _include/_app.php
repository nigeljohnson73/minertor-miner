<?php

function bitCompare($str, $in, $now, $prev) {
    // $bp = 24; // bit padding
    $has_str = "PAST";
    $now_str = "NOW";
    $no_str = "NEVER";
    $ret = $no_str;

    // $hi = str_pad ( decbin ( $in ), $bp, "0", STR_PAD_LEFT );
    // $hn = str_pad ( decbin ( $now ), $bp, "0", STR_PAD_LEFT );
    // $hp = str_pad ( decbin ( $prev ), $bp, "0", STR_PAD_LEFT );

    // echo $str . ": ";
    if ($in & $now) {
        $ret = $now_str;
    } elseif ($in & $prev) {
        $ret = $has_str;
    }
    // echo $ret;
    // echo "\n";

    // echo "In (in): " . $hi . " (" . dechex ( $in ) . ")\n";
    // echo "Now (hn): " . $hn . " (" . (($in & $now) ? ("set") : ("--")) . ")\n";
    // echo "Prev(hp): " . $hp . " (" . (($in & $prev) ? ("set") : ("--")) . ")\n";
    // echo "----\n";

    return $ret;
}

function getVmStats() {
    global $expect_vpn;

    $vpn = exec("/usr/bin/expressvpn status | grep 'Connected to'");
    @list($c, $vpn) = explode("Connected to ", trim($vpn));

    $hdd = exec("df -k | grep '^\/dev\/root'");
    // echo "free: '$free'\n";
    $bits = explode(" ", preg_replace('/\s+/', " ", trim($hdd)));
    // echo "bits: " . ob_print_r ( $bits ) . "\n";

    $keys = [];
    $keys[] = "fs";
    $keys[] = "blocks";
    $keys[] = "used";
    $keys[] = "available";
    $keys[] = "use";
    $keys[] = "mount";

    $hdd = new StdClass();
    foreach ($bits as $k => $v) {
        $key = $keys[$k];
        $hdd->$key = $v;
    }
    // echo "HDD: ".ob_print_r($hdd)."\n";

    $free = exec("free 2> /dev/null | grep '^Mem:'");
    // echo "free: '$free'\n";
    $bits = explode(" ", preg_replace('/\s+/', " ", trim($free)));
    // echo "bits: " . ob_print_r ( $bits ) . "\n";

    $keys = [];
    $keys[] = "dummy";
    $keys[] = "total";
    $keys[] = "used";
    $keys[] = "free";
    $keys[] = "shared";
    $keys[] = "cache";
    $keys[] = "available";

    $free = new StdClass();
    foreach ($bits as $k => $v) {
        $key = $keys[$k];
        $free->$key = $v;
    }

    $vmstat = exec("vmstat -a 2> /dev/null");
    // echo "vmstat: '$vmstat'\n";
    $bits = explode(" ", preg_replace('/\s+/', " ", trim($vmstat)));
    // echo "bits: " . ob_print_r ( $bits ) . "\n";

    $keys = [];
    $keys[] = "procs_r";
    $keys[] = "procs_b";
    $keys[] = "mem_swapd";
    $keys[] = "mem_free";
    $keys[] = "mem_buff";
    $keys[] = "mem_cache";
    $keys[] = "swap_si";
    $keys[] = "swap_so";
    $keys[] = "io_bi";
    $keys[] = "io_bo";
    $keys[] = "sys_in";
    $keys[] = "sys_cs";
    $keys[] = "cpu_us";
    $keys[] = "cpu_sy";
    $keys[] = "cpu_id";
    $keys[] = "cpu_wa";
    $keys[] = "cpu_st";

    $vmstat = new StdClass();
    foreach ($bits as $k => $v) {
        $key = $keys[$k];
        $vmstat->$key = $v;
        // echo "(".$k.") '".$key."' - '" .$v."'\n";
    }

    $throt = exec("vcgencmd get_throttled 2> /dev/null");
    $throt = @explode("0x", $throt)[1];
    $throt = hexdec($throt);

    $temp = exec("vcgencmd measure_temp 2> /dev/null");
    $temp = @explode("=", $temp)[1];
    $temp = @explode("'", $temp)[0];

    $freq_cpu = exec("vcgencmd measure_clock arm 2> /dev/null");
    $freq_cpu = @explode("=", $freq_cpu)[1];
    $freq_cpu = @explode("'", $freq_cpu)[0];
    $freq_cpu = ($freq_cpu != "") ? (@round($freq_cpu / 1000000)) : ("");

    $freq_gpu = exec("vcgencmd measure_clock core 2> /dev/null");
    $freq_gpu = @explode("=", $freq_gpu)[1];
    $freq_gpu = @explode("'", $freq_gpu)[0];
    $freq_gpu = ($freq_gpu != "") ? (@round($freq_gpu / 1000000)) : ("");

    $ret = new StdClass();
    $ret->vpn_connection = strlen($vpn) ? $vpn : ("Not Connected");
    $ret->vpn_expected = $expect_vpn;
    $ret->vpn_alarm = strlen($vpn) ? "NEVER" : ($expect_vpn ? "NOW" : "DISABLED");
    $ret->sd_total = @$hdd->blocks;
    $ret->sd_avail = @$hdd->available;
    $ret->sd_load = (@$hdd->blocks > 0) ? (round(100 * $hdd->used / $hdd->blocks, 3)) : ("");
    $ret->cpu_wait = @$vmstat->cpu_wa;
    $ret->cpu_load = (@$vmstat->cpu_id != "") ? (100 - @$vmstat->cpu_id) : ("");
    $ret->mem_total = @$free->total;
    $ret->mem_avail = @$free->available;
    $ret->mem_load = (@$free->total > 0) ? (round(100 * ($free->total - $free->available) / $free->total, 3)) : ("");
    $ret->temperature = $temp;

    $ret->under_voltage = ($throt !== "") ? (bitCompare("UNDERVOLT", $throt, (1 << 0), (1 << 16))) : ("");
    $ret->frequency_capped = ($throt !== "") ? (bitCompare("FREQCAP", $throt, (1 << 1), (1 << 17))) : ("");
    $ret->throttled = ($throt !== "") ? (bitCompare("THROTTLED", $throt, (1 << 2), (1 << 18))) : ("");
    $ret->soft_temperature_limited = ($throt !== "") ? (bitCompare("TEMPLIMIT", $throt, (1 << 3), (1 << 19))) : ("");
    $ret->frequency_cpu = $freq_cpu;
    $ret->frequency_gpu = $freq_gpu;
    $ret->temperature_unit = "°C";
    $ret->frequency_unit = "MHz";

    $ret = (array) $ret;
    ksort($ret);
    $ret = (object) $ret;

    return $ret;
}

function storeServerStats() {
    $pt = new ProcessTimer();
    $stats = getVmStats();
    $stats->call_duration = $pt->duration();
    $stats->datetime = timestampNow();

    InfoStore::set("ServerStats", json_encode($stats));
    return $stats;
}

function tick() {
    echo "APP Tick\n";
    storeServerStats();
}
