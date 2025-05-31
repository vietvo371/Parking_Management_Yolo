<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Phê Duyệt Cư Dân</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .greeting {
            margin-bottom: 20px;
        }
        .message {
            border-left: 4px solid #4CAF50;
            padding: 15px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        .highlight {
            color: #4CAF50;
            font-weight: bold;
        }
        .action {
            margin-bottom: 20px;
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #45a049;
            text-decoration: none;
            color: white;
        }
        .signature {
            margin-bottom: 20px;
        }
        .footer {
            border-top: 1px solid #eeeeee;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
            color: #757575;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Thông Báo Phê Duyệt Cư Dân
        </div>
        <div class="content">
            <div class="greeting">
                Kính gửi {{ $data['ten_cu_dan'] }},
            </div>
            <div class="action">
                Tài khoản của bạn đã được duyệt, bạn có thể đăng nhập vào hệ thống để sử dụng các dịch vụ.
                <br>
                <a class="button" target="_blank" href="http://localhost:5173/login">Đăng Nhập Ngay</a>
            </div>
            <div class="signature">
                Trân trọng,<br>
                Ban quản lý
            </div>
        </div>
        <div class="footer">
            © 2025 Ban quản lý. Tất cả các quyền được bảo lưu.
            <br><br>
            Hotline: 1900 xxxx
        </div>
    </div>
</body>
</html>
