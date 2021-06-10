<?php
/**
 * File: payjs.php
 * Functionality: payjs
 * Author: iCodex
 * Date: 2021年6月10日 13:13:09
 */

namespace Pay\payjs;

class payjs
{
    public function post($data, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $rst = curl_exec($ch);
        curl_close($ch);
        return $rst;
    }

    public function sign(array $attributes, $key)
    {
        ksort($attributes);
        $sign = strtoupper(md5(urldecode(http_build_query($attributes)) . '&key=' . $key));
        return $sign;
    }
}