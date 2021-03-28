# Ejemplo de aplicación CRUD con PHP + Laravel

## Configuraciones previas

Para poder desarrollar bajo este framework tenemos varias opciones. La que utilizaremos involucra la instalación de:
- PHP
- Composer
- Laravel

Por otro lado, dado que es fundamental contar con la posibilidad de persistir los datos en nuestro sistema, utilizaremos una base de datos relacional: PostgreSQL.


### Instalación de PHP

Se puede utilizar XAMPP pero debido a que es posible que existan errores con la versión de Postgres y la librería cliente, se recomienda descargar [PHP 7.4](https://www.php.net/downloads). Una vez descargado, descomprimir y agregar la raíz del directorio al PATH del sistema operativo.

Para verificar la correcta instalación, ejecutar en una nueva terminal el comando `php -v`. Si todo ha salido bien, veremos como salida la versión de PHP instalada.

#### Configuración adicional

Será necesario utilizar ciertas extensiones de PHP para poder conectarnos con PostgreSQL, hacer uso de las migraciones ofrecidas por el framework y servir la aplicación en entorno de desarrollo.

Dentro del directorio de instalación de PHP, ubicar el archivo `php.ini`. Si este no existe, seguramente tengamos `php.ini-development` y `php.ini-production`. Hacemos una copia del primero de ellos y lo renombramos a `php.ini`.

Abrimos el archivo con un editor de texto y descomentamos (quitando el ";" del comienzo) las líneas:

- extension=fileinfo
- extension=mbstring
- extension=openssl
- extension=pdo_pgsql
- extension=pgsql
- extension_dir="ext"

Desde luego, si algunas o todas se encuentran habilitadas, mantenerlas de ese modo.


[Completar con links a la doc y comandos para crear un proyecto de cero.]


### Composer

Composer es un gestor de dependencias para PHP con el que instalaremos Laravel. Lo descargamos desde su [página oficial](https://getcomposer.org/download/). Por defecto, los instaladores configuran el PATH para poder utilizar Composer desde cualquier CLI. Lo verificamos mediante el comando `composer --version`.

### Laravel

Dado que ya contamos con Composer, podemos instalar el instalador de Laravel como una dependencia global de Composer, ejecutando:

```bash
composer global require laravel/installer
```

Para comprobar su instalación, ejecutar en una terminal el comando `laravel --version`.

### Base de datos: PostgreSQL

Instalar el motor de base de datos [PostgreSQL](https://www.postgresql.org/download/), y crear una base de datos para el proyecto desde algún cliente. 


## Creación del proyecto

Mediante el instalador de Laravel, vamos a crear un nuevo proyecto. Para ello, abrir una consola en la carpeta donde queremos alojar el directorio del proyecto y ejecutar:
```
laravel new crud-app-example --git
```

Esperamos a que finalice, y como resultado se creará una nueva carpeta con el nombre "crud-app-example" que contendrá los archivos del proyecto. Dado que estamos indicando el modificador --git, se inicializará además un repositorio git en el que podremos agregar un remote con Github o cualquier otro servicio de elección.

### Estructura básica del proyecto

La estructura básica del proyecto generado contiene una vista inicial de bienvenida con algunos enlaces útiles de la documentación del framework. Para accederlo, es necesario ejecutar el servidor de desarrollo de Laravel mediante el comando

```
php artisan serve
```

Con esto, quedará ejecutándose la aplicación creada en el puerto 8000 de nuestro host, accesible desde http://localhost:8000.

### Configuración de archivo de ambiente

El archivo `.env` nos permite definir configuraciones que podrán luego ser leídas desde dentro de nuestra aplicación. Al colocar estos valores aquí, cada desarrollador o incluso cada ambiente en que se ejecuta la aplicación, podría contener valores distintos de acuerdo a los parámetros necesarios para cada uno. 

Es importante que **este archivo no sea subido al repositorio**, dado que puede contener credenciales u otro tipo de información sensible. 

Sin embargo, es buena práctica incluir un archivo denominado `.env.example` que permita indicar qué variables deben configurarse para poder hacer uso de la aplicación, tal como el que se encuentra en este repositorio.

#### Acceso a base de datos

Dentro del archivo `.env` **debemos configurar los datos adecuados para la base de datos creada previamente** (host, password, nombre de base de datos, etc.). Dado que utilizamos PostgreSQL, debemos incluir el campo DB_CONNECTION=pgsql. El resto de los datos dependen de la instancia de base de datos que estemos utilizando. Los nombres de las propiedades son los que se encuentran en el archivo de ejemplo, bajo la sección "Database config section". 

##### Verificando el acceso

Laravel, al igual que otros frameworks, incorpora el concepto de *migraciones* con la idea de poder definir, mediante alguna sintaxis que se mapee a SQL, la estructura que tendrán las tablas de la base de datos. Luego de configurar el archivo `.env` con las credenciales de la base de datos, probamos ejecutar las migraciones con el comando

```
php artisan migrate
```


## Starter Kit: Laravel Breeze

Laravel Breeze es una implementación de las funcionalidades básicas de autenticación de Laravel, incluyendo tanto la lógica como las vistas para el ingreso de datos.  

