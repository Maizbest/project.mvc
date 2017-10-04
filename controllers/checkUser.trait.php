<?php
namespace App;

trait CheckUser {

    private function checkCookie($redirect = '/auth') {
        if (isset($_COOKIE['sid']) || isset($_COOKIE['PHPSESSID'])) {
            session_start();
        } else {
            header("Location: $redirect");
        }
    }
}