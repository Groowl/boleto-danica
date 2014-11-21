<?php

require 'Sebold/Locale/GeoIp/geoip.inc';

class Sebold_Locale_GeoIp {
    
    public function getCountryCodeByAddr($ip) {
        $gi = geoip_open(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'GeoIp' . DIRECTORY_SEPARATOR . 'GeoIP.dat',GEOIP_STANDARD);
        return geoip_country_code_by_addr($gi, $ip);
    }
    
}