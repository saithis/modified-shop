<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XTC\Translation\Loader;

use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;

/**
 * IniFileLoader loads translations from an ini file.
 *
 * @author stealth35
 */
class ConfFileLoader extends ArrayLoader implements LoaderInterface
{
  /**
   * {@inheritdoc}
   */
  public function load($resource, $locale, $domain = 'messages')
  {
    if (!stream_is_local($resource)) {
      throw new InvalidResourceException(sprintf('This is not a local file "%s".', $resource));
    }

    if (!file_exists($resource)) {
      throw new NotFoundResourceException(sprintf('File "%s" not found.', $resource));
    }

    $content = file_get_contents($resource);
    $lines = preg_split('/\n|\r/', $content, -1, PREG_SPLIT_NO_EMPTY);

    $messages = array();
    $prefix = 'global';
    foreach($lines as $line){
      $line = trim($line);
      if(empty($line)){
        continue;
      }

      $fistChar = substr($line, 0, 1);
      if($fistChar === '#'){
        continue;
      }

      if($fistChar === '['){
        $prefix = substr($line, 1, strlen($line) - 2);
        continue;
      }

      $pos = strpos($line, '=');
      if($pos === false){
        continue;
      }

      $name = trim(substr($line, 0, $pos));
      $value = trim(substr($line, $pos+1), ' \'');
      $messages[$prefix.'.'.$name] = $value;
    }

    $catalogue = parent::load($messages, $locale, $domain);
    $catalogue->addResource(new FileResource($resource));

    return $catalogue;
  }
}
