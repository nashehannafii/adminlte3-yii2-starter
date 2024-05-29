<?php

namespace app\helpers;

if (!defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}

class MyHelper
{

    public static function logError($model)
    {
        $errors = '';
        foreach ($model->getErrors() as $attribute) {
            foreach ($attribute as $error) {
                $errors .= $error . ' ';
            }
        }

        return $errors;
    }

    public static function formatRupiah($value, $decimal = 0)
    {
        return number_format($value, $decimal, ',', '.');
    }
}