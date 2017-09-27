#!/usr/bin/php
<?php
/*
 * This tool runs latency test against all predefined servers
 * and displays results without any additional action.
 *
 * Copyright (c) 2008-2014 by Afilias Technologies Limited (dotMobi). All rights reserved.
 */

require_once dirname(__FILE__).'../../Api/Client.php';

print "\nDeviceAtlas Cloud endponit latency checker\n";
print "Running tests, this may take a while...\n";

$best     = "\nNo good server found!";
$best_avg = -1;

foreach (DeviceAtlasCloudClient::getServersLatencies() as $server) {
    $name = $server['host'];
    if ($server['port'] != '80') {
        $name .= ':'.$server['port'];
    }
    print "\n    $name\n";

    if ($server['avg'] === -1) {
        print "        (Couldn't connect to host)\n";
    } else {

        foreach ($server['latencies'] as $latency) {
            print $latency === -1? "        n/a\n": '        '.round($latency, 4)."ms\n";
        }

        print '        * average: '.round($server['avg'], 4)."ms\n";
        if ($best_avg === -1 || $best_avg > $server['avg']) {
            $best_avg = $server['avg'];
            $best     = "\nBest server >> $name <<";
        }
    }
}

print $best;
print "\nPlease see https://deviceatlas.com/resources/cloud-service-end-points for more information.\n";
