Archivos modificados.

routes\api.php
app\Http\Controllers\PruebaController.php
app\Models\TCalificacione.php
.env


Servicios
+--------+-----------+---------------------+----------------+-----------------------------------------------+------------+
| Domain | Method    | URI                 | Name           | Action                                        | Middleware |
+--------+-----------+---------------------+----------------+-----------------------------------------------+------------+
|        | GET|HEAD  | api/prueba          | prueba.index   | App\Http\Controllers\PruebaController@index   | api        |
|        | POST      | api/prueba          | prueba.store   | App\Http\Controllers\PruebaController@store   | api        |
|        | PUT|PATCH | api/prueba/{prueba} | prueba.update  | App\Http\Controllers\PruebaController@update  | api        |
|        | DELETE    | api/prueba/{prueba} | prueba.destroy | App\Http\Controllers\PruebaController@destroy | api        |
+--------+-----------+---------------------+----------------+-----------------------------------------------+------------+
