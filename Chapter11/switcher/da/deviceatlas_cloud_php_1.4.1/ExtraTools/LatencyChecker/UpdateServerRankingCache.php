#!/usr/bin/php
<?php
/*
 * When AUTO_RANKING is set on you can use this tool to avoid unnecessary
 * client latencies. All you need to do is to run this script in regular
 * intervals which are lower than AUTO_SERVER_RANKING_LIFETIME.
 *
 * Copyright (c) 2008-2014 by Afilias Technologies Limited (dotMobi). All rights reserved.
 */

require_once dirname(__FILE__).'../../Api/Client.php';

print "\nDeviceAtlas Cloud server ranking cache updater\n";
print "Running tests, this may take a while...\n";

// Rank servers and cache ranked list
try {
    $result = DeviceAtlasCloudClient::rankServers();
} catch (Exception $x) {
    $result = null;
}

print "\n".($result?
    'Servers ranked and cached successfully.':
    'Failed to rank servers or update cache files!')."\n";
