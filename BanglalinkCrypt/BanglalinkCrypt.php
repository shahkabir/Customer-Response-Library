<?php

//error_reporting(-1);

class BanglalinkCrypt
{
    const ENCRYPTION_METHOD = "AES-128-CBC";  //Storing cipher method
    const OPTIONS = 0; 
    const ENCRYPTION_IV = "9519410743199374";  // randomly 16 digit values
    private static $encryptionKey = null ;
    
    static function encrypt($str)
    {
        self::validateEncryptionKey();
        // Encryption of string process begins
        $encryption = openssl_encrypt($str, self::ENCRYPTION_METHOD, self::$encryptionKey, self::OPTIONS, self::ENCRYPTION_IV);
        return $encryption;
    }


    static function decrypt($encryption)
    {
        self::validateEncryptionKey();
        $decryption = openssl_decrypt($encryption, self::ENCRYPTION_METHOD, self::$encryptionKey, self::OPTIONS, self::ENCRYPTION_IV);
        return $decryption;
    }

    static function setEncryptionKey($key)
    {
        self::$encryptionKey = $key;
    }
  
    private static function hasEncryptionKey()
    {
        return self::$encryptionKey && true ;
    }

    private static function validateEncryptionKey()
    {
        !self::hasEncryptionKey() &&  die('Error: Please set encryption key first.');
    }

    public static function getEncryptionKey()
    {
        return self::$encryptionKey;
    }
}

?>