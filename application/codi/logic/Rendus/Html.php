<?php

/**
 * Class Html of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Rendus_Html
{
  private static $ACloseTags = ['div'];

  public static function parseHtmlArray(array $AHtml)
  {
    $html = '';


    // if param passed isn't an array then return it like a string
    if (!is_array($AHtml)) {
      return (string)$AHtml;
    }

    foreach ($AHtml as $tag => $AContent) {
      if (is_numeric($tag)) {
        if (is_array($AContent)) {
          $html .= self::parseHtmlArray($AContent);
        }
        else {
          $html .= (string)$AContent;
        }
      }
      else {
        list($tag, $id, $class) = self::parseHtmlTag($tag);

        if (!is_array($AContent)) {
          $content = (string)$AContent;
          $AContent = [];
          $AContent['content'] = $content;
        }
        else if (!array_key_exists('content', $AContent) && !array_key_exists('attr', $AContent)) {
          $_AContent = $AContent;
          $AContent = [];
          $AContent['content'] = $_AContent;
        }

        if (!is_null($id)) {
          $AContent['attr']['id'] = $id;
        }
        if (!is_null($class)) {
          $AContent['attr']['class'] = $class;
        }

        if (isset($AContent['content'])) {

          $html .= '<' . $tag;
          if (isset($AContent['attr']) && is_array($AContent['attr'])) {
            $html .= self::parseHtmlAttr($AContent['attr']);
          }
          $html .= ">\n\t";

          if (is_array($AContent['content'])) {
            $html .= self::parseHtmlArray($AContent['content']);
          }
          else {
            $html .= (string)$AContent['content'];
          }

          $html .= "\n\t</" . $tag . ">\n\t";
        }
        else {
          $html  .= '<' . $tag;
          if (isset($AContent['attr']) && is_array($AContent['attr'])) {
            $html .= self::parseHtmlAttr($AContent['attr']);
          }
          if (in_array($tag, self::$ACloseTags)) {
            $html .= "></$tag>\n\t";
          }
          else {
            $html .= " />\n\t";
          }

        }
      }
    }

    return $html;
  }

  private static function parseHtmlAttr(array $AAttr)
  {
    $html = '';

    foreach($AAttr as $param => $value) {
      $html .= ' ' . $param . "=\"" . $value . "\"";
    }
    return $html;
  }

  private static function parseHtmlTag($tag)
  {
    $AId = explode('#', $tag);
    $AClass = explode('.', $tag);
    $id = null;
    $class = null;

    if (count($AId) == 1) {
      $tag = $AId[0];
    }
    elseif (count($AId) == 2) {
      $tag = $AId[0];
      $AId = explode('.', $AId[1]);
      if (!is_numeric($AId[0])) {
        $id = $AId[0];
      }
    }

    if (count($AClass) > 1) {
      array_shift($AClass);
      $class = implode(' ', $AClass);
    }

    return array($tag, $id, $class);
  }
}