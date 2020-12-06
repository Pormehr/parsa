<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PhoneController extends Controller
{
    public function sendVerificationCode($phone)
    {
        $code = $this->setVerificationCode(5);
        $ttl = 2 * 60;
        Log::info("$phone verification code is: $code");
        Cache::put($phone, $code, $ttl);
        return response('verify your phone number...');
    }

    public function verifyPhone($phone, $code)
    {
        $verificationCode = Cache::get($phone);
        if (isset($code) && $verificationCode != NULL && $verificationCode == $code){
            Cache::forget($phone);
            return 'your phone number verified successfully';
        }else{
            return 'try again';
        }
    }

    public function setVerificationCode($count)
    {
        $code = rand((10 ** $count), (10 ** ($count + 1) - 1));
        return $code;
    }
}
