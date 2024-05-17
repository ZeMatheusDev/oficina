<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <h3>Olá, {{ $name }}! <br>
    Segue abaixo o link de recuperação de senha que você solicitou no nosso sistema {{ env('APP_NAME') }}</h3>
    <br>
    <p>{{ route('recovery.password.send', ['code' => $code]) }}</p>
    <br>
    <p>Qualquer dúvida, é só entrar em contato com a gente: suporte@raioxgestao.com.br</p>
</body>

</html>
