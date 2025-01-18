<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    <h1>You have requested to change password:</h1>
    <p>Hello, {{$mailData['user']->name}}</p>

    <p>Please click the link below to change your password</p>
    <a href="{{route('front.resetPassword', $mailData['token'])}}">Click Here</a>

    <p>Thanks.</p>
    

</body>

</html>