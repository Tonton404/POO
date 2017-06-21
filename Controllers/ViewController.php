<?php

class View
{
  private $title;
  private $content;
  private $style;
  private $directory = "elements/";
  private $styleExtension = ".css";
  private $templateExtension = ".html"; //saisie en dur, la variable est implantée dans le reste du directory.change la valeur ici, le reste se fait tout seul #magie
  private $templateBase = "templates/template.php";

  public function loadHtml($fileName) //cette méthode reprend le nom du fichier HTML dont on va afficher le contenu
  {
    $html = "";
    $html .= "<div id='" . str_replace($this->templateExtension, "", $fileName) . "'>";//la div prend le nom du fichier retourné
    $html .= file_get_contents($this->directory . $fileName);
    $html .= "</div>";

    return $html;
  }

  public function loadCss($fileName)
  {
    $css ="<link rel='stylesheet' href:'". $this->directory . $fileName . "'>";
    return $css;
  }

  public function renderPage($title) //cette méthode cherche l'ensemble des fichiers HTML dans tout le directory
  {
    $template = file_get_contents($this->directory . $this->templateBase);
    $css="";
    $html = "";
    $directoryList = scandir($this->directory); // scandir passe en boucle (foreach) sur le directory et renvoi un array
    foreach ($directoryList as $key => $value) {
      if (strpos($value, $this->templateExtension) !== False) {
        $html .= $this->loadHtml($value); //concatenation des résultats de la méthode précédente
      }
      if (strpos($value, $this->styleExtension) !== False)  {
        $css .= $this->loadCss($value);
      }
    }
    $template = str_replace('%%TITLE%%', $title, $template);
    $template = str_replace('%%CONTENT%%', $html, $template); //on remplace le CONTENT par le html récupéré juste au dessus (foreach + if)
    $template = str_replace('%%STYLE%%', $css, $template);
    $template = str_replace('%%MENU%%', file_get_contents($this->directory . "templates/menu.php"), $template);
    echo $template;
  }
}
