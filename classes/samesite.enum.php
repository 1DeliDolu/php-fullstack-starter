<?php
/* 
Namespaces sind vgl. mit Packages in Java
sie bestehen aus einem Prefix gefolgt vom Pfad ab root zur Datei
die Datei selbst ist nicht Bestandteil des NS
über den NS kann die Datei per autoloader includiert werden
Hinweis: bei NS ist die Groß-/Kleinschreibung unbedingt zu beachten; UNIX wirft sonst Fatal Error's
*/
namespace IAD\classes;

// direkte aufrufe abbrechen
defined('_IAD') or die();


enum SameSite:string {
    case Strict = 'Strict';
    case Lax = 'Lax';
    case None = 'None';
}