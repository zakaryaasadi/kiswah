<?php

/*
 * This file is part of the Kiswah Tookan Integration.
 *
 * (c) Gidicodes <gidicodes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /**
     * API Key From Tookan Dashboard
     * kiswah 5b636183f245564a5e44727a5d10224418e7c3ff2bd47b3a551b
     * suhaib 5b6b6882f84051141a49646d464525401ee7c6f822d97f365d1804
     */
    'key' => env('TOOKAN_API_KEY', '5b636183f245564a5e44727a5d10224418e7c3ff2bd47b3a551b'),

    /**
     * Access From Tookan Dashboard
     *562668cca9044d031c1527301416234f1decc4fa2c
     * suhaib 73af46ca3ea43835eba690d2a22b1d29
     */
    'access' => env('TOOKAN_ACCESS_KEY', '562668cca9044d031c1527301416234f1decc4fa2c'),

    /**
     * User ID From Tookan Dashboard
     *90162
     * suhaib: 988782
     */
    'user' => env('TOOKAN_USER_ID', '90162'),

    /**
     * User ID From Tookan Dashboard
     * kiswah: 197058
     * suhaib: 988782
     */
    'fleet_id' => env('TOOKAN_FLEET_ID', '197058'),

    /**
     * API Url to tookan
     *
     */
    'base_url' => env('TOOKAN_API_URL', "https://api.tookanapp.com/v2/"),


];
