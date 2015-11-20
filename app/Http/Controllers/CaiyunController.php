<?php
/*
**Author:tianling
**createTime:15/7/21 上午10:32
*/

namespace App\Http\Controllers;

use App\Weather;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

include_once('Curl.php');


class CaiyunController extends Controller{

    /*
     *获取坐标指定地点未来一小时内降雨情况
     * */
    public function CaiyunGet(Request $request){
        header('Access-Control-Allow-Origin:*');
        header("content-type:application/json");

        $lon = $request->input('lon');//经纬度数据需保留4位小数
        $lat = $request->input('lat');
        if(!is_numeric($lon) || !is_numeric($lat)){
            $this->throwError(501,'参数违法');
        }

        $url = 'http://caiyunapp.com/fcgi-bin/v1/api.py?lonlat='.$lon.','.$lat.'&format=json&product=minutes_prec&token=xeeL7DagnXhxhK7u';
        $data = $this->Curlget($url,'','get');
        $data = json_decode($data);
        $dataseries = $data->dataseries;
        $dataseries = array_slice($dataseries,0,12);

        $weatherData = array();
        $now = date('H',time());

        $temperature = rand(15,28);
        foreach($dataseries as $data){

            $weatherData[] = array(
                'time'=>$now.':00',
                'rain'=>$data,
                'temperature'=>$temperature
            );

            if(6<=$now&&$now<=14){
                $temperature +=2;
            }else{
                $temperature --;
            }

            if($now == 24){
                $now = 1;
            }else{
                $now++;
            }

        }

        $this->show($weatherData);

    }




    /*
     * 获取指定坐标地点当前天气情况
     * */
    public function CaiyunGetNow(Request $request){
        header('Access-Control-Allow-Origin:*');
        header("content-type:application/json");

        $lon = $request->input('lon');//经纬度数据需保留4位小数,例如：104.2569,30.6463
        $lat = $request->input('lat');
        if (!is_numeric($lon) || !is_numeric($lat)) {
            $this->throwError(501, '参数违法');
        }

//        $url_now = 'https://api.caiyunapp.com/v2/xeeL7DagnXhxhK7u/' . $lon . ',' . $lat . '/realtime.json';
//        $data_now = $this->Curlget($url_now, '', 'get');
//        $data_now = json_decode($data_now);
//        $result = $data_now->result;

        $weatherData = Weather::all();
        $weatherData = $weatherData->toArray();

        $key = rand(0,count($weatherData)-1);
        unset($weatherData[$key]['id']);
        $caiyunNow = $weatherData[$key];

//        $caiyunNow = array(
//            'temperature'=>$result->temperature,
//            'humidity'=>$result->humidity,
//            'precipitation'=>$result->precipitation->local->intensity,
//            'wind'=>array(
//                'direction'=>$result->wind->direction,
//                'speed'=>$result->wind->speed
//            )
//        );

        $this->show($caiyunNow);



    }

    private function Curlget($url,$data='',$method){
        //$headers = $this->headers;

        if(!empty($url)){
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);

            if($method == 'get'){
                curl_setopt($ch,CURLOPT_HTTPGET,1);
            }else{
                curl_setopt($ch,CURLOPT_POST,1);
                $data = http_build_query($data);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            }

            //curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);

            ob_start();
            curl_exec($ch);

            $result = ob_get_contents() ;
            ob_end_clean();
            curl_close($ch);

            return $result;

        }
    }
}