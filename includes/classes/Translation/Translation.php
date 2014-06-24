<?php

namespace XTC\Translation;


use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;
use XTC\Translation\Loader\ConfFileLoader;

class Translation {
  private static $translator = null;

  public static function getTranslator(){
    if(static::$translator === null){
      $language = new \language();
      $code = $language->language['code'];
      $directory = $language->language['directory'];

      $translator = new Translator($code, new MessageSelector());
      $translator->addLoader('conf', new ConfFileLoader());
      $translator->addResource('conf', DIR_WS_LANGUAGES.$directory.'/lang_'.$directory.'.conf', $code);

      static::$translator = $translator;
    }
    return static::$translator;
  }
} 