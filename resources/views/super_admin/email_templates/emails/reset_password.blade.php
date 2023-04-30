<!DOCTYPE html>
<html>
<head>
    <title>SIGN UP EMAIL</title>
    <style>
        .team_info p{
            margin: 0px;
        }
        .mail_body h4{
            margin: 0px;
        }
        .mail_body p{
            margin: 0px;
        }
    </style>
</head>
<body>
    <div class="mail_body">
        <p>Dear {{$user->name}}, You have successfully reset password please login to continue - </p>
        <p>{{$link}}</p>
    </div>
</body>
</html>
