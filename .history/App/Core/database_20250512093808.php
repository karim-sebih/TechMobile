<?php

class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=tmboile;charset=utf8', 'root', '');
    }
}
