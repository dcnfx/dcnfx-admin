<?php
/**
 * 判断是否为不可操作id
 *
 * @param	number	$id	参数id
 * @param	string	$configName	配置名
 * @param	bool  $emptyRetValue
 * @param	string	$split 分隔符
 * @return	bool
 */
if (!function_exists('is_config_id')) {
    function is_config_id($id, $configName, $emptyRetValue = false, $split = ",")
    {
        if (empty($configName)) return $emptyRetValue;
        $str = trim(config($configName, ""));
        if (empty($str)) return $emptyRetValue;
        $ids = explode($split, $str);
        return in_array($id, $ids);
    }
}

function vn_time($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}
function Ymd_time($timestamp)
{
    return date('Ymd', $timestamp);
}

function formatTime($timestamp,$format='Y-m-d H:i:s')
{
    return date($format, $timestamp);
}
function DeleteHtml($str)  {
    $str = trim($str);
    $str = str_replace("\n","",$str);
//    $str = strip_tags($str,"");
//    $str = ereg_replace("\t","",$str);
//    $str = ereg_replace("\r\n","",$str);
//    $str = ereg_replace("\r","",$str);
//    $str = ereg_replace("\n","",$str);
//    $str = ereg_replace(" "," ",$str);
    return trim($str);
}

function formatSize($str,$moreinfo = true)
{
    $sizeArray = explode(',', $str);
    $byte = $sizeArray[0];

    if ($byte >= 1073741824) {
        $fileSize = round($byte / 1073741824 * 100) / 100 . ' GB';
    } elseif ($byte >= 1048576) {
        $fileSize = round($byte / 1048576 * 100) / 100 . ' MB';
    } elseif ($byte >= 1024) {
        $fileSize = round($byte / 1024 * 100) / 100 . ' KB';
    } else {
        $fileSize = $byte . ' 字节';
    }
    if(count($sizeArray) > 1 && $moreinfo){
        return '文件大小: '.$fileSize.'，图像尺寸: '.$sizeArray[1] ;
    } else{
        return $fileSize;
    }
}
if (!function_exists('getRealPath')) {
    function getRealPath($path)
    {
        return config('filesystems.disks.public.root') . '/' . $path;
    }
}
