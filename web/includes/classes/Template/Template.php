<?php

namespace XTC\Template;

use Symfony\Bridge\Twig\NodeVisitor\TranslationDefaultDomainNodeVisitor;
use Symfony\Bridge\Twig\NodeVisitor\TranslationNodeVisitor;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use XTC\Translation\Translation;

class Template {
  private $_twig = null;
  private $_smarty = null;
  private $_tpl_vars = array();
  private $_filters = array();

  public function assign($tpl_var, $value = null){
    if (is_array($tpl_var)){
      foreach ($tpl_var as $key => $val) {
        if ($key != '') {
          $this->_tpl_vars[$key] = $val;
        }
      }
    }
    else {
      if ($tpl_var != ''){
        $this->_tpl_vars[$tpl_var] = $value;
      }
    }
  }


  public function clear_assign($tpl_var){
    if (is_array($tpl_var)){
      foreach ($tpl_var as $curr_var){
        unset($this->_tpl_vars[$curr_var]);
      }
    }
    else {
      unset($this->_tpl_vars[$tpl_var]);
    }
  }

  /**
   * load a filter of specified type and name
   *
   * @param string $type filter type
   * @param string $name filter name
   */
  public function load_filter($type, $name){
    $this->_filters[] = array('type' => $type, 'name' => $name);
  }

  /**
   * test to see if valid cache exists for this template
   *
   * @param string $tpl_file name of template file
   * @param string $cache_id
   * @param string $compile_id
   * @return string|false results of {@link _read_cache_file()}
   */
  public function is_cached($tpl_file, $cache_id = null, $compile_id = null)
  {
    if($this->getTemplateEngineType($tpl_file) === 'smarty'){
      $smarty = $this->getSmarty();
      return $smarty->is_cached($tpl_file, $cache_id, $compile_id);
    }
    return false;
  }

  /**
   * executes & displays the template results
   *
   * @param string $resource_name
   * @param string $cache_id
   * @param string $compile_id
   */
  public function display($resource_name, $cache_id = null, $compile_id = null){
    $this->fetch($resource_name, $cache_id, $compile_id, true);
  }

  /**
   * executes & returns or displays the template results
   *
   * @param string $resource_name
   * @param string $cache_id
   * @param string $compile_id
   * @param boolean $display
   */
  public function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false){
    $engine_type = $this->getTemplateEngineType($resource_name);

    if($engine_type === 'twig'){
      $template = $this->getTwig()->loadTemplate($resource_name);
      $rendered_template = $template->render($this->_tpl_vars);
    }
    else {
      $smarty = $this->getSmarty();
      foreach($this->_tpl_vars as $name => $value){
        $smarty->assign($name, $value);
      }
      foreach($this->_filters as $filter){
        $smarty->load_filter($filter['type'], $filter['name']);
      }
      $rendered_template = $smarty->fetch($resource_name, $cache_id, $compile_id);
    }

    if($display){
      echo $rendered_template;
      return true;
    }
    return $rendered_template;
  }

  private function getTemplateEngineType($resource_name){
    if(substr($resource_name, -4) === 'twig'){
      return 'twig';
    }
    return 'smarty';
  }

  private function getSmarty(){
    if($this->_smarty === null){
      $this->_smarty = new \smarty();
    }
    return $this->_smarty;
  }

  private function getTwig(){
    if($this->_twig === null){
      $twig_config = array(
        'cache' => DIR_FS_DOCUMENT_ROOT.'templates_c/twig/',
        'charset' => 'iso-8859-15',
        'auto_reload' => true
      );
      $this->_twig = new \Twig_Environment(new \Twig_Loader_Filesystem(DIR_FS_DOCUMENT_ROOT.'templates/'), $twig_config);

      $translator = Translation::getTranslator();
      $this->_twig->addExtension(new TranslationExtension($translator));
    }
    return $this->_twig;
  }
}