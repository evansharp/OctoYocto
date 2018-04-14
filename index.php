<?php
//high error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    echo "\n";
    echo "Endpoint: " . $endpoint . "\n";
    echo "Result: " . $output . "\n";
    echo "---------------------------------------------------\n";
}

// push YoctoCloud data to online implementation with rsync
// -c, --checksum              skip based on checksum, not mod-time & size
// -r, --recursive             recurse into directories
// -a, --archive               archive mode; equals -rlptgoD (no -H,-A,-X)
// -z, --compress              compress file data during the transfer
// -P                          same as --partial --progress
// -v, --verbose               increase verbosity
echo "Rsync YC Data to cloud:\n";
$output = [];
exec('rsync -crahvzP -e "ssh -i /var/www/.ssh/monitor" /mnt/datadisk/yoctocloud/data/ evan@evansharp.ca:/var/www/evansharp.ca/YoctoCloud/data',
        $output, $exit_code);
if( !empty($output) ){
    foreach($output as $line){
        echo $line . "\n";
    }
}
echo "\n";
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
