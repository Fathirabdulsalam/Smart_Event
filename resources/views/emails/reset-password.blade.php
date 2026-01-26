<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2F0864;">Reset Password - Smart Event ID</h2>
        
        <p>Halo,</p>
        
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #3B82F6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block;">
                Reset Password
            </a>
        </div>
        
        <p>Link ini akan kadaluarsa dalam 60 menit.</p>
        
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #666;">
            Jika Anda kesulitan mengklik tombol "Reset Password", copy dan paste URL berikut ke browser Anda:<br>
            <a href="{{ $url }}" style="color: #3B82F6;">{{ $url }}</a>
        </p>
    </div>
</body>
</html>