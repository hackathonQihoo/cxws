<?php
/*
**Author:tianling
**createTime:15/11/21 上午1:39
*/
namespace App\Http\Controllers;

use App\UserLocus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Redis;

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
        
    }
}
