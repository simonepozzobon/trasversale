@component('mail::message')
#Messaggio inviato!

Vi ringraziamo per averci contattato, risponderemo quanto prima alla vostra richiesta.

di seguito una copia del contenuto:
<pre>
{{ $message }}
</pre>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
