<?php

class Database {
    public static function connect() {
        return new PDO('mysql:host=localhost;dbname=boutique;charset=utf8', 'root', '');
    }
}
