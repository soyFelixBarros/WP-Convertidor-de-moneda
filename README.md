# WP-Convertidor-de-moneda

> Desarrollado por [Felix Barros](https://felixbarros.blog)

Este plugin agrega un widget que funciona con la tecnología Ajax, para una conversión de moneda conveniente en el lugar.

Puedes agregar tantas monedas como quieras. Utilizando el servicio de [currencyconverterapi.com](https://www.currencyconverterapi.com/) para obtener información actualizada sobre divisas.

<img src="https://soyfelixbarros.files.wordpress.com/2019/02/plugin-convertidor-de-moneda-1.0.png" alt="Plugin WP Convertidor de moneda">

## Instalación

Para instalar WP Convertidor de moneda:

1. [Descargue el plugin](https://github.com/soyFelixBarros/WP-Convertidor-de-moneda/archive/master.zip) en formato .zip en el directorio `/wp-content/plugins/` de su WordPress.
2. Active el complemento a través del menú `Plugins`.
3. Configura el servicio ingresando tu correo en [currencyconverterapi.com](https://free.currencyconverterapi.com/free-api-key)
4. Te llegara un correo con la API Key, copiala y pegala en `Ajustes -> WP Convertidor de moneda`.
5. Ahora arrastre el widget a la barra lateral deseada y disfrute.

También puede utilizar la opción de código corto, por ejemplo:

`[wpcdm titulo="Convertidor de moneda" parrafo_anterior="Pruebe esta útil herramienta de conversión de moneda:" convertir_desde=USD a=ARS]`

## Cambios

#### 1.0.0 - 15/2/2019
* Traducción al idioma castellano (Argentina).
* Nuevo campo de configuración para guardar la API KEY del servicio [www.currencyconverterapi.com](https://free.currencyconverterapi.com/free-api-key).
* BUG: Quitamos la opcion de configurar las divisas por defectos, por incompatibilidad con nuevas versiones.