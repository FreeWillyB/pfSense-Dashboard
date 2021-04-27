#!/usr/local/bin/php-cgi -f
<?php

require_once("gwlb.inc");

$gw_array = return_gateways_array();
$gw_statuses = return_gateways_status(true);

foreach ($gw_array as $gw => $gateway) {

    $gw_name = $gw_statuses[$gw]["name"];
    $monitor_ip = $gw_statuses[$gw]["monitorip"];
    $gateway_ip = $gw_statuses[$gw]["srcip"];
    $rtt = $gw_statuses[$gw]["delay"];
    $rttsd = $gw_statuses[$gw]["stddev"];
    $loss = $gw_statuses[$gw]["loss"];
    $status = $gw_statuses[$gw]["status"];
    
    $interface = $gateway["interface"];
    $interface_desc = $gateway["friendlyifdescr"];
    $interface_friendly = $gateway["friendlyiface"]; // e.g. wan, lan, opt1, etc... 
    $gwdescr = $gateway["descr"]; // NOT USED

    
    if (!isset($gateway['isdefaultgw'])) {
        $defaultgw = "1";
    } else {
        $defaultgw = "0";
    }
        
    if ($gw_statuses[$gw]) {
        if (isset($gateway['monitor_disable'])) {
            $monitor = "Unmonitored";
            $delay = "Pending";
            $stdev = "Pending";
            $loss = "Pending";
        }
    }

    // Some earlier versions of pfSense do not return substatus
    if ($gw_statuses[$gw]["substatus"]) {
        $substatus = $gw_statuses[$gw]["substatus"];
    } else {
        $substatus = "N/A";
    }

    printf("gateways,gateway_name=%s monitor_ip=\"%s\",gateway_ip=\"%s\",rtt=%s,rttsd=%s,loss=%si,status=\"%s\",substatus=\"%s\",interface=\"%s\",interface_desc=\"%s\",interface_friendly=\"%s\",default_gw=\"%s\"\n",
        $gw_name,
        $monitor_ip,
        $gateway_ip,
        floatval($rtt),
        floatval($rttsd),
        floatval($loss),
        $status,
        $substatus,
        $interface,
        $interface_desc,
        $interface_friendly,
        $defaultgw
    );
}
?>
