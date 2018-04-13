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
    echo "---------------------------------------------------";
}

// push YoctoCloud data to online implementation with rsync
// -c, --checksum              skip based on checksum, not mod-time & size
// -r, --recursive             recurse into directories
// -a, --archive               archive mode; equals -rlptgoD (no -H,-A,-X)
// -z, --compress              compress file data during the transfer
// -P                          same as --partial --progress
// -v, --verbose               increase verbosity
echo "Rsync YC Data to cloud:";
$output = [];
exec("rsync -crahvzP -e \"ssh -i /home/pi/.ssh/monitor\" /mnt/datadisk/YoctoCloud/data/ evan@evansharp.ca:/var/www/evansharp.ca/YoctoCloud/data",
        $output, $exit_code);
if( not_empty($output) ){
    foreach($output as $line){
        echo $line;
    }
}
echo " ";
switch( $exit_code ){
    case 0:
        echo "Rsync completed successfully. (code 0)";
        break;
    case 1:
        echo "Syntax error in rsync command. (code 1)";
        break;
    case 255:
        echo "Probably an SSH error. (code 255)";
        break;
    default:
        echo "Something weird happened. Code " . $exit_code;
}

//release api resources
YAPI::FreeAPI();

?>
