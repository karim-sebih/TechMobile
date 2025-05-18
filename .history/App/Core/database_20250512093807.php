<?php

class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=tmbo;charset=utf8', 'root', '');
    }
}
