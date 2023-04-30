<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Enumeration\EmailEnumeration;
use App\VendorUser;
use Spatie\MailTemplates\TemplateMailable;


use App\EmailTemplate;
use App\SuperUser;
use Illuminate\Http\Request;
use Mail;
class EmailController extends Controller
{

    public function registration()
    {
        $info =  EmailTemplate::where('email_id',  EmailEnumeration::$REGISTER)->first();
        if($info == null){
            EmailTemplate::create([
                'email_id' => EmailEnumeration::$REGISTER,
                'super_user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'body_content' => ''
            ]);
        }
        if ($info){
            $info->body_content = html_entity_decode($info->body_content);
        }
        $title = 'Email Registration';
        $page_detail = 'Email Registration Template';
        //Send Mail to User
//        $user = SuperUser::where('user_type_id', 1)->where('id',auth()->id())->first();
//        Mail::send('super_admin.email_templates.emails.registration_complete', ['user'=>$user, 'message_content'=>$info], function ($message) use ($user) {
//            $message->subject('Registration Complete');
//            $message->to($user->email);
//        });
        return view('super_admin.email_templates.registration',compact('title', 'page_detail','info'));


    }

    public function registration_save(Request $request)
    {
        $request->body_content = html_entity_decode($request->body_content);
        EmailTemplate::where('email_id', EmailEnumeration::$REGISTER)
            ->update([
                'body_content' => $request->body_content,
                'updated_by' => auth()->id(),
            ]);

        return redirect()->back()->with('message', 'Successfully Added!.');
    }

    public function order_receive(Request $request){
        if ($request->email){
            $user = VendorUser::where('email', '=', $request->email)->select('id','name', 'email')
                ->first();
            if (!$user){
                $user = SuperUser::where('email', '=', $request->email)->select('id','name', 'email')
                    ->first();
            }
        }
        if ($user){
            Mail::send('super_admin.email_templates.emails.order_receive', ['user'=>$user], function ($message) use ($user) {
                $message->subject('Order Received Successful');
                $message->to($user->email);
            });
        }
    }
}
