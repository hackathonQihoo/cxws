<?php
/*
**Author:tianling
**createTime:15/11/21 上午1:39
*/
namespace App\Http\Controllers;

use App\Plans;
use App\UserLocus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Redis;

define('EARTH_RADIUS', 6378.137);//地球半径
define('PI', 3.1415926);

class LocusController extends Controller{

    /*
     * 获取用户实时坐标
     * */
    public function LocusSet(Request $request){
        $lon = $request->input('lon');
        $lat = $request->input('lat');
        $uid = $request->input('uid');

        if($lon == '' || $lat == '' || !is_numeric($uid)){
            $this->throwERROR(501,'参数错误');
        }

        $uniKey = md5(date('Y-m-d',time()).$uid);
        $redis = Redis::connection();

        $locusData = $redis->get($uniKey);


        if($locusData == ''){
            $locusArray[] = array(
                date('Y-m-d H:i:s',time())=>array(
                    'lon'=>$lon,
                    'lat'=>$lat
                )
            );
            $locusArray = json_encode($locusArray);

            $set = $redis->set($uniKey,$locusArray);

            $locusModel = new UserLocus();
            $locusModel->uid = $uid;
            $locusModel->uniKey = $uniKey;
            $locusModel->date = date('Y-m-d',time());
            if(!($locusModel->save())){
                $this->throwERROR(502,'DB error');
            }


        }else{
            $locusArray = json_decode($locusData,true);

            $locusArray[] = array(
                date('Y-m-d H:i:s',time())=>array(
                    'lon'=>$lon,
                    'lat'=>$lat
                )
            );

            $locusArray = json_encode($locusArray);

            $set = $redis->set($uniKey,$locusArray);

        }

        if($set){
            echo json_encode(array(
                'status'=>200,
                'msg'=>'set success'
            ));

            exit();
        }

        $this->throwERROR(500,'redis-error');


    }



    /*
     * 获取用户实时坐标
     * */
    public function LocusGet(Request $request){
        $uid = $request->input('uid');

        if(!is_numeric($uid)){
            $this->throwERROR(501,'参数错误');
        }
        $uniKey = md5(date('Y-m-d',time()).$uid);

        $redis = Redis::connection();
        $locusData = $redis->get($uniKey);

        if($locusData == ''){
            $this->throwERROR(400,'暂无该用户数据');
        }

        $locusArray = json_decode($locusData,true);
        $locusNow = $locusArray[count($locusArray)-1];
        $lat = '';
        $lon = '';
        foreach($locusNow as $locus){
            $lat = $locus['lat'];
            $lon = $locus['lon'];
        }

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>array(
                'lat'=>$lat,
                'lon'=>$lon
            )
        ));

    }


    /*
     * 出行状态生成
     * */
    public function planSet($uid,$lon,$lat){
        $plan = new Plans();

        $plan->uid = $uid;
        $plan->start_longitude = $lon;
        $plan->start_latitude = $lat;
        $plan->has_alert = 0;
    }


    /*
     * 计算坐标间距离
     * */
    private function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * PI / 180.0;
        $radLat2 = $lat2 * PI / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * EARTH_RADIUS;
        $s = round($s * 1000);
        if ($len_type > 1)
        {
            $s /= 1000;
        }
        return round($s, $decimal);
    }


    /*
     *
     * */

}
