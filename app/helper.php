<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('getRealQuery')) {
    function getRealQuery($query, $dumpIt = false)
    {
        $params = array_map(function ($item) {
            return "'{$item}'";
        }, $query->getBindings());
        $result = Str::replaceArray('?', $params, $query->toSql());
        if ($dumpIt) {
            dd($result);
        }
        return $result;
    }
}

if (!function_exists('getActiveGuard')) {
    function getActiveGuard(){
        foreach(array_keys(config('auth.guards')) as $guard){
            if(auth()->guard($guard)->check()) return $guard;
        }
        return null;
    }
}
if (!function_exists('getPathInfo')) {
    function getPathInfo($pathInfo){
        $realPath = 'backend'.DIRECTORY_SEPARATOR.'uploads';
        if(!empty($pathInfo) && is_array($pathInfo)){
            foreach ($pathInfo as $path){
                $realPath = $realPath .DIRECTORY_SEPARATOR. $path;
            }
            return public_path($realPath);
        }
        return false;
    }
}
if (!function_exists('makeDirectory')) {
    function makeDirectory($directoryInfo,$mode = 0755){
        if(!empty($directoryInfo)){
            if(!File::isDirectory($directoryInfo)){
                File::makeDirectory($directoryInfo, $mode, true, true);
            }
        }
        return false;
    }
}
if (!function_exists('getActiveUserTypeId')) {
    function getActiveUserTypeId(){
        if(getActiveGuard() == 'superadmin') {
            return 1 ;
        } elseif(getActiveGuard() == 'vendor') {
            return 2 ;
        } else {
            return 3;
        }
    }
}
if (!function_exists('getActiveUserRole')) {
    function getActiveUserRole(){
        return auth()->user()->user_role->name;
    }
}

if (!function_exists('responseFormat')) {
    function responseFormat($status = 'error', $data = '',$options = []){
        $response = [''];
        if(!empty($status)){
            if($status == 'success'){
                $response = [
                    'status' => $status,
                    'data' => $data
                ];
            }
            elseif ($status == 'error'){
                $response = [
                    'status' => $status,
                    'message' => $data
                ];
                if(!empty($options) && !empty($options['details'])){
                    $response['details'] = $options['details'];
                }
            }
            if(!empty($options) && !empty($options['code'])){
                $response['code'] = $options['code'];
            }
        }
        return $response;
    }
}
if (!function_exists('isSuccessResponse')) {
    function isSuccessResponse($response)
    {
        if (!empty($response)) {
            if (isset($response['status']) && $response['status'] == 'success') {
                return true;
            } elseif (isset($response['status']) && $response['status'] == 'error') {
                return false;
            }
        }
        return false;
    }
}
if (!function_exists('getDomainName')) {
    function getDomainName()
    {
        return $_SERVER['HTTP_HOST'];
    }
}
if(!function_exists('hideData')){
    function hideData($string_to_encode = '',$first_range =1,$last_range = 1) {
        $len = strlen($string_to_encode);
        if(($len - $last_range) < 0){
            return $string_to_encode;
        }
        return substr($string_to_encode, 0, $first_range).str_repeat('*', $len - $last_range).substr($string_to_encode, $len - $last_range, $last_range);
    }
}
if (!function_exists('getIP')) {

    function getIP()
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if(!empty($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
if (!function_exists('curlRequest')) {
    function curlRequest($url,$method = 'get',$headers = [], $post_params = [], $query_params = null,$ssl_verifier = 0)
    {
        try{
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, APP);
            if(!empty($headers)){
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
            }
            if(!empty($query_params)){
                curl_setopt_array($curl_handle, $query_params);
            }
            if(empty($ssl_verifier)){
                curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
            }
            if(!empty($post_params)){
//                curl_setopt($curl_handle, CURLOPT_POST, true);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_params);
            }
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            $http_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

            $curl_response    = curl_exec($curl_handle);
//            pr($curl_response);
//            pr($http_code);
//            die;
            if (curl_errno($curl_handle)) {
                $curl_response = curl_error($curl_handle);
                $response = responseFormat('error', $curl_response,['details' => ['end-point: curl request'],'code' => $http_code]);
            }else{
//                if(!empty($http_code) && $http_code == 200){
//
//                }else{
//                    $response = responseFormat('error',$curl_response,['code' => $http_code]);
//                }
                $response = responseFormat('success',$curl_response,['code' => $http_code]);
            }
            curl_close($curl_handle);
        }catch (\Exception $ex){
            $response = responseFormat('error', $ex->getMessage(),['details' => ['end-point: curl request','code' => $http_code]]);
        }
        return $response;
    }
}
if (!function_exists('RandStr')) {
    function RandStr($l, $c = '1234567890ABCDEFGHIJ1234567890ABCDEFGHIJ')
    {
        $s = '';$cl = strlen($c) - 1;
        for ($i = 0; $i < $l; ++$i){
            $s .= $c [mt_rand(0, $cl)];
        };
        return $s;
    }
}
if (!function_exists('getFileType')) {

    function getFileType($file_path)
    {
        switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'bmp':
                return 'image/bmp';
            case 'pdf':
                return 'application/pdf';
            case 'doc':
                return 'application/msword';
            case 'docx':
                return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            case 'xls':
                return 'application/vnd.ms-excel';
            case 'xlsx':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            case 'ppt':
                return 'application/vnd.ms-powerpoint';
            case 'pptx':
                return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
            default:
                return 'application/octet-stream';
        }
    }
}
if (!function_exists('getBrowser')) {

    function getBrowser()
    {
        $u_agent = !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
        $ub = 'Unknown';
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        $pattern = '';
        if(empty($u_agent)){
            goto rtn;
        }

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac os';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }


        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = 0;
        if(isset($matches['browser'])){
            $i = count($matches['browser']);
        }
        if($i > 0){
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                    $version = $matches['version'][0];
                } else {
                    $version = $matches['version'][1];
                }
            }
            else {
                $version = $matches['version'][0];
            }
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }
        rtn:
        return array(
            'userAgent' => $u_agent,
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'pattern' => $pattern
        );
    }
}
if (!function_exists('encryptData')) {
    function encryptData($string= '',$options=[]){
        try{
            $cipher = !empty($options['method'])?$options['method']:config('app.cipher');
            $key = !empty($options['key'])?$options['key']:config('app.key');
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext_raw = openssl_encrypt($string, $cipher, $key,$options=OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $ciphertext_raw, $key,$as_binary=true);
            $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
            return $ciphertext;
        }catch(\Exception $ex){

        }
        return '';
    }
}
if (!function_exists('decryptData')) {
    function decryptData($encrypt_string= '',$options = []){
        try{
            $cipher = !empty($options['method'])?$options['method']:config('app.cipher');
            $key = !empty($options['key'])?$options['key']:config('app.key');
            $c = base64_decode($encrypt_string);
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = substr($c, 0, $ivlen);
            $hmac = substr($c, $ivlen, $sha2len=32);
            $ciphertext_raw = substr($c, $ivlen+$sha2len);
            $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $calcmac = hash_hmac('sha256', $ciphertext_raw, $key,true);
            if (hash_equals($hmac, $calcmac))
            {
                return $original_plaintext;
            }
        }catch(\Exception $ex){

        }

        return '';
    }
}

if (!function_exists('randGen')) {
    function randGen($length = 6)
    {
       return substr(mt_rand().str_replace(".", "", microtime(true)) , 0, $length);
//        return strtoupper(substr(base_convert(sha1(uniqid(microtime(true) . mt_rand())), 16, 36), 0, $length));
    }
}
