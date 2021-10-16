<?php
$ret = startJsonResponse();

$json = InfoStore::get("ServerStats");
$ret->server = json_decode($json);

endJsonResponse($response, $ret, true, "Called Ping API");
