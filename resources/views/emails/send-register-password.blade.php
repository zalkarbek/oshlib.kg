@component('mail::message')
<h1>Регистрация в системе El-Kitep</h1>
<p>Вы можете использовать следующий временный пароль для входа в вашу учетную запись:</p>

@component('mail::panel')
{{ $password }}
@endcomponent

@endcomponent
