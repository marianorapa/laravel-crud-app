# Ejemplo de aplicación CRUD con PHP + Laravel

El objetivo de este repositorio es dar una breve introducción a la configuración necesaria para desarrollar un proyecto 
con el framework Laravel y llevar a cabo un CRUD (Create, Read, Update, Delete) de una entidad. 

## Configuraciones previas

Para poder desarrollar bajo este framework tenemos varias opciones. La que utilizaremos involucra la instalación de:
- PHP
- Composer
- Laravel

Por otro lado, dado que es fundamental contar con la posibilidad de persistir los datos en nuestro sistema, utilizaremos 
una base de datos relacional: PostgreSQL.


### Instalación de PHP

Se puede utilizar XAMPP pero debido a que es posible que existan errores con la versión de Postgres y la librería cliente, 
se recomienda descargar [PHP 7.4](https://www.php.net/downloads). Una vez descargado, descomprimir y agregar la raíz del
directorio al PATH del sistema operativo.

Para verificar la correcta instalación, ejecutar en una nueva terminal el comando `php -v`. Si todo ha salido bien, 
veremos como salida la versión de PHP instalada.

####  NOTA ACLARATORIA:
Si tienen linux, instalarlo a través de apt:
``` bash
$ sudo apt install php -y
```

#### Configuración adicional

Será necesario utilizar ciertas extensiones de PHP para poder conectarnos con PostgreSQL, hacer uso de las migraciones
ofrecidas por el framework y servir la aplicación en entorno de desarrollo.

Dentro del directorio de instalación de PHP, ubicar el archivo `php.ini`. Si este no existe, seguramente tengamos
`php.ini-development` y `php.ini-production`. Hacemos una copia del primero de ellos y lo renombramos a `php.ini`.

Abrimos el archivo con un editor de texto y descomentamos (quitando el ";" del comienzo) las líneas:

- extension=fileinfo
- extension=mbstring
- extension=openssl
- extension=pdo_pgsql
- extension=pgsql
- extension_dir="ext"

Desde luego, si algunas o todas se encuentran habilitadas, mantenerlas de ese modo.

####  NOTA ACLARATORIA:
Si tienen linux:
``` bash
sudo nano /etc/php/7.4/cli/php.ini
```
[Completar con links a la doc y comandos para crear un proyecto de cero.]


### Composer

Composer es un gestor de dependencias para PHP con el que instalaremos Laravel. Lo descargamos desde su
[página oficial](https://getcomposer.org/download/). Por defecto, los instaladores configuran el PATH para poder 
utilizar Composer desde cualquier CLI. Lo verificamos mediante el comando `composer --version`.

NOTA ACLARATORIA:
Si estás con linux, seguí este tutorial:

 
 sudo apt install php7.4-xml

 



### Laravel

Dado que ya contamos con Composer, podemos descargar el instalador de Laravel como una dependencia global de Composer,
ejecutando:

```bash
composer global require laravel/installer
```

Para comprobar su instalación, ejecutar en una terminal el comando `laravel --version`.

### Base de datos: PostgreSQL

Instalar el motor de base de datos [PostgreSQL](https://www.postgresql.org/download/), y crear una base de datos para
el proyecto desde algún cliente. 

UBUNTU INSTALL:
https://www.postgresql.org/download/linux/ubuntu/

## Creación del proyecto

Mediante el instalador de Laravel, vamos a crear un nuevo proyecto. Para ello, abrir una consola en la carpeta donde
queremos alojar el directorio del proyecto y ejecutar:
```
laravel new crud-app-example --git
```

Esperamos a que finalice, y como resultado se creará una nueva carpeta con el nombre "crud-app-example" que contendrá
los archivos del proyecto. Dado que estamos indicando el modificador --git, se inicializará además un repositorio git
en el que podremos agregar un remote con Github o cualquier otro servicio de elección.

### Estructura básica del proyecto

La estructura básica del proyecto generado contiene una vista inicial de bienvenida con algunos enlaces útiles de la
documentación del framework. Para accederlo, es necesario ejecutar el servidor de desarrollo de Laravel mediante el comando

```
php artisan serve
```

Con esto, quedará ejecutándose la aplicación creada en el puerto 8000 de nuestro host, accesible desde http://localhost:8000.

### Configuración de archivo de ambiente

El archivo `.env` nos permite definir configuraciones que podrán luego ser leídas desde dentro de nuestra aplicación.
Al colocar estos valores aquí, cada desarrollador o incluso cada ambiente en que se ejecuta la aplicación, podría
contener valores distintos de acuerdo a los parámetros necesarios para cada uno. 

Es importante que **este archivo no sea subido al repositorio**, dado que puede contener credenciales u otro tipo de
información sensible. 

Sin embargo, es buena práctica incluir un archivo denominado `.env.example` que permita indicar qué variables deben
configurarse para poder hacer uso de la aplicación, tal como el que se encuentra en este repositorio.

#### Acceso a base de datos

Dentro del archivo `.env` **debemos configurar los datos adecuados para la base de datos creada previamente** 
(host, password, nombre de base de datos, etc.). Dado que utilizamos PostgreSQL, debemos incluir el campo 
DB_CONNECTION=pgsql. El resto de los datos dependen de la instancia de base de datos que estemos utilizando. Los nombres
de las propiedades son los que se encuentran en el archivo de ejemplo, bajo la sección "Database config section". 

#### Migraciones de la base de datos

Laravel, al igual que otros frameworks, **incorpora el concepto de *migraciones*** con la idea de poder definir, mediante
alguna sintaxis que se mapee a SQL, **la estructura que tendrán las tablas de la base de datos**. Dentro
de `/database/migrations` encontramos 3 clases que heredan de Migration. 
Para ver el impacto que tienen sobre la base de datos, luego de configurar el archivo `.env` con las credenciales
adecuadas, ejecutamos 

```
php artisan migrate
```

Si la salida fue exitosa, dentro de la base de datos se habrán creado las 3 tablas con los atributos especificados en
las migraciones. 


## Starter Kit: Laravel Breeze

Laravel Breeze es una implementación de las funcionalidades básicas de autenticación de Laravel, incluyendo tanto la
lógica como las vistas para el ingreso de datos. Se trata de un *starter kit* que podemos incorporar al proyecto, mediante

```
composer require laravel/breeze --dev
```

Luego, ejecutamos

```
php artisan breeze:install

npm install

npm run dev

php artisan migrate
```

y volvemos a ejecutar el proyecto mediante `php artisan serve`.

Para comprobar la correcta instalación, navegamos a la ruta `/register`, completamos los datos y nos registramos en la
aplicación.

## CRUD de productos

Dado que ya contamos con la estructura básica del proyecto y la posibilidad de autenticarnos para proteger las
rutas correspondientes, veamos ahora como **llevar a cabo la creación, consulta, actualización y eliminación de una entidad**.

### Alta de un producto
                  
En primer lugar, vamos a crear una vista que presente un formulario a completar con los datos de un producto: nombre y precio.
Los archivos que se mencionan a continuación pueden encontrarse bajo la rama `feature/product-creation` de este 
repositorio.

#### Creación de una entidad

Laravel nos ofrece un comando de Artisan para facilitarnos la tarea de crear una entidad del modelo de nuestra aplicación.
Para crear un Producto, por ejemplo, ejecutamos:

```shell
php artisan make:model Product --migration
```

Con esto, se habrá creado la clase `app/Models/Product` además de la migración correspondiente para la creación de la tabla
en la base de datos.

*Aclaración: La utilización de "Product" en inglés se justifica para aprovechar las capacidades del framework de 
nomenclatura automática tal como en la migración creada, especialmente frente a palabras que en el plural de
un idioma agregarían 'es' en lugar de 's'.*

##### Definición de los atributos de la entidad

[Completar con la definición de la migration <-----------------------------]() 

#### Envío de datos al servidor

Utilizando el método POST de HTTP, enviaremos datos desde el cliente hacia el servidor. En la vista HTML podemos incluir
un `<form>` con los `<input>`s necesarios para el envío de los datos.

##### Creación de la vista

Para presentarle el formulario al usuario, creamos el archivo `create.blade.php` en la carpeta `resources/views/products`
(bajo este directorio, por convención, podemos agrupar todas las vistas relacionadas con los productos) con el HTML correspondiente. 

**Importante**: dentro del `<form>` está la directiva de Blade `@csrf`. Esta es fundamental para que podamos procesar el 
formulario cuando se envíen los datos a nuestra aplicación. Más información en la [documentación](https://laravel.com/docs/8.x/csrf).

###### Blade templates

Laravel incluye el **motor de plantillas Blade** para la presentación de contenido al usuario. Las vistas que sean construidas
con este motor podrán ser devueltas desde rutas o controladores. Deberán tener extensión `.blade.php` y su sintaxis es
HTML junto con ciertas directivas propias al motor de templates. Más información en la
[documentación](https://laravel.com/docs/8.x/blade).


##### Creación de la ruta: obtener formulario

Para poder acceder a la vista creada mediante un GET a la URL adecuada, **necesitamos registrar la ruta en nuestra aplicación**.
Para ello, utilizamos la fachada Route con su método estático *get* dentro del archivo `web.php` tal como se 
indica a continuación:

```php
Route::get('/products/create', function () {
    return view('products.create');
})->middleware('auth')->name('products.create');
```

De esta forma indicamos que, frente a un GET a la ruta `/products/create`, se devuelva la vista `products.create`. Por
convención, Laravel la buscará dentro del directorio `resources/views/products` bajo el nombre `create.blade.php`.

Al utilizar `->middleware('auth')` sobre la ruta, le estamos indicando a Laravel que debe aplicar el middleware de 
autenticación a las solicitudes que se realicen sobre esta URL. Por lo tanto, de no estar autenticados, nos redirigirá a
la pantalla de login. 

##### Manejo de la respuesta: procesar formulario

Cuando el cliente envíe de vuelta el formulario completo, debemos procesar los datos que está enviando. Para ello, 
es necesario definir una nueva ruta que sea capaz de recibir la información. Esta vez, sin embargo, el método será un POST.

###### Creación del controlador

Dado que la acción a llevar a cabo involucra código más complejo que devolver una vista, **necesitamos introducir un
Controlador**. Para ello, utilizamos el comando de Artisan: 

```shell
php artisan make:controller ProductController --resource --model=Product
```

Como consecuencia, Laravel crea la clase `App/Http/Controllers/ProductController` tanto con el método necesario para guardar 
un producto, como así también el resto de las operaciones CRUD que nos interesan implementar. 

Ahora bien, ¿por qué Laravel hizo esto? Veamos que, en el comando introducido, estamos especificando que el controlador se 
hará cargo de un *recurso* mediante la bandera `--resource`. Como muchas aplicaciones requieren realizar las operaciones
más comunes sobre las entidades, el framework nos facilita su creación mediante *scaffolding*.

Por el momento, sin embargo, solo nos interesa el método `store(Request $request)`.

###### Validación de la request

[Explicar validación sencilla en controller]


Para lógicas más extensas de validación, Laravel ofrece la posibilidad de clases que abstraigan la request y ejecuten las 
reglas correspondientes. Se recomienda leer [la documentación del framework al respecto](https://laravel.com/docs/8.x/validation#form-request-validation).

###### Persistencia de la entidad recibida



### Consulta de productos



### Actualización de un producto


### Eliminación de un producto



