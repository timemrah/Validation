<?php


class Validation
{

    private static $field = [];
    private static $error = false;


    static function mustBeStrLen($name, $value, int $mustBe, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(mb_strlen($value, 'utf8') !== $mustBe){
            return self::setField($name, false, "{$title} değeri {$mustBe} karakter olmalı.", 'wrongLength');
        }
        return self::setField($name, true);
    }


    static function minStrLen($name, $value, $min, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(mb_strlen($value, 'utf8') < $min){
            return self::setField($name, false, "{$title} değeri en az {$min} karakter olabilir.", 'short');
        }
        return self::setField($name, true);
    }


    static public function maxStrLen($name, $value, $max, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(mb_strlen($value, 'utf8') > $max){
            return self::setField($name, false, "{$title} değeri en fazla {$max} karakter olabilir.", 'long');
        }
        return self::setField($name, true);
    }


    static public function betweenStrLen($name, $value, $min, $max, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(mb_strlen($value, 'utf8') < $min || mb_strlen($value, 'utf8') > $max){
            return self::setField($name, false, "{$title} değeri en az {$min} en fazla {$max} karakter olabilir.", 'outOfLength');
        }
        return self::setField($name, true);
    }


    static public function email($name, $value, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
            return self::setField($name, false, "{$title} bir eposta adresi olmalıdır.", 'email');
        }
        return self::setField($name, true);
    }


    static public function number($name, $value, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(!is_numeric($value)){
            return self::setField($name, false, "{$title} değeri sayı olabilir", 'int');
        }
        return self::setField($name, true);
    }


    static public function minInt($name,$title, $value, $min, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        } else if(!is_numeric($value)){
            return self::setField($name, false, "{$title} değeri sayı olabilir", 'int');
        }

        if($value < $min){
            return self::setField($name, false, "{$title} değeri en az {$min} olabilir.", 'low');
        }
        return self::setField($name, true);
    }


    static public function maxInt($name,$title, $value, $max, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        } else if(!is_numeric($value)){
            return self::setField($name, false, "{$title} değeri sayı olabilir", 'int');
        }

        if($value > $max){
            return self::setField($name, false, "{$title} değeri en fazla {$max} olabilir.", 'high');
        }
        return self::setField($name, true);
    }


    static public function betweenInt($name,$title, $value, $min, $max, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        } else if(!is_numeric($value)){
            return self::setField($name, false, "{$title} değeri sayı olabilir", 'int');
        }

        if($value < $min || $value > $max){
            return self::setField($name, false, "{$title} değeri en az {$min} en fazla {$max} olabilir.", 'outOfRange');
        }
        return self::setField($name, true);
    }


    static public function date($name, $value, $title, $nullable = false):array{

        if(empty($value)){
            return self::nullable($name, $title, $nullable);
        }

        if(strtotime($value) === false){
            return self::setField($name, false, "{$title} değeri 23/04/2021 gibi bir tarih formatında olmalı", 'date');
        }
        return self::setField($name, true);
    }




    //SETTER:
    static function valid($name, $msg = null, $code = null):array{
        return self::setField($name, true, $msg, $code);
    }
    static function invalid($name, $msg, $code = null):array{
        return self::setField($name, false, $msg, $code);
    }
    static function unset($name){
        unset(self::$field[$name]);
    }




    //GETTER:
    static function getStatus($name){
        return self::$field[$name]['status'] ?? NULL;
    }
    static function getMsg($name){
        return self::$field[$name]['msg'] ?? NULL;
    }
    static function getField($name){
        return self::$field[$name] ?? NULL;
    }
    static function getAllFields():array{
        return self::$field;
    }
    static function isError():bool{
        return self::$error;
    }
    static function isNoError():bool{
        return !self::$error;
    }




    //PRIVATE:
    private static function resFalse($msg = ''):array{
        self::$error = true;
        return [
            'status' => false,
            'msg' => $msg
        ];
    }


    private static function resTrue($msg = ''):array{
        return [
            'status' => true,
            'msg' => $msg
        ];
    }


    private static function setField(string $field, bool $status, $msg = null, $code = null):array{
        if(!$status){ self::$error = true; }
        return self::$field[$field] = compact(['field', 'status', 'msg', 'code']);
    }


    private static function nullable($name, $title, $nullable = false):array{
        if(!$nullable){
            return self::setField($name, false, "{$title} değeri boş olamaz.", 'empty');
        }
        return self::setField($name, true);
    }


}