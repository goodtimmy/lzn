<html>
<head>
    <style>
        p {
            font-size: 16px;
            color: #000;
        }
    </style>
</head>
<body>
<p>
    Нажмите на ссылку для сброса пароля: {{ url('admin/password/reset/'.$token) }}
</p>
</body>
</html>