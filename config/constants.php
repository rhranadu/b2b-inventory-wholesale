<?php
//file : app/config/constants.php

return [
    'MOBILE_DIGIT_LIMIT' => '11',
    'MOBILE_DIGIT_PATTERN' => '/^+88(0\d|1\d)\d{11}$/',
    'EMAIL_PATTERN' => '/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
];
// return [
//     'MOBILE_DIGIT_LIMIT' => '11',
//     'MOBILE_DIGIT_PATTERN' => '/^(?:\+?88)?01[13-9]\d{8}$/',
//     'EMAIL_PATTERN' => '^[^\s@]+@[^\s@]+\.[^\s@]+$',
// ];
