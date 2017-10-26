<?php

use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Support\Str;


if (! function_exists('user')) {
    /**
     * Get the request's user.
     *
     * @return mixed
     */
    function user()
    {
        return request()->user();
    }
}

if (! function_exists('hashids')) {
    /**
     * Get the request's user.
     *
     * @return mixed
     */
    function hashids()
    {
        return new Hashids(config('app.key'));
    }
}

if (! function_exists('words')) {
    /**
     * Limit the number of words in a string.
     *
     * @param  string  $value
     * @param  int     $words
     * @param  string  $end
     * @return string
     */
    function words($value, $words = 20, $end = '...')
    {
        return \Illuminate\Support\Str::words($value, $words, $end);
    }
}

if (! function_exists('rand_token')) {
    /**
     * Generate a random token.
     *
     * @param  int  $length
     * @return string
     */
    function rand_token($length = 40)
    {
        return hash_hmac('sha256', Str::random($length), config('app.key'));
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance.
     *
     * @param  string|null  $time
     * @param  DateTimeZone|string|null $tz
     * @return \Carbon\Carbon
     */
    function carbon($time = null, $tz = null)
    {
        return new Carbon($time, $tz);
    }
}

if (! function_exists('url_ip')) {
    /**
     * Get the IP address of a given url.
     *
     * @param  string  $url
     * @return string|null
     */
    function url_ip($url)
    {
        $components = parse_url(trim($url));

        $path = explode('/', array_get($components, 'path', ''), 2);

        $host = array_get($components, 'host', head($path));

        $address = gethostbyname($host);

        return $address !== $host ? $address : null;
    }
}

if (! function_exists('str_datetime')) {
    /**
     * Format to date and time.
     *
     * @param  \Carbon\Carbon|null  $time
     * @return string
     */
    function str_datetime($time = null)
    {
        $time = $time ?: carbon();

        return $time->format('Y-m-d H:i');
    }
}

if (! function_exists('str_date')) {
    /**
     * Format to date.
     *
     * @param  \Carbon\Carbon|null  $time
     * @return string
     */
    function str_date($time = null)
    {
        $time = $time ?: carbon();

        return $time->format('Y-m-d');
    }
}

if (! function_exists('str_time')) {
    /**
     * Format to time.
     *
     * @param  \Carbon\Carbon|null  $time
     * @return string
     */
    function str_time($time = null)
    {
        $time = $time ?: carbon();

        return $time->format('H:i');
    }
}

if (! function_exists('make_tags')) {
    /**
     * Split a string to tags collection.
     *
     * @param  string  $value
     * @param  string  $delimeter
     * @return \Illuminate\Support\Collection
     */
    function make_tags($value, $delimeter = ',')
    {
        return collect(explode($delimeter, $value))
                    ->map(function ($item) {
                        return trim($item);
                    })
                    ->reject(function ($item) {
                        return $item === '';
                    })->unique()->values();
    }
}

if (! function_exists('words_count')) {
    /**
     * Returns the text's words count
     *
     * @param  string  $text
     * @return int
     */
    function words_count($text)
    {
        $text = strip_tags($text);
        $text = preg_replace('/(\n)+/u', " ", $text);
        $text = preg_replace('/[^\w\s\%]/u', "", $text);
        $text = preg_replace('/(\r)+/u', " ", $text);
        $text = preg_replace('/(\s)+/u', " ", $text);
        $text = preg_replace('/[ ]+/u', " ", $text);

        $text = str_replace(array(
            "\xC2\xAB", // « (U+00AB) in UTF-8
            "\xC2\xBB", // » (U+00BB) in UTF-8
            "\xE2\x80\x98", // ‘ (U+2018) in UTF-8
            "\xE2\x80\x99", // ’ (U+2019) in UTF-8
            "\xE2\x80\x9A", // ‚ (U+201A) in UTF-8
            "\xE2\x80\x9B", // ‛ (U+201B) in UTF-8
            "\xE2\x80\x9C", // “ (U+201C) in UTF-8
            "\xE2\x80\x9D", // ” (U+201D) in UTF-8
            "\xE2\x80\x9E", // „ (U+201E) in UTF-8
            "\xE2\x80\x9F", // ‟ (U+201F) in UTF-8
            "\xE2\x80\xB9", // ‹ (U+2039) in UTF-8
            "\xE2\x80\xBA", // › (U+203A) in UTF-8
            "\xE2\x80\x93", // – (U+2013) in UTF-8
            "\xE2\x80\x94", // — (U+2014) in UTF-8
            "\xE2\x80\xA6"), "", $text);
        $words = explode(' ', $text);
        $words = array_diff($words, ['']);

        return count($words);
    }
}