<?php

class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=tmobile;charset=utf8', 'root', '');
    }
}
