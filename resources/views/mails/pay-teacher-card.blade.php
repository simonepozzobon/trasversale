@component('mail::message')
#Pagamento inviato!

Il codice della tua carta è stato inviato correttamente, appena avremo conferma del pagamento le invieremo la conferma dell'iscrizione.


Di seguito una copia del contenuto:


<br>
##Corso:
{{ $product->title }}


<br>
##Codice carte docente:
<pre>
{{ $code }}
</pre>


<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
