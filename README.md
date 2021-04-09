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

```shell
composer require laravel/breeze --dev
```

Luego, ejecutamos

```shell
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

PHP no requiere la definición explícita de los campos de una clase; sin embargo, para poder persistir los valores en la base de datos, necesitamos que la tabla los incluya. Dado que estamos utilizando las migraciones ofrecidas por el framework, modificamos el archivo generado en el paso previo dentro de la carpeta `database/migrations`, agregando en su método `up()` la siguiente definición:
```php
 Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', $precision = 8, $scale=2);
            $table->timestamps();
        });
```

Dentro del método definimos los atributos de la tabla mediante los métodos `id()`, `string()`, `decimal()` y `timestamps()`. El primero de ellos genera un campo autoincremental como clave primaria, el segundo define la columna `producto` de tipo `VARCHAR`, la tercera el campo `price` como `DECIMAL` con la precisión indicada, y el último genera los campos `created_at` y `updated_at` de tipo `TIMESTAMP` con la fecha y hora de creación y actualización, respectivamente. 

Para ver impactada la entidad en la base de datos, ejecutamos nuevamente:

```shell
php artisan migrate
```

#### Envío de datos al servidor

Utilizando el método POST de HTTP, enviaremos datos desde el cliente hacia el servidor. En la vista HTML podemos incluir
un `<form>` con los `<input>`s necesarios para el envío de los datos.

##### Creación de la vista

Para presentarle el formulario al usuario, creamos el archivo `create.blade.php` en la carpeta `resources/views/products`
(bajo este directorio, por convención, podemos agrupar todas las vistas relacionadas con los productos) con el HTML correspondiente. 

```blade
 <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{route('products.store')}}">
                        @csrf
                        <div>
                            <x-label for="name" :value="__('Nombre')" />

                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <div class="mt-4 grid grid-flow-col grid-rows-1 grid-cols-2">
                            <div>
                                <x-label for="price" :value="__('Precio')" />

                                <span>$ </span><x-input id="price" class="mt-1 w-20" type="number" name="price" :value="old('price')" required />
                            </div>
                            <div class="my-auto">
                                <x-button class="ml-3 float-right">
                                    {{ __('Guardar producto') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
```
Además del HTML necesario, se incluyen las clases de Tailwind para formatear la vista. Se recomienda poner el foco en el `<form>`, su `action` y los campos. Los *tags* que no pertenecen a la especificación de HTML, se tratan de [componentes de Blade](https://laravel.com/docs/8.x/blade#components). 

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

Dentro del método `store(Request $request)` del `ProductController`, podemos acceder a los atributos enviados desde la vista agrupados dentro de la Request. Laravel nos provee, además, el método *helper* `validate()` para llevar a cabo una validación sobre los campos recibidos:

```php
   $validated = $request-> validate([
        'name' => 'required|unique:products|max:255',
        'price' => 'required|numeric|min:0'
    ]);
```
Con las reglas utilizadas, **la solicitud no avanzará frente a alguna de las siguientes condiciones**:
- El campo `name` o `price` no está en el *body* de la *request*.
- El campo `name` ya existe en la tabla `products` de la base de datos o supera los 255 caracteres de largo.
- El campo `price` no es numérico o el valor es menor a 0.

Frente al fallo en alguna de las validaciones, **Laravel interrumpe el avance de la *request*, redirigiendo al usuario a la ubicación previa**. Desde la vista que reciba al usuario, podemos acceder a los errores (si hubiera alguno) con la variable `$errors`:

```blade
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4 rounded relative" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif  

```
Mediante la directiva `@if` podemos verificar si hay errores, y en ese caso renderizar el fragmento comprendido hasta el `@endif`.

Para lógicas más extensas de validación, Laravel ofrece la posibilidad de clases que abstraigan la request y ejecuten las 
reglas correspondientes. Se recomienda leer [la documentación del framework al respecto](https://laravel.com/docs/8.x/validation#form-request-validation).

###### Persistencia de la entidad recibida

Para guardar los datos del producto, una vez validados, utilizamos el ORM incorporado en Laravel: Eloquent. Para más información al respecto, dirigirse a [la documentación](https://laravel.com/docs/8.x/eloquent).

Luego de la validación, agregamos:

```php
Product::create($validated);
```

Para que esto funcione, es necesario que el modelo `App/Model/Product` defina los atributos "asignables en masa", de la siguiente forma:

```php
protected $fillable = ['name', 'price'];
```

### Consulta de productos

Para poder confirmar que se haya registrado exitosamente el producto, agreguemos la posibilidad de acceder al listado de todos los productos.

#### Creación de la ruta

Dentro del archivo de rutas, agregamos:

```php
Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');
```
Es decir, que frente a un GET a `/products`, se ejecutará el método `index()` del `ProductController`.


***Importante**: si bien podría parecer un error que "colisione" esta ruta con la definida previamente, debemos tener en cuenta el método HTTP utilizado en cada una. Dado que en esta definimos un GET y en la anterior un POST, es perfectamente válido (y si lo trasladamos a REST, buena práctica) que ambas refieran a `/products`.*


#### Lógica en el controlador

En el método `index()` del controlador, recuperamos todos los productos y los entregamos a una nueva vista:

```php
// Retrieve all products and send them to view
$products = Product::all();

return view('products.index')->with('products', $products);
```

Eloquent nos ofrece el método `all()` para recuperar todos los objetos almacenados. Luego, mediante el *helper* `with()` agregamos los productos a la vista `products.index`.


#### Vista del listado

En el punto anterior llamamos a la vista `products.index` que hasta el momento no existía. Vamos a crearla en el mismo directorio y con la misma lógica de nomenclatura que la de creación de productos: `resources/views/products/index.blade.php`. Dentro de ella, podemos acceder a la variable `$products` que nos envió el controlador. Con la directiva `@foreach($value in <variable>)`, Blade nos permite iterar sobre un arreglo.

Agregando algunas clases de Tailwind, nos queda:


```blade
<div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @foreach ($products as $product)
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nombre
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Precio
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Editar</span>
                                            </th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Eliminar</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" src="https://picsum.photos/seed/picsum/200/300" alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 w-40">
                                                            {{$product->name}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                  ${{$product->price}}
                                                </span>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="#" class="text-red-600 hover:text-red-900">Eliminar</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
    </div>
```

Es importante notar que, en cada iteración, podemos acceder a los atributos del producto mediante `$product->field_name`. Si el objeto tuviese otro asociado, también podríamos acceder a él, a sus atributos o incluso métodos, como comúnmente lo hacemos en POO. 

*Aclaración: se agregaron los enlaces de 'Editar' y 'Eliminar' para los pasos posteriores, pero aún no se utilizan.*

#### Acomodo general

Al completar los pasos previos ya hemos logrado acceder a todos los productos. Ahora, al terminar de guardar un producto, sería deseable redirigir a los usuarios al listado que construimos. En el método `store()` del controlador, utilizamos el *helper* de redirección en lugar del `return` previo:

```php
 /* Previous product saving... */
 
 return redirect()->route('products.index');
 
```

Luego, en la barra de navegación, cambiamos el enlace de `Productos` a la vista del listado:

```blade
<!-- other links -->
<x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
```


### Actualización de un producto

Para la actualización crearemos la vista con los datos del producto almacenado, permitiendo al usuario que los modifique. A su vez, los cambios que realice serán persistidos a la base de datos.

#### Agregando las rutas

Agregamos dos rutas: una para obtener el formulario de actualización, y la otra para recibir los datos del producto actualizado.

```php
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->middleware('auth')->name('products.edit');

Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('auth')->name('products.update');

```
En ambos casos, utilizamos una variable en el path que nos permite capturar el producto afectado mediante `{}` con la variable entre medio.

En la segunda ruta utilizamos un `put` dado que es en ella donde estamos ejecutando la acción concreta de actualizado.


#### Creación de la vista

Creamos una vista en la carpeta `resources/views/products` con el nombre `edit.blade.php`, que muestre el formulario con los datos de un producto como variable `$product`, y que al hacer _submit_ invoque la ruta `products.update`.

```blade 
 <form method="POST" action="{{route('products.update', $product)}}">
    @csrf
    @method('PUT')
    <div>
        <x-label for="name" :value="__('Nombre')" />

        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $product->name" required autofocus />
    </div>

    <div class="mt-4 grid grid-flow-col grid-rows-1 grid-cols-2">
        <div>
            <x-label for="price" :value="__('Precio')" />

            <span>$ </span><x-input id="price" class="mt-1 w-24" type="number" name="price" :value="old('price') ?? $product->price" required />
        </div>
        <div class="my-auto">
            <x-button class="ml-3 float-right">
                {{ __('Actualizar producto') }}
            </x-button>
        </div>
    </div>
</form>
```
Dentro del `<form>`, además de la directiva `@csrf`, incluimos `@method('put')`. Esto se debe a que el formulario especifica el método `POST`, pero a nosotros nos interesa enviar los datos como un `PUT`. Mediante esta directiva, Blade agrega un campo _hidden_ con el valor pasado como parámetro, logrando el efecto deseado. Más información en la [documentación](https://laravel.com/docs/8.x/blade#method-field). 

Veamos que los campos del formulario son los mismos que los utilizados para la creación de un producto, pero dentro del valor inicial - el atributo `:value` del `<x-input>`- estamos indicamos que frente a la existencia de un valor para el campo `name` se utilice ese mismo, pero de no existir, el campo sea rellenado con el valor del producto pasado como variable, es decir, `$product`.


#### Generando los enlaces 

Para poder acceder a la vista que creamos, modificaremos `index.blade.php` para que al clickear en el botón Editar que incluimos previamente, el usuario sea redirigido al formulario de edición.

Este punto parece trivial, pero es importante para entender **cómo renderizar un enlace de forma dinámica**. Lo vemos a continuación:

```blade 
...
<a href="{{route('products.edit', $product->id)}}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
...
```
Dentro del `href` de la etiqueta `<a>` del botón Editar de cada elemento (cada iteración del `@foreach`), utilizamos el *helper* `route()` como en otras oportunidades, pero pasando como segundo parámetro `$product->id`, de forma tal que la ruta `products.edit` reciba el id del producto que queremos modificar. 


#### Handles en el controlador

Del lado del controlador, revisando las rutas que definimos, estamos utilizando los métodos `edit()` y `update()`. Dada la presencia de la variable en el path, estos métodos tendrán que recibir un parámetro. Sin embargo, dado que construimos el controlador con la bandera `--resource` y el modelo `Product`, estos métodos ya están definidos en nuestro `ProductController`, tomando un `Product` como parámetro.  

##### Método `edit()`

Dentro del método `edit()`, simplemente necesitamos devolver la vista que construimos, entregando el producto recibido como parámetro:

```php
return view('products.edit')->with('product', $product);
```

##### Método `update()`

Dentro del método `update()` vamos a validar (al igual que hicimos en el `create`) que los datos del producto sean correctos. Luego, actualizamos el producto con los nuevos valores en la base de datos. 

```php
// Validate the user input
$validated = $request->validate([
    'name' => 'required|max:255',
    'price' => 'required|numeric|min:0'
]);

// Update the product
$product->update($validated);

return redirect()->route('products.index')->with('message', 'Producto actualizado con éxito!');
```
En esta oportunidad, al redirigir a la ruta `products.index`, agregamos un mensaje para mejorar la usabilidad de la aplicación. Del lado de la vista, será accedido mediante `session('message')`.


### Eliminación de un producto



