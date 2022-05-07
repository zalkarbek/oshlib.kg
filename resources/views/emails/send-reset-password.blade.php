@component('mail::message')
<h1>Мы получили ваш запрос на сброс пароля вашей учетной записи</h1>
<p>Вы можете использовать следующий временный пароль для восстановления вашей учетной записи:</p>

@component('mail::panel')
{{ $password }}
@endcomponent

@endcomponent
