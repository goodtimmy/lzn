Новая заявка для групп {{getcong('site_name')}}:

<p>Имя: {{ $name }}</p>
<p>E-mail: {{ $email }}</p>
<p>Телефон: {{ $phone }}</p>
<p>Начало: {{ $start }}</p>
<p>Количество: {{ $persons }}</p>

@if(isset($text))
    <p>Сообщение: {{ $text }}</p>
@endif







