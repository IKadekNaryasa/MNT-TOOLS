<?php

use Config\Services;


function urlsafe_b64encode($string)
{
    return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
}

function urlsafe_b64decode($string)
{
    return base64_decode(strtr($string, '-_', '+/'));
}

function encrypt_id($id)
{
    $encrypter = Services::encrypter();
    return urlencode(urlsafe_b64encode($encrypter->encrypt($id)));
}
function decrypt_id($encrypted_id)
{
    $encrypter = Services::encrypter();
    return $encrypter->decrypt(urlsafe_b64decode(urldecode($encrypted_id)));
}
