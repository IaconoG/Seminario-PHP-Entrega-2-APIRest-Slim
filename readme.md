# LA CARGA DE LA IMAGEN BASE64 TIENE UN substr DE 5 CHAR, PORQUE NI GANAS DE TENER TODO ESE TEXTO (PARA LA ENTREGA ELIMINARLO)
# LA CARGA DE JUEGO NO EJECUTA EL INSERT PORQUE ESTA COMENTADO, ESTO PORQUE QUIERO VER LOS DATOS ANTES DE REALIZAR EL INSERT.

ctrl + shift + f

AppFactory.php -> vamos a poder ver la calse de AppFactory con sus metodos etc.

composer update -> actualiza las dependencias

composer dump-autoload -> actualiza el autoload

php -S localhost:8000 -> ejecutamos slim

localhost:8000 -> nos muestra el index.php

200 ok
400 bad request: faltan datos
404 not found: no existe la ruta
500 internal server error

post -> body -> raw -> json
{
    "nombre": "Counter Strike Global Offensive",
    "imagen": "lKNKJSBkJL",
    "tipoImagen": "imagen/jpg",
    "descripcion": "Tiros",
    "setUrl": "csgo.com",
    "setIdGenero": "1",
    "setIdPlataforma": "1",
}





Preguntas

 - En las consultas donde se envien parametros a para eliminar, modificar u obtener un dato, la informacion del dato es recomendable que sea el id?
 - Al isertar un nuevo juego, genero, plataforma se debe verificar si ya existe?
 - El pasaje de datos por post deber ser por json?






- Informacion importante
- Trabajar con puth y patch 
    - https://stackoverflow.com/questions/50691938/patch-and-put-request-does-not-working-with-form-data
    - https://www.youtube.com/watch?v=07ShvdYh2kM

    - Se debe enviar un dato del formulario con key _METHOD y value PATCH o PUT :D
    - El metodo debe ser de tipo post


// Detalles sonbre mvc
- https://es.stackoverflow.com/questions/61436/duda-sobre-excepciones-en-patron-mvc-propio

// slim
- https://stackoverflow.com/questions/57558242/slim-exception-httpnotfoundexception