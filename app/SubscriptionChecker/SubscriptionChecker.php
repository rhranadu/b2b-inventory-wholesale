<?php


namespace App\SubscriptionChecker;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;

class SubscriptionChecker extends Controller
{
    use AuthenticatesUsers, RedirectsUsers;
    private $subscription_url = 'https://subscription-checker.t2v.com.bd'; //'http://subscription-checker.local';
    public function SubscriptionCheck(Request $request, $next)
    {
        $parse_url = parse_url($this->subscription_url);
        if (filter_var(gethostbyname($parse_url['host']), FILTER_VALIDATE_IP)) {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $this->subscription_url . '/api/request');
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Nothi');

            curl_setopt($curl_handle, CURLOPT_POST, true);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, ['url' => url('/')]);

            $response = curl_exec($curl_handle);
            if (curl_errno($curl_handle)) {
                $response = curl_error($curl_handle);
            }
            curl_close($curl_handle);
            $decoded_response = json_decode($response);
            //var_dump($response);die;
            if ($decoded_response->status == false) {
                if ($decoded_response->payment_url) {
                    return redirect($decoded_response->payment_url);
                } else {
                    return $this->sendFailedLoginResponse($request);
                }
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }
}
