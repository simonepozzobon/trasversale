@component('mail::message')
##Nome:
{{ $name }}

##Cognome:
{{ $surname }}

<br>
##Corso:
{{ $product->title }}


<br>
##Richiesta:
<pre>
{{ $message }}
</pre>
@endcomponent
