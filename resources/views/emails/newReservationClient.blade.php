Успешная резервация на сайте {{getcong('site_name')}}:

<p>Имя: {{ $name }}</p>
<p>E-mail: {{ $email }}</p>
<p>Телефон: {{ $phone }}</p>
<p>Начало: {{ $start }}</p>

@if(isset($text))
    <p>Сообщение: {{ $text }}</p>
@endif







