<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        date_default_timezone_set('PRC');
    }

    /*
    * 抛错函数
    **/
    protected  function throwERROR($code,$msg){
        echo json_encode(array(
            'status'=>$code,
            'msg'=>$msg,
            'data'=>''
        ));

        exit();
    }



    /*
     * 输出函数
     **/
    protected  function show($data,$msg=''){
        echo json_encode(array(
            'status'=>200,
            'msg'=>$msg,
            'data'=>$data
        ));

        exit();
    }
}
