<?php
// autoloader - lädt alle erforderlichen Class-Files

// spl - Standard PHP Library
// registriert eine anonyme funktion für den autoload als eventhandler
spl_autoload_register(function ($namespace_or_class) {
    try {
        // zerlege namespace in Teile
        $path = explode('\\', $namespace_or_class);
        // lösche den ersten Teil (prefix) und prüfe auf erlaubte prefixe
        $prefix = array_shift($path);
        if(!in_array($prefix, ['IAD']))throw new Exception("Ungültiger Namespace '$prefix'!", 0x80000001);
        // NUR FÜR ROBERT - wenn domain genutzt werden kann unbedingt auskommentieren
        #array_unshift($path, 'project');
        // erstelle relativen pfad zur datei -die datei kann auch evtl auch aus einem subdir aufgerufen werden
        // zähle die /, ziehe 1 ab und wiederhole ../, lege ergebnis an den afang von $path
        $path = array_merge(array_fill(0, substr_count($_SERVER['PHP_SELF'], '/') - 1, '..'), $path);
        // füge array $path zu string zusammen
        $path = implode('/', $path) . "%s.php";
        // prüfe, welche datei in, class, enum, etc im pfad vorhanden ist und inkludiere diese
        $included = false;
        foreach(['', '.class', '.enum', '.if', '.inc'] as $extension) {
            $currentPath = strtolower(sprintf($path, $extension));
            if(file_exists($currentPath)) {
                include_once $currentPath;
                $included = true;
                break;
            }
        }
        if(!$included)throw new Exception("Fatal Error: $path konnte nicht gefunden werden!", 0x80000002);
    }catch(Exception $e) {
        var_dump($e);
        exit();
    }
});