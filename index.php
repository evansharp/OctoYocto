<?php

//load the Yocto API


//array of URL endpoints to recieve
$tentacles = [
    "10.0.1.55/trailsendmonitor/hopper/post",
    "10.0.1.5/YoctoCloud/index.php"
];



//forward to endpoints
foreach($tentacles as $endpoint){

}

//push YoctoCloud data to online implementation with rsync
// -c, --checksum              skip based on checksum, not mod-time & size
// -r, --recursive             recurse into directories
// -a, --archive               archive mode; equals -rlptgoD (no -H,-A,-X)
// -z, --compress              compress file data during the transfer
// -P                          same as --partial --progress
// -v, --verbose               increase verbosity

exec("rsync -crahvzP /path/in/local/files/foldertocopy remoteuser@remoteserveraddress:/path/in/remote/destinationfolder/",
        $output, $exit_code);


//release api


?>
