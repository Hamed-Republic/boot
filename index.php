[<?php

include("config.php");
include("check.php");
date_default_timezone_set('Asia/Tehran');
// ------- Telegram -------
    $telegram_ip_ranges = [["lower" => "149.154.175.0", "upper" => "149.154.175.255"], ["lower" => "91.108.4.0", "upper" => "91.108.7.255"]];
    $ip_dec = (int) sprintf("%u", ip2long($_SERVER["REMOTE_ADDR"]));
    $ok = false;
    foreach ($telegram_ip_ranges as $telegram_ip_range) {
        if (!$ok) {
            $lower_dec = (int) sprintf("%u", ip2long($telegram_ip_range["lower"]));
            $upper_dec = (int) sprintf("%u", ip2long($telegram_ip_range["upper"]));
            if ($lower_dec <= $ip_dec && $ip_dec <= $upper_dec) {
                $ok = true;
            }
        }
    }
    if (!$ok) {
        exit("<h1 style='text-align: center;margin-top:30px'> برای ورود به ربات به ایدی زیر مراجعه کنید <a href='tg://resolve?domain=" . $bot_id . "'>@" . $bot_id . "</a></h1>");
    }

error_reporting(0);
$next = date('Y/m/d', strtotime('+3 day'));
$next90 = date('Y/m/d', strtotime('+90 day'));

// ------- Telegram -------
$update = json_decode(file_get_contents('php://input'));
if(isset($update->message)){
$chat_id = $update->message->chat->id;
$from_id = $update->message->from->id;
$text = $update->message->text;
$first_name = $update->message->from->first_name;
$last_name = $update->message->from->last_name;
$fullName = $first_name . ' ' . $last_name;
$username = $update->message->from->username;
$message_id = $update->message->message_id;
$phoneid = $update->message->contact->user_id;
}
if (isset($update->callback_query)){
$chat_id = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$message_id2 = $update->callback_query->message->message_id;
}


function objectToArrays($object){
if(!is_object($object)and !is_array($object)){
return $object;
}
if(is_object($object)){
$object = get_object_vars($object);
}
return array_map("objectToArrays",$object);
}



// Anti Code
if($chat_id != $admin){
    if(strpos($text, 'zip') !== false or strpos($text, 'ZIP') !== false or strpos($text, 'Zip') !== false or strpos($text, 'ZIp') !== false or strpos($text, 'zIP') !== false or strpos($text, 'ZipArchive') !== false or strpos($text, 'ZiP') !== false){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"❌ | از ارسال کد مخرب خودداری کنید",
            'parse_mode'=>"HTML",
            ]);
        exit;
        }
        if(strpos($text, 'kajserver') !== false or strpos($text, 'update') !== false or strpos($text, 'UPDATE') !== false or strpos($text, 'Update') !== false or strpos($text, 'https://api') !== false){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"❌ | از ارسال کد مخرب خودداری کنید",
            'parse_mode'=>"HTML",
            ]);
        exit;
        }
        if(strpos($text, '$') !== false or strpos($text, '{') !== false or strpos($text, '}') !== false){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"❌ | از ارسال کد مخرب خودداری کنید",
            'parse_mode'=>"HTML",
            ]);
        exit;
        }
        if(strpos($text, '"') !== false or strpos($text, '(') !== false or strpos($text, '=') !== false){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"❌ | از ارسال کد مخرب خودداری کنید",
            'parse_mode'=>"HTML",
            ]);
        exit;
        }
        if(strpos($text, 'getme') !== false or strpos($text, 'GetMe') !== false){
        bot('sendMessage',[
            'chat_id'=>$chat_id,
            'text'=>"❌ | از ارسال کد مخرب خودداری کنید",
            'parse_mode'=>"HTML",
            ]);
        exit;
        }
    }

    if($text == "/start"){

        $sql    = "SELECT `id` FROM `users` WHERE `id`=$chat_id";
        $result = mysqli_query($conn,$sql);

        $res = mysqli_fetch_assoc($result);

        if(!$res){

            $sql2    = "INSERT INTO `users` (id, step, ref, coin, phone, account) VALUES ($chat_id, 'none', 0, 0, 0, 'ok')";
            $result2 = mysqli_query($conn,$sql2);
        }
        }

$sql_on_off    = "SELECT `bot` FROM `Settings`";
$result_on_off = mysqli_query($conn,$sql_on_off);
$res_on_off = mysqli_fetch_assoc($result_on_off);
$trsrul_on_off  = $res_on_off['bot'];

if($trsrul_on_off == "off" and $chat_id != $admin){

    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"❌ ربات از طرف مدیریت خاموش میباشد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🖥 کانال",'url'=>"https://t.me/$channel_bot"]],
]
])
]);
exit;
}

$sql_account    = "SELECT `account` FROM `users` WHERE `id`=$chat_id";
$result_account = mysqli_query($conn,$sql_account);
$res_account = mysqli_fetch_assoc($result_account);
$trsrul_account  = $res_account['account'];

if($trsrul_account == "ban"){

    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"❌ حساب شما از طرف مدیریت مسدود شده است",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🖥 کانال",'url'=>"https://t.me/$channel_bot"]],
]
])
]);
exit;
}



if($channel_bot !="on"){
$forchaneel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$channel_bot&user_id=".$chat_id));
$tch = $forchaneel->result->status;

        if($tch != 'member' && $tch != 'creator' && $tch != 'administrator'){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"👨‍💻 سلام کاربر گرامی جهت استفاده از ربات درون کانال شما عضو شوید تا از اخرین اخبار ما با خبر باشید",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"🖥 کانال",'url'=>"https://t.me/$channel_bot"]],
]
])
]);
exit();
}}

        $key1        = '👤 حساب کاربری';
        $key2        = '🛍 خرید سرویس';
        $key5        = '📲 سرویس های موجود';
        $key6        = '💵 تعرفه ها';
        $key7        = '☎️ پشتیبانی';
        $key8        = '🔑 راهنمای اتصال';
        $key9        = '🎁 اکانت تست';
        $pay         = '💳 شارژ حساب';

        $reply_keyboard = [
                                [$key1] ,
                                [$key5 , $key2] ,
                                [$key9 , $key6 , $pay] ,
                                [$key7 , $key8] ,

                              ];

            $reply_kb_options = [
                                    'keyboard'          => $reply_keyboard ,
                                    'resize_keyboard'   => true ,
                                    'one_time_keyboard' => false ,
                                ];

                                $key11          = '📊 امار ربات';
                                $key21          = '📨 پیام همگانی';
                                $key51          = '📨 فوروارد همگانی';
                                $key61          = '➕اضافه کردن سرویس';
                                $suppprt_result = '📮 پیام به کاربر';
                                $add_coin       = '➕ اضافه کردن موجودی';
                                $kasr_coin      = '➖کسر موجودی';
                                $add_time       = '🔁 تمدید سرویس';
                                $moton          = '📝 تنظیم متن ها';
                                $Settings       = '⚙️ تنظمیات';
                                $check_user     = '👤 پیگیری افراد';
                                $vaz            = '🔃 تغییر وضعیت حساب';

                                $reply_keyboard_panel = [
                                                        [$key11] ,
                                                        [$key21 , $key51] ,
                                                        [$key61 , $suppprt_result] ,
                                                        [$add_coin , $kasr_coin , $add_time] ,
                                                        [$moton , $Settings , $check_user] ,
                                                        [$vaz] ,

                                                      ];

                                    $reply_kb_options_panel = [
                                                            'keyboard'          => $reply_keyboard_panel ,
                                                            'resize_keyboard'   => true ,
                                                            'one_time_keyboard' => false ,
                                                        ];

                                                        $back = '◀️ بازگشت';

                                                            $reply_keyboard_back = [
                                                                                        [$back] ,

                                                                                    ];

$reply_kb_options_back = [
                                                                                            'keyboard'          => $reply_keyboard_back ,
                                                                                            'resize_keyboard'   => true ,
                                                                                            'one_time_keyboard' => false ,
                                                                                        ];

// if

$adminstep = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `step` FROM `users` WHERE `id`=$from_id LIMIT 1"));

if(isset($update->message->contact)){
    if($update->message->contact->user_id == $from_id){
        $phone =$update->message->contact->phone_number;
        if(strpos($phone,'98') === 0 || strpos($phone,'+98') === 0){
            $phone = '0'.strrev(substr(strrev($phone),0,10));
            mysqli_query($conn,"UPDATE users SET phone='$phone' WHERE id='$phoneid' LIMIT 1");
            bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ شماره تلفن شما با موفقیت ثبت و تایید شد.",
'reply_markup'=>json_encode($reply_kb_options),
]);

bot('sendmessage',[
'chat_id'=>$chanSef,
'text'=>"👤 ثبت نام جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $from_id",
]);
        }
        else{
            bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"کشور شما مجاز نیست فقط ایران مجاز است",
]);
exit;
        }

    }
}

if($data == "zarinPal"){
$sqlnumber    = "SELECT phone FROM users WHERE id=$chat_id";
$resultnumber = mysqli_query($conn,$sqlnumber);

$resnumber = mysqli_fetch_assoc($resultnumber);
    if($resnumber['phone'] == 0){
        bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
📱 لطفا شماره موبایل خود را تایید نمایید.

👈جهت جلوگیری از خرید با کارت های دزدی نیاز است شماره خود را تایید نمائید و سپس اقدام به خرید کنید.

✔️شماره شما نزد ما محفوظ است و هیچ شخصی به آن دسترسی نخواهد داشت.
",
'reply_markup' => json_encode([
'resize_keyboard'=>true,
'keyboard' => [
[['text'=>"⏳تایید شماره⏳",'request_contact'=>true]],
],
])
]);

    }

            else{
            mysqli_query($conn,"UPDATE `users` SET `step`='pay_d' WHERE id='$chat_id' LIMIT 1");

            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"💳 مبلغی که میخواهید شارژ کنید را به تومان وارد کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode($reply_kb_options_back),
        ]);
            }
}

if($adminstep['step'] == "pay_d" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

    if(is_numeric($text)){

        bot('sendmessage',[
			'chat_id'=>$chat_id,
			'text'=>"💳 درگاه پرداخت ساخته شد

✅ بعد پرداخت موجودی خودکار واریز میشود",
			'reply_to_message_id'=>$message_id,
			'reply_markup'=>json_encode([
    'inline_keyboard'=>[
	[['text'=>"💳 | پرداخت $text",'url'=>"$web/pay/index.php?amount=$text&id=$from_id"]],
              ]
              ])
	       ]);

    }
    else{
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ | اطلاعات وارد شده شما اشتباه است",
        ]);

    }
}

if($adminstep['step'] == "support" and $text != $back){

    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"✅ پیام با موفقیت ارسال شد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode($reply_kb_options),
]);

bot('sendMessage',[
'chat_id'=>$admin,
'text'=>"👨‍💻 سلام ادمین یک پیام برات امده

 $text


👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id",
'parse_mode'=>"HTML",
]);
mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "android"){

    $sql2    = "SELECT `android` FROM `moton`";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['android'];

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"$trsrul2",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "back" ]
        ],
        ]
        ])
        ]);

}

if($data == "windows"){

    $sql2    = "SELECT `windows` FROM `moton`";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['windows'];

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"$trsrul2",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "back" ]
        ],
        ]
        ])
        ]);

}

if($data == "ios"){

    $sql2    = "SELECT `ios` FROM `moton`";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['ios'];

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"$trsrul2",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "back" ]
        ],
        ]
        ])
        ]);

}

if($data == "back"){


        bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"راهنمای اتصال سرویس ها",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"📲 اندروید",'callback_data'=>"android"],
            ['text'=>"📲 ios",'callback_data'=>"ios"],
        ],
        [
            ['text'=>"🖥 ویندوز",'callback_data'=>"windows"],
        ],
        [
            ['text'=>"❌ بستن",'callback_data'=>"close"],

        ],
        ]
        ])
        ]);

}

if($data == "close"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"✅ بسته شد",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        ]);
}

if($adminstep['step'] == "key_hmgani" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

$sql    = "SELECT * FROM `users`";
$result = mysqli_query($conn,$sql);

 while($row = mysqli_fetch_assoc($result)){

    bot('sendMessage',[
'chat_id'=>$row['id'],
'text'=>"$text",
'parse_mode'=>"HTML",
]);
}
bot('sendMessage',[
'chat_id'=>$admin,
'text'=>"✅ انجام شد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode($reply_kb_options_panel),
]);
}


if($adminstep['step'] == "key_forvard" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$admin' LIMIT 1");

$sql    = "SELECT * FROM `users`";
$result = mysqli_query($conn,$sql);

 while($row = mysqli_fetch_assoc($result)){

    bot('ForwardMessage',[
'chat_id'=>$row['id'],
'from_chat_id'=>$chat_id,
'message_id'=>$message_id
]);
    }

    bot('sendMessage',[
'chat_id'=>$admin,
'text'=>"✅ انجام شد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode($reply_kb_options_panel),
]);
}

if($adminstep['step'] == "suppprt_result" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

    $text_admin = explode(",",$text);
    $user_id = $text_admin['0'];
    $text_admin = $text_admin['1'];


    bot('sendmessage',[
'chat_id'=>$user_id,
'text'=>"👨‍💻 یک پیام از طرف مدیریت براتون امد

📝 : $text_admin",
]);

bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✅ انجام شد",
'reply_markup'=>json_encode($reply_kb_options_panel),
]);
}


if($data == "bestgig"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
'text'=>"🔑 جهت اضافه کردن کلید دستور العمل زیر را دنبال کنید

key,contry

key : کلید
contry : کشور

کشورهای مجاز 👇

finland
germany
usa",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "bestgigBack" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='bestgig' WHERE id='$chat_id' LIMIT 1");
        exit();
}

if($data == "bestgigBack"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestgig"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"chlgig"]
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"shastgig"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadgog"]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "bestgig"){

$sql4    = "SELECT * FROM `vpn`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$ok = $res4 + 1;

    $text_admin = explode(",",$text);
    $kay = $text_admin['0'];
    $contry = $text_admin['1'];

    $sql2    = "INSERT INTO `vpn` (`id`, `code`, `hajm`, `contry`) VALUES ('$ok', '$kay', '25', '$contry')";
    $result2 = mysqli_query($conn,$sql2);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ انجام شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "chlgig"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"🔑 جهت اضافه کردن کلید دستور العمل زیر را دنبال کنید

key,contry

key : کلید
contry : کشور

کشورهای مجاز 👇

finland
germany
usa",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "chlgigback" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='chlgig' WHERE id='$chat_id' LIMIT 1");
        exit();
}

if($data == "chlgigback"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestgig"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"chlgig"]
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"shastgig"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadgog"]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

}

if($adminstep['step'] == "chlgig"){

$sql4    = "SELECT * FROM `vpn`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$ok = $res4 + 1;

    $text_admin = explode(",",$text);
    $kay = $text_admin['0'];
    $contry = $text_admin['1'];

    $sql2    = "INSERT INTO `vpn` (`id`, `code`, `hajm`, `contry`) VALUES ('$ok', '$kay', '50', '$contry')";
    $result2 = mysqli_query($conn,$sql2);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"انجام شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "shastgig"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"🔑 جهت اضافه کردن کلید دستور العمل زیر را دنبال کنید

key,contry

key : کلید
contry : کشور

کشورهای مجاز 👇

finland
germany
usa",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "shastgigback" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='shastgig' WHERE id='$chat_id' LIMIT 1");
        exit();
}

if($data == "shastgigback"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestgig"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"chlgig"]
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"shastgig"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadgog"]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "shastgig"){

$sql4    = "SELECT * FROM `vpn`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$ok = $res4 + 1;

    $text_admin = explode(",",$text);
    $kay = $text_admin['0'];
    $contry = $text_admin['1'];

    $sql2    = "INSERT INTO `vpn` (`id`, `code`, `hajm`, `contry`) VALUES ('$ok', '$kay', '75', '$contry')";
    $result2 = mysqli_query($conn,$sql2);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"انجام شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "sadgog"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"🔑 جهت اضافه کردن کلید دستور العمل زیر را دنبال کنید

key,contry

key : کلید
contry : کشور

کشورهای مجاز 👇

finland
germany
usa",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "sadgogback" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='sadgog' WHERE id='$chat_id' LIMIT 1");
        exit();
}

if($data == "sadgogback"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestgig"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"chlgig"]
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"shastgig"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadgog"]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "sadgog"){

$sql4    = "SELECT * FROM `vpn`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$ok = $res4 + 1;

    $text_admin = explode(",",$text);
    $kay = $text_admin['0'];
    $contry = $text_admin['1'];

    $sql2    = "INSERT INTO `vpn` (`id`, `code`, `hajm`, `contry`) VALUES ('$ok', '$kay', '100', '$contry')";
    $result2 = mysqli_query($conn,$sql2);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"انجام شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "dvistgig"){

    bot('editmessagetext',[
        'chat_id'=>$chat_id,
'text'=>"🔑 جهت اضافه کردن کلید دستور العمل زیر را دنبال کنید

key,contry

key : کلید
contry : کشور

کشورهای مجاز 👇

finland
germany
usa",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "dvistgigback" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='dvistgig' WHERE id='$chat_id' LIMIT 1");
        exit();
}

if($data == "dvistgigback"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestgig"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"chlgig"]
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"shastgig"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadgog"]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "dvistgig"){

$sql4    = "SELECT * FROM `vpn`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$ok = $res4 + 1;

    $text_admin = explode(",",$text);
    $kay = $text_admin['0'];
    $contry = $text_admin['1'];

    $sql2    = "INSERT INTO `vpn` (`id`, `code`, `hajm`, `contry`) VALUES ('$ok', '$kay', '200', '$contry')";
    $result2 = mysqli_query($conn,$sql2);

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"انجام شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "add_coin" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

    $text_admin = explode(",",$text);
    $user_id = $text_admin['0'];
    $coin = $text_admin['1'];

    $sql2    = "SELECT `coin` FROM `users` WHERE `id`=$user_id";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['coin'];

    $coin_new = $trsrul2 + $coin;

    mysqli_query($conn,"UPDATE `users` SET `coin`='$coin_new' WHERE id='$user_id' LIMIT 1");

    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"انجام شد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode($reply_kb_options_panel),
]);

bot('sendMessage',[
'chat_id'=>$user_id,
'text'=>"👤 کاربر عزیز مقدار $coin به حساب شما از طرف مدیریت اضافه شد",
'parse_mode'=>"HTML",
]);



}

if($adminstep['step'] == "kasr_coin" and $text != $back){

    mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");

    $text_admin = explode(",",$text);
    $user_id = $text_admin['0'];
    $coin = $text_admin['1'];

    $sql2    = "SELECT `coin` FROM `users` WHERE `id`=$user_id";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['coin'];

    $coin_new = $trsrul2 - $coin;

    mysqli_query($conn,"UPDATE `users` SET `coin`='$coin_new' WHERE id='$user_id' LIMIT 1");

    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"انجام شد",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode($reply_kb_options_panel),
]);

bot('sendMessage',[
'chat_id'=>$user_id,
'text'=>"👤 کاربر عزیز مقدار $coin از حساب شما از طرف مدیریت کسر شد",
'parse_mode'=>"HTML",
]);



}

if($data == "cart"){
$sqlnumber    = "SELECT phone FROM users WHERE id=$chat_id";
$resultnumber = mysqli_query($conn,$sqlnumber);

$resnumber = mysqli_fetch_assoc($resultnumber);
    if($resnumber['phone'] == 0){
        bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
📱 لطفا شماره موبایل خود را تایید نمایید.

👈جهت جلوگیری از خرید با کارت های دزدی نیاز است شماره خود را تایید نمائید و سپس اقدام به خرید کنید.

✔️شماره شما نزد ما محفوظ است و هیچ شخصی به آن دسترسی نخواهد داشت.
",
'reply_markup' => json_encode([
'resize_keyboard'=>true,
'keyboard' => [
[['text'=>"⏳تایید شماره⏳",'request_contact'=>true]],
],
])
]);

    }

            else{
    bot('editmessagetext',[
        'chat_id'=>$chat_id,
        'text'=>"💳 برای اضافه کردن موجودی، مبلغی که میخواهید شارژ کنید را به حساب زیر واریز کنید بعد عکس رسید را ارسال فرمایید

💳 : $cartP
بنام: $cartN

❌ تا ارسال نکردن عکس از این قسمت خارج نشید اگه قصد لغو داشتید از دکمه بازگشت استفاده کنید

📍 تایید تراکنش شما به نوبت در سریع‌ترین زمان ممکن انجام خواهد شد.",
        'parse_mode'=>"HTML",
        'message_id' => $message_id2,
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            [ 'text' => "بازگشت"   , 'callback_data' => "cartback" ]
        ],
        ]
        ])
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='cart' WHERE id='$chat_id' LIMIT 1");
}
}
if($data == "cartback"){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لغو شد",
        'parse_mode'=>"HTML",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($adminstep['step'] == "cart"){

    bot('ForwardMessage',[
'chat_id'=>$admin,
'from_chat_id'=>$chat_id,
'message_id'=>$message_id
]);

bot('sendMessage',[
        'chat_id'=>$admin,
        'text'=>"🔑 #Pay

واریزی جدید توسط  انجام شده عکس ارسالی کا ربر پست بالا 👆

👤 : $fullName
👤 : $username
☎️ : $phone
🆔 : `$chat_id`",
        'parse_mode'=>"MarkDown",
        ]);
        mysqli_query($conn,"UPDATE `users` SET `step`='none' WHERE id='$chat_id' LIMIT 1");
}

if($data == "pay"){

    pay();
}

if($data == "usa"){

$sql4    = "SELECT * FROM `vpn` WHERE contry='usa' LIMIT 1";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

if($res4 == 0){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
}
else{

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestPangGig25Gu"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"ChlPangGig50Gu"],
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"ShastGig75Gu"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadGig100Gu"],

        ],
        ]
        ])
        ]);
}
}

if($data == "bestPangGig25Gu"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='25'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig25){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'usa' AND hajm = '25' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id


🔑 vpn key : $trsrul2233

تاریخ انقضا : $next
کشور : امریکا
اشتراک : 1 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig25;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'usa', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");

    }
       else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);

    }

    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "ChlPangGig50Gu"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='50'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig50){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'usa' AND hajm = '50' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next90",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next90
کشور : امریکا
اشتراک : 3 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig50;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'usa', $chat_id, '$next90')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
        else{
            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
        }
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "ShastGig75Gu"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='75'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig75){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'usa' AND hajm = '75' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next
کشور : امریکا
اشتراک : 1 ماهه (سه کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig75;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'usa', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}


if($data == "sadGig100Gu"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='100'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig100){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'usa' AND hajm = '100' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next90",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next90
کشور : امریکا
اشتراک : 3 ماهه (سه کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig100;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'usa', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
        else{

            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
        }
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "finland"){

$sql4    = "SELECT * FROM `vpn` WHERE contry='finland' LIMIT 1";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

if($res4 == 0){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
}
else{

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestPangGig25"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"ChlPangGig50"],
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"Shastgig75"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadGig100"],

        ],
        ]
        ])
        ]);
}
}

if($data == "bestPangGig25"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='25'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig25){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'finland' AND hajm = '25' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next
کشور : فنلاند
اشتراک : 1 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig25;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'finland', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");

    }
       else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);

    }

    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "ChlPangGig50"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='50'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig50){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'finland' AND hajm = '50' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next90",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next90
کشور : فنلاند
اشتراک : 3 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig50;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'finland', $chat_id, '$next90')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
        else{
            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
        }
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "Shastgig75"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='75'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig75){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'finland' AND hajm = '75' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔗 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next
کشور : فنلاند
اشتراک : 1 ماهه (سه کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig75;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'finland', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}


if($data == "sadGig100"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='100'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig100){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'finland' AND hajm = '100' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next90",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next90
کشور : فنلاند
اشتراک : 3 ماهه (سه کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig100;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'finland', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
        else{

            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
        }
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "germany"){

$sql4    = "SELECT * FROM `vpn` WHERE contry='germany' LIMIT 1";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

if($res4 == 0){

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
}
else{

    bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"لطفا حجم مورد نظر خود را انتخاب کنید",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [
            ['text'=>"1 ماهه (دو کاربر)",'callback_data'=>"bestPangGig25G"],
            ['text'=>"3 ماهه (دو کاربر)",'callback_data'=>"ChlPangGig50G"],
        ],
        [
            ['text'=>"1 ماهه (سه کاربر)",'callback_data'=>"Shastgig75G"],
            ['text'=>"3 ماهه (سه کاربر)",'callback_data'=>"sadGig100G"],

        ],
        ]
        ])
        ]);
}
}

if($data == "bestPangGig25G"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='25'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig25){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'germany' AND hajm = '25' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next
کشور : آلمان
اشتراک : 1 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig25;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'germany', $chat_id, '$next')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");

    }
       else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);

    }

    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "ChlPangGig50G"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='50'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trsrul2)){

    $sql22    = "SELECT `coin` FROM `users` WHERE `id`='$chat_id'";
    $result22 = mysqli_query($conn,$sql22);
    $res22 = mysqli_fetch_assoc($result22);
    $trsrul22  = $res22['coin'];

    if($trsrul22 >= $gig50){

$sql2233    = "SELECT * FROM vpn WHERE contry = 'germany' AND hajm = '50' LIMIT 1";
$result2233 = mysqli_query($conn,$sql2233);
$res2233 = mysqli_fetch_assoc($result2233);
$trsrul2233  = $res2233['code'];

if(isset($trsrul2233)){

bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "لطفا چند لحظه صبر کنید ربات درحال فعال سازی اشتراک شما می باشد ...",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
bot('sendmessage',[
'chat_id'=> $chat_id,
'text'=> "⏳",
'parse_mode'=>"Markdown",
'reply_to_message_id'=>$message_id,
]);
sleep ('5');

bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ #ok

خرید انجام شد کلید اتصال شما 👇
🔑 : `$trsrul2233`

📍برای کپی کردن کلید اتصال روی آن کلیک کنید.
📆 زمان تمدید : $next90",
        'parse_mode'=>"MarkDown",
        ]);

        bot('sendMessage',[
        'chat_id'=>$chanSef,
        'text'=>"#خرید_جدید

👤 : $fullName
👤 : @$username
☎️ : $phone
🆔 : $chat_id

🔑 vpn key : $trsrul2233

تاریخ انقضا : $next90
کشور : آلمان
اشتراک : 3 ماهه (دو کاربر)",
        'parse_mode'=>"HTML",
        ]);

$sql4    = "SELECT * FROM `Bought`";
$result4 = mysqli_query($conn,$sql4);
$res4    = mysqli_num_rows($result4);

$res42 = $res4 + 1;

$sql223    = "SELECT `coin` FROM `users` WHERE `id`=$chat_id";
$result223 = mysqli_query($conn,$sql223);
$res223 = mysqli_fetch_assoc($result223);
$trsrul223  = $res223['coin'];

$trsrul24 = $trsrul223 - $gig50;

        $sql2    = "INSERT INTO `Bought` (id, code, contry, Owner, date) VALUES ($res42, '$trsrul2233', 'germany', $chat_id, '$next90')";
        $result2 = mysqli_query($conn,$sql2);

        mysqli_query($conn,"UPDATE `users` SET `coin`='$trsrul24' WHERE id='$chat_id' LIMIT 1");
        mysqli_query($conn,"DELETE FROM vpn WHERE code='$trsrul2233'");
    }
        else{
            bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"❌ سرویسی برای ارائه موجود نیست",
        'parse_mode'=>"HTML",
        ]);
        }
    }
    else{

        bot('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"موجودی حساب شما کافی نمیباشد",
        'parse_mode'=>"HTML",
        ]);

    }
    }
}

if($data == "Shastgig75G"){

    $sql2    = "SELECT `contry` FROM `vpn` WHERE `hajm`='75'";
    $result2 = mysqli_query($conn,$sql2);
    $res2 = mysqli_fetch_assoc($result2);
    $trsrul2  = $res2['contry'];

    if(isset($trs