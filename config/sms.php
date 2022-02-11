<?php

/*
 * This file is part of the Kiswah SMS Integration.
 *
 * (c) Gidicodes <gidicodes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /**
     * API Key From Tookan Dashboard
     *
     */
    'key' => env('SMS_API_KEY', ''),

    /**
     * API Url to tookan
     *
     */
    'base_url' => env('SMS_API_URL', "https://api.tookanapp.com/v2/"),


];
