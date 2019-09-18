@component('mail::message')
#Messaggio inviato!

Vi ringraziamo per averci contattato, risponderemo quanto prima alla vostra richiesta.

di seguito una copia del contenuto:


<br>
##Corso:
{{ $product->title }}


<br>
##Richiesta:
<pre>
{{ $message }}
</pre>


<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
