<?php
    class Permiso
    {
       public function acceder()
       {
           /*atributos para la conexión pdo */
           $opc=[PDO::ATTR_CASE=>PDO::CASE_UPPER,PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_ORACLE_NULLS=>PDO::NULL_EMPTY_STRING,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
           PDO::ATTR_EMULATE_PREPARES => FALSE];
            /*creando un objeto para instanciar un objeto PDO
            Para hacer una conexión más segura, este lleva consigo 
            4 paramétros*/
            $link= new PDO("mysql:host=127.0.0.1;dbname=prueba_bodega_tfm",
                "root","",$opc);

            /*evaluando la información que viene en la conexión,
            a fin que todo lo que venga en caracteres con la connotación 
            UTF-8 no presente inconveniente alguno*/
            $link->exec("set names utf8");
            /*se retorna la conexión*/
            return $link;
       }
    }