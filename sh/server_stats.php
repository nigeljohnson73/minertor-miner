<?php
include_once (dirname ( __FILE__ ) . "/../functions.php");

$stats = storeServerStats ();
unset ( $stats->vpn_expected );
unset ( $stats->vpn_alarm );
$stats->datetime = timestampFormat ( $stats->datetime );

print_r ( $stats );
?>