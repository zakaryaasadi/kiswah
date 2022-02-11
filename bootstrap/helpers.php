<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: *");
/**
 * Gets authenticated user
 * @return \Illuminate\Contracts\Auth\Authenticatable|User
 */
function getUser()
{
    return auth()->user();
}

function linkActive($route)
{
    return request()->url() === url($route) ? 'active' : '';
}

function array_random($arr, $num = 1)
{
    shuffle($arr);

    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }
    return $num == 1 ? $r[0] : $r;
}

function nf($num = 0, $d = 2)
{
    return number_format($num, $d);
}

function slugify($string, $separator = '-')
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array('&' => 'and', "'" => '');
    $string = mb_strtolower(trim($string), 'UTF-8');
    $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
    $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}

function showTab($tab = 'sliders')
{
    if ($tab === 'sliders') {
        if (!session()->has('tab') || session()->get('tab') === 'sliders') {
            return 'active';
        }
        return '';
    }
    if (session()->has('tab') || session()->get('tab') === 'slider') {
        return 'active';
    }
    return '';
}

function setting()
{
    $settings = \App\Settings::first();
    return optional($settings);
}


function parse_yturl($url)
{
    $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    preg_match($pattern, $url, $matches);
    return (isset($matches[1])) ? $matches[1] : false;
}

function getYouTubeVideoImage($url)
{
//    $link = $pageVideUrl;
//    $video_id = explode("?v=", $link);
//    if (!isset($video_id[1])) {
//        $video_id = explode("youtu.be/", $link);
//    }
//    $youtubeID = $video_id[1];
//    if (empty($video_id[1])) $video_id = explode("/v/", $link);
//    $video_id = explode("&", $video_id[1]);
//    '%(?:youtube(?:-nocookie)?\.com/(?:[\w\-?&!#=,;]+/[\w\-?&!#=/,;]+/|(?:v|e(?:mbed)?)/|[\w\-?&!#=,;]*[?&]v=)|youtu\.be/)([\w-]{11})(?:[^\w-]|\Z)%i'


    $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
    preg_match($pattern, $url, $matches);
    if (isset($matches[1])) {
        $youtubeVideoID = $matches[1];// [0];
        if ($youtubeVideoID) {
            return 'https://img.youtube.com/vi/' . $youtubeVideoID . '/mqdefault.jpg';
        }

    }


    return '';
}


/**
 * @param $file
 * @param string $path
 * @return string
 */
function moveFile($file, $path = 'uploads'): string
{
    try {
        if (!$file || !$file->isValid()) {
            return asset('6.jpg');
        }
        $saved = $file->store('public/' . $path, config('filesystems.default'));
        return asset(Storage::url(substr($saved, 7)));
//        $otherMethod = $file->storeOnCloudinary('kiswah/' . $path);
//        $response = $otherMethod->getSecurePath();
//        $s = cloudinary()->upload($file->getRealPath(), ['folder' => "kiswah/$path", 'use_filename' => true]);
//        return $s->getSecurePath();
    } catch (\Exception $e) {
        return asset('6.jpg');
    }
}

function isLocalhost($whitelist = ['127.0.0.1', '::1', 'localhost', ':8000'])
{
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

/**
 * If the given value is not an array and not null, wrap it in one.
 *
 * @param mixed $value
 * @return array
 */
function array_wrap($value): array
{
    if (is_null($value)) {
        return [];
    }
    return is_array($value) ? $value : [$value];
}

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
    $array = array();
    $interval = new DateInterval('P1D');
    try {
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    } catch (Exception $e) {
        $period = [];
    }
    foreach ($period as $date) {
        $array[] = $format ? $date->format($format) : new \Carbon\Carbon($date);
    }
    return $array;
}

