<?php
date_default_timezone_set("Asia/Kolkata");

$tokenExpiration = date('Y-m-d H:i:s', time()+3600);

print $tokenExpiration;