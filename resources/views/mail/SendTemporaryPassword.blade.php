<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <h3>Olá, {{ $name }}! Seja bem vindo(a) ao nosso sistema {{ env('APP_NAME') }}</h3>
    <br>
    <p>Essa é a sua <b>senha temporária</b> para acessar o nosso sistema:</p>
    <p>{{ $password }}</p>
    <br>
    <p>Você pode acessar o sistema utilizando o e-mail cadastrado e a senha temporária com o link a seguir:</p>
    <p>{{ route('login') }}</p>
</body>

</html>
