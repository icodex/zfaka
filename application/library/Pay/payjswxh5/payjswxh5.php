<?php
/**
 * File: payjswxh5.php
 * Functionality: payjs
 * Author: iCodex
 * Date: 2021年6月10日 13:13:09
 */

namespace Pay\payjswxh5;

use \Pay\payjs;
use \Pay\notify;

class payjswxh5
{
    private $paymethod = "payjswxh5";

    private $payjs_native_url = 'https://payjs.cn/api/mweb?';

    //处理请求
    public function pay($payconfig, $params)
    {
        $payjs = new \Pay\payjs\payjs();

        clearstatcache();
        $data         = [
            'mchid'        => $payconfig['app_id'],
            'body'         => $params['orderid'],
            'out_trade_no' => $params['orderid'],
            'total_fee'    => $params['money'] * 100,
            'notify_url'   => $params['weburl'] . '/product/notify/?paymethod='.$this->paymethod,
        ];
        $this->key    = $payconfig['app_secret'];
        $data['sign'] = $payjs->sign($data, $this->key);
        
        $url = $this->payjs_native_url . http_build_query($data);

        $result = $payjs->post($data, $this->payjs_native_url);
        $result = json_decode($result, true);

        $result_params = [
            'type'      => 1,
            'subjump'   => 0,
            'paymethod' => $this->paymethod,
            'payname'   => $payconfig['payname'],
            'overtime'  => $payconfig['overtime'],
            'money'     => $params['money'],
            'url'       => $result['h5_url']
        ];

        return ['code' => 1, 'msg' => 'success', 'data' => $result_params];
    }

    public function notify()
    {
        $data = $_POST;

        if ($data['return_code'] == 1) {
            $config = [
                'paymethod' => $this->paymethod,
                'tradeid'   => $data['payjs_order_id'],
                'paymoney'  => $data['total_fee'] / 100,
                'orderid'   => $data['out_trade_no'],
            ];
            $notify = new \Pay\notify();
            $notify->run($config);
        }

        return 'success';
    }
}