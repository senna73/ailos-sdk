<?php
namespace Senna\AilosSdkPhp\Core;

interface DtoInterface{
    public static function fromRequest(object $request):self;
    public static function fromArray(array $data):self;
}

?>