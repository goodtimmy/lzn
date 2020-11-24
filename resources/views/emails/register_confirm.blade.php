<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Здравствуйте {{$name}},</p>
		 <p>Спасибо за регистрацию на сайте {{getcong('site_name')}}.</p>
		<p>Вы можете использовать следующие данные для управления уведомлениями на сайте {{getcong('site_name')}}.</p>
		<p>Ниже ваши данные регисрации для сайта {{getcong('site_name')}}</p>
		
		<p>Логин: {{$email}}</p>
		<p>Пароль: {{$password}}</p>
		
		<p>Пожалуйста подтвердите вашу регисрацию нажатием на следующую ссылку (или скопируйте ее в браузер):</p>
		<p>{!! link_to('auth/confirm/' . $confirmation_code) !!}.<br></p>
	</body>
</html>
 