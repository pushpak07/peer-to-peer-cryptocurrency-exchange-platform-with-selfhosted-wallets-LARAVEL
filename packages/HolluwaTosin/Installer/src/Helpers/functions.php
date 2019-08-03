<?php

if(!function_exists('getTimeZones')){
    function getTimeZones(){
        $identifier = \DateTimeZone::ALL;

        $tz = \DateTimeZone::listIdentifiers(
            $identifier
        );

        return array_combine($tz, $tz);
    }
}
