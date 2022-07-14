@component('mail::message')

# **Hola!**

Has sido registrado en nuestra plataforma.

Es necesario que cree una contraseña para poder ingresar a nuestra plataforma.

Presione el boton que se encuentra debajo de este mensaje para iniciar!

@component('mail::button', ['url' => $url])
    Nueva contraseña
@endcomponent

**Una vez creada su contraseña podra ingresar con su mail**

Gracias,<br>
{{ config('app.name') }}
@endcomponent
