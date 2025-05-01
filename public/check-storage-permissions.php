<?php
// Ce script vérifie et corrige les permissions des dossiers de stockage

// Chemin vers les dossiers de stockage
$storagePath = __DIR__ . '/../storage';
$publicStoragePath = __DIR__ . '/../storage/app/public';
$cvsPath = __DIR__ . '/../storage/app/public/cvs';

// Fonction pour vérifier et corriger les permissions d'un dossier
function checkAndFixPermissions($path, $createIfNotExists = true) {
    echo "Vérification du dossier: $path\n";
    
    // Vérifier si le dossier existe
    if (!file_exists($path)) {
        if ($createIfNotExists) {
            echo "  Création du dossier...\n";
            if (mkdir($path, 0755, true)) {
                echo "  Dossier créé avec succès.\n";
            } else {
                echo "  Erreur lors de la création du dossier.\n";
                return false;
            }
        } else {
            echo "  Le dossier n'existe pas et ne sera pas créé.\n";
            return false;
        }
    } else {
        echo "  Le dossier existe.\n";
    }
    
    // Vérifier si le dossier est accessible en lecture et écriture
    if (is_readable($path)) {
        echo "  Le dossier est lisible.\n";
    } else {
        echo "  Le dossier n'est pas lisible. Correction des permissions...\n";
        if (chmod($path, 0755)) {
            echo "  Permissions corrigées.\n";
        } else {
            echo "  Erreur lors de la correction des permissions.\n";
        }
    }
    
    if (is_writable($path)) {
        echo "  Le dossier est modifiable.\n";
    } else {
        echo "  Le dossier n'est pas modifiable. Correction des permissions...\n";
        if (chmod($path, 0755)) {
            echo "  Permissions corrigées.\n";
        } else {
            echo "  Erreur lors de la correction des permissions.\n";
        }
    }
    
    return true;
}

// Vérifier et corriger les permissions des dossiers de stockage
echo "=== Vérification des dossiers de stockage ===\n";
checkAndFixPermissions($storagePath);
checkAndFixPermissions($publicStoragePath);
checkAndFixPermissions($cvsPath);

// Vérifier que le lien symbolique existe
$linkPath = __DIR__ . '/storage';
echo "\n=== Vérification du lien symbolique ===\n";
if (is_link($linkPath) || is_dir($linkPath)) {
    echo "Le lien symbolique existe.\n";
    
    // Vérifier si le lien symbolique pointe vers le bon dossier
    $target = readlink($linkPath);
    echo "Le lien symbolique pointe vers: $target\n";
    
    if ($target == $publicStoragePath || realpath($linkPath) == realpath($publicStoragePath)) {
        echo "Le lien symbolique est correct.\n";
    } else {
        echo "Le lien symbolique ne pointe pas vers le bon dossier.\n";
        echo "Il devrait pointer vers: $publicStoragePath\n";
        
        // Supprimer et recréer le lien symbolique
        echo "Suppression et recréation du lien symbolique...\n";
        if (PHP_OS_FAMILY === 'Windows') {
            exec('rmdir ' . escapeshellarg($linkPath));
            exec('mklink /D ' . escapeshellarg($linkPath) . ' ' . escapeshellarg($publicStoragePath));
        } else {
            unlink($linkPath);
            symlink($publicStoragePath, $linkPath);
        }
        
        echo "Lien symbolique recréé.\n";
    }
} else {
    echo "Le lien symbolique n'existe pas. Création...\n";
    
    if (PHP_OS_FAMILY === 'Windows') {
        exec('mklink /D ' . escapeshellarg($linkPath) . ' ' . escapeshellarg($publicStoragePath));
    } else {
        symlink($publicStoragePath, $linkPath);
    }
    
    echo "Lien symbolique créé.\n";
}

echo "\nTerminé.\n";
