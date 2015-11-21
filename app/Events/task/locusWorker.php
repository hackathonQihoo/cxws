<?php
/*
**Author:tianling
**createTime:15/11/21 下午6:18
*/
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Redis;

$uniKey = '2cb6418b1e6182093385c8150891c46e';

$redis = Redis::connection();
$locusData = $redis->get($uniKey);

echo $locusData;
