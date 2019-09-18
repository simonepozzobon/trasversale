@component('mail::message')
#Pagamento Con Carta Docente Ricevuto!
<br>

##Nome:
{{ $name }}
<br>
##Cognome:
{{ $surname }}

<br>
##Corso:
{{ $product->title }}


<br>
##Codice carte docente:
<pre>
{{ $code }}
</pre>
@endcomponent
