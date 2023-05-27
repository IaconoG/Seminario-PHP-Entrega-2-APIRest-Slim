# LA CARGA DE LA IMAGEN BASE64 TIENE UN substr DE 5 CHAR, PORQUE NI GANAS DE TENER TODO ESE TEXTO
# IGUAL EN LA ENTREGA ELIMNARLO

# LA CARGA DE IMAGEN NO EJECUTA EL INSERT PORQUE ESTA COMENTADO, ESTO PORQUE QUIERO VER LOS DATOS ANTES DE REALIZAR EL INSERT.

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


https://stackoverflow.com/questions/57558242/slim-exception-httpnotfoundexception


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
El pasaje de datos por post deber ser por json?



// Detalles sonbre mvc
https://es.stackoverflow.com/questions/61436/duda-sobre-excepciones-en-patron-mvc-propio


---------------------------
JUEGOS

listar - si

mostrar uno - si
eliminar - si
modificar - si
agregar - si (falta el try-catch)
---------------------------
GENERO

listar - si

mostrar uno - no
eliminar - casi (ver lo q no permite eliminar generos vinculados)
modificar - no
agregar - casi
---------------------------
PLATAFORMA

listar - no

mostrar uno - no
eliminar - no
modificar - no
agregar - no


Preguntas

 - En las consultas donde se envien parametros a para eliminar, modificar u obtener un dato, la informacion del dato es recomendable que sea el id?
 - Al isertar un nuevo juego, genero, plataforma se debe verificar si ya existe?

 - Al momento de actualizar la informacion no me permite modificar el id_genero y id_plataforma, 
    - {"error":"SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`test`.`juegos`, CONSTRAINT `fk_genero` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION)"}



- Informacion importante

- Trabajar con puth y patch 
    - https://stackoverflow.com/questions/50691938/patch-and-put-request-does-not-working-with-form-data
    - https://www.youtube.com/watch?v=07ShvdYh2kM

    - Se debe enviar un dato del formulario con key _METHOD y value PATCH o PUT :D
    - El metodo debe ser de tipo post