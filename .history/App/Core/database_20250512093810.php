<?php

class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=tmobm;charset=utf8', 'root', '');
    }
}
