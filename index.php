<?php
//load the Yocto API
require_once('yocto_api.php');
YAPI::RegisterHub("callback");


//array of URL endpoints to recieve
$tentacles = [
    "http://10.0.1.55/trailsendmonitor/hopper/post",
    "http://10.0.1.55/YoctoCloud/index.php"
];

// string for debug feedback
$output = "";

// forward to endpoints
foreach($tentacles as $endpoint){
    YAPI::ForwardHTTPCallback($endpoint, $output);
    echo "Endpoint: " . $endpoint;
    echo "Result: " . $output;
    echo "---------------------------------------------------"
}

// push YoctoCloud data to online implementation with rsync
// -c, --checksum              skip based on checksum, not mod-time & size
// -r, --recursive             recurse into directories
// -a, --archive               archive mode; equals -rlptgoD (no -H,-A,-X)
// -z, --compress              compress file data during the transfer
// -P                          same as --partial --progress
// -v, --verbose               increase verbosity
echo "Rsync YC Data to cloud:";
exec("rsync -crahvzP /mnt/datadisk/YoctoCloud/data evan@evansharp.ca:/var/evansharp.ca/YoctoCloud/data",
        $output, $exit_code);

echo $output;

//release api resources
YAPI::FreeAPI();

?>
