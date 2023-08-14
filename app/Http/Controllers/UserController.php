<?php

namespace App\Http\Controllers;

use App\Helper\JWT_TOKEN;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use function Laravel\Prompts\password;

class UserController extends Controller
{







    function LoginPage ():View {
        return view('pages.auth.login-page');
    }
    function RegistrationPage ():View {
        return view('pages.auth.registration-page');
    }
    function SendOtpPage ():View {
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage ():View {
        return view('pages.auth.verify-otp-page');
    }
    function ResetPasswordPage ():View {
        return view('pages.auth.reset-pass-page');
    }

    function DashboardPage ():View {
        return view('pages.dashboard.dashboard-page');
    }







   function UserRegistration (Request $request) {


       try {
           User::create([
               'firstName' => $request->input('firstName'),
               'lastName' => $request->input('lastName'),
               'email' => $request->input('email'),
               'mobile' => $request->input('mobile'),
               'password' => $request->input('password'),
           ]);
           return response()->json([
               'status' => 'success',
               'message' => 'User Registration successfully Completed'
           ]);
       }catch (Exception $exception){
           return response()->json([
               'status' => 'Failed',
               'message' => 'User Registration Failed'
//               'message' => $exception->getMessage()
           ]);
       }

   }


  function UserLogin (Request $request) {

       $count = User::where('email','=',$request->input('email'))
           ->where('password','=',$request->input('password'))
           ->count();

       if ($count == 1){

           $token = JWT_TOKEN::createToken($request->input('email'));

           return response()->json([
               'status' => 'success',
               'message' => 'user login successful',
           ])->cookie( 'token',$token,60*24*30);

       }else{
           return response()->json([
              'status' => 'failed',
              'message' => 'unauthorized'
           ]);
       }

  }



  function SendOtpCode (Request $request) {
       $email = $request->input('email');
       $otp = rand(1000,9999);
       $count = User::where('email','=',$email)->count();

       if ($count == 1){

           Mail::to($email)->send(new OTPMail($otp));
           User::where('email','=',$email)->update(['otp' => $otp]);

           return response()->json([
               'status' => 'success',
               'message' => '4 Digit otp code was sent your email address'
           ]);

       }else{
           return response()->json([
               'status' => 'failed',
               'message' => 'unauthorized'
           ]);
       }
  }



  function VerifyOtp (Request $request) {
       $email = $request->input('email');
       $otp = $request->input('otp');

       $count = User::where('email','=',$email)
           ->where('otp','=',$otp)
           ->count();

       if ($count == 1){

           User::where('email','=',$email)->update(['otp' => '0']);

           $token = JWT_TOKEN::createTokenForSetPassword($request->input('email'));

           return response()->json([
               'status' => 'success',
               'message' => 'OTP verification successfully completed'
           ])->cookie( 'token',$token,60*24*30);
       }else{
           return response()->json([
               'status' => 'failed',
               'message' => 'unauthorized'
           ]);
       }

  }



  function ResetPassword (Request $request) {

      try {
          $email = $request->header('email');
          $password = $request->input('password');
          User::where('email','=',$email)->update(['password' => $password]);
          return response()->json([
              'status' => 'success',
              'message' => 'Password reset successfully completed'
          ]);
      }catch (Exception $exception){
          return response()->json([
              'status' => 'failed',
              'message' => 'something went wrong',
//              'exception' => $exception->getMessage()
          ]);
      }


  }




}
