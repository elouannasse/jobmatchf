<?php
/**
 * Script pour réparer l'accès aux fichiers CV
 * ==========================================
 * Ce script résout les problèmes d'accès aux fichiers CV stockés dans le dossier storage.
 * Il vérifie et corrige:
 * 1. Les liens symboliques entre storage/app/public et public/storage
 * 2. Les permissions des dossiers
 * 3. La présence du dossier cvs
 */

// Afficher un message d'en-tête
echo "=================================================================\n";
echo "    SCRIPT DE RÉPARATION D'ACCÈS AUX CV - APPLICATION JOBMATCH\n";
echo "=================================================================\n\n";

// Fonction pour afficher un message de statut
function showStatus($message, $success = true) {
    echo ($success ? "  [✓] " : "  [✗] ") . $message . "\n";
}

// Fonction pour afficher une section
function showSection($title) {
    echo "\n" . $title . "\n" . str_repeat('-', strlen($title)) . "\n";
}

// Définir les chemins importants
$basePath = __DIR__;
$storagePath = $basePath . '/storage';
$publicPath = $basePath . '/public';
$storageAppPath = $storagePath . '/app';
$storagePublicPath = $storageAppPath . '/public';
$cvsFolderPath = $storagePublicPath . '/cvs';
$storageSymlinkPath = $publicPath . '/storage';

// Vérifier si le script s'exécute avec les privilèges administratifs (Windows)
showSection("Vérification des privilèges");
if (PHP_OS_FAMILY === 'Windows') {
    // Sur Windows, nous ne pouvons pas vraiment vérifier les privilèges administratifs de manière fiable en PHP
    echo "  [!] Sous Windows, ce script doit être exécuté avec des privilèges administratifs\n";
    echo "      pour pouvoir créer des liens symboliques.\n";
    echo "  [!] Si vous rencontrez des erreurs, exécutez à nouveau ce script en tant qu'administrateur.\n";
} else {
    // Sur Unix/Linux, on peut vérifier si l'utilisateur est root
    if (posix_getuid() !== 0) {
        showStatus("Ce script n'est pas exécuté en tant que root/superutilisateur", false);
        echo "      Vous pourriez rencontrer des problèmes de permissions.\n";
        echo "      Exécutez ce script avec sudo si nécessaire.\n";
    } else {
        showStatus("Le script s'exécute avec des privilèges élevés");
    }
}

// Vérifier et créer les dossiers nécessaires
showSection("Vérification des dossiers de stockage");

$dirsToCheck = [
    $storageAppPath => "Dossier storage/app",
    $storagePublicPath => "Dossier storage/app/public",
    $cvsFolderPath => "Dossier storage/app/public/cvs"
];

foreach ($dirsToCheck as $dir => $label) {
    if (!file_exists($dir)) {
        showStatus("$label n'existe pas", false);
        echo "      Création du dossier $dir...\n";
        
        if (mkdir($dir, 0755, true)) {
            showStatus("$label créé avec succès");
        } else {
            showStatus("Impossible de créer $label", false);
            echo "      Vérifiez les permissions et exécutez à nouveau le script.\n";
        }
    } else {
        showStatus("$label existe");
        
        // Vérifier si le dossier est accessible en lecture/écriture
        if (!is_readable($dir)) {
            showStatus("$label n'est pas lisible", false);
            chmod($dir, 0755);
            showStatus("Permissions de lecture ajoutées à $label");
        }
        
        if (!is_writable($dir)) {
            showStatus("$label n'est pas modifiable", false);
            chmod($dir, 0755);
            showStatus("Permissions d'écriture ajoutées à $label");
        }
    }
}

// Vérifier et recréer le lien symbolique
showSection("Vérification du lien symbolique public/storage");

$symlinkExists = false;
$symlinkCorrect = false;

if (is_link($storageSymlinkPath)) {
    $symlinkExists = true;
    $target = readlink($storageSymlinkPath);
    showStatus("Le lien symbolique existe et pointe vers: $target");
    
    // Vérifier si le lien pointe vers le bon dossier
    if (realpath($target) === realpath($storagePublicPath)) {
        $symlinkCorrect = true;
        showStatus("Le lien symbolique est correctement configuré");
    } else {
        showStatus("Le lien symbolique pointe vers le mauvais dossier", false);
    }
} elseif (is_dir($storageSymlinkPath)) {
    $symlinkExists = true;
    showStatus("Le dossier public/storage existe en tant que dossier (pas un lien symbolique)", false);
} else {
    showStatus("Le lien symbolique n'existe pas", false);
}

// Si le symlink n'existe pas ou est incorrect, le supprimer et le recréer
if (!$symlinkExists || !$symlinkCorrect) {
    // Supprimer le lien/dossier existant s'il existe
    if ($symlinkExists) {
        echo "  [!] Suppression du lien/dossier existant...\n";
        
        if (PHP_OS_FAMILY === 'Windows') {
            if (is_dir($storageSymlinkPath)) {
                // Sous Windows, nous devons utiliser rmdir pour supprimer un lien symbolique de dossier
                exec('rmdir ' . escapeshellarg($storageSymlinkPath));
            } else {
                // Pour un fichier ou un lien symbolique de fichier, utiliser del/erase
                exec('del ' . escapeshellarg($storageSymlinkPath));
            }
        } else {
            // Sous Unix/Linux, unlink fonctionne pour les liens symboliques
            unlink($storageSymlinkPath);
        }
    }
    
    // Créer le nouveau lien symbolique
    echo "  [!] Création d'un nouveau lien symbolique...\n";
    
    $success = false;
    if (PHP_OS_FAMILY === 'Windows') {
        // Sous Windows, nous utilisons mklink /D pour les liens symboliques de dossier
        exec('mklink /D ' . escapeshellarg($storageSymlinkPath) . ' ' . escapeshellarg($storagePublicPath), $output, $returnCode);
        $success = ($returnCode === 0);
    } else {
        // Sous Unix/Linux, symlink fonctionne directement
        $success = symlink($storagePublicPath, $storageSymlinkPath);
    }
    
    if ($success) {
        showStatus("Lien symbolique créé avec succès");
    } else {
        showStatus("Échec de la création du lien symbolique", false);
        echo "      Il est possible que vous n'ayez pas les privilèges nécessaires.\n";
        echo "      Sous Windows, exécutez ce script en tant qu'administrateur.\n";
        echo "      Sous Linux/Unix, utilisez sudo.\n";
        
        // Proposer une solution alternative
        echo "\n  [!] Solution alternative: exécutez la commande artisan suivante:\n";
        echo "      php artisan storage:link\n";
    }
}

// Test de fonctionnalité
showSection("Test d'accès aux fichiers");

// Créer un fichier de test
$testFile = $cvsFolderPath . '/test_access.txt';
$testContent = 'Fichier de test créé le ' . date('Y-m-d H:i:s');

if (file_put_contents($testFile, $testContent)) {
    showStatus("Fichier de test créé dans le dossier cvs");
    
    // Vérifier l'accès via le lien symbolique
    $testFileViaSymlink = $storageSymlinkPath . '/cvs/test_access.txt';
    
    if (file_exists($testFileViaSymlink)) {
        showStatus("Le fichier est accessible via le lien symbolique");
        
        // Lire le contenu pour vérifier qu'il est identique
        $content = file_get_contents($testFileViaSymlink);
        if ($content === $testContent) {
            showStatus("Le contenu du fichier est accessible et correct");
        } else {
            showStatus("Le contenu du fichier est différent via le lien symbolique", false);
        }
    } else {
        showStatus("Le fichier n'est pas accessible via le lien symbolique", false);
    }
    
    // Supprimer le fichier de test
    unlink($testFile);
    showStatus("Fichier de test supprimé");
} else {
    showStatus("Impossible de créer un fichier de test dans le dossier cvs", false);
    echo "      Vérifiez les permissions du dossier.\n";
}

// Recommandations finales
showSection("Recommandations");

echo "  1. Assurez-vous que les permissions des dossiers storage et public sont correctes:\n";
echo "     - Le serveur web (www-data, apache, nginx, etc.) doit avoir accès en lecture/écriture\n";
echo "     - Sous Linux: chmod -R 755 storage public\n\n";

echo "  2. Si vous utilisez un hébergement partagé sans accès aux liens symboliques:\n";
echo "     - Copiez manuellement les fichiers de storage/app/public vers public/storage\n";
echo "     - Ou modifiez config/filesystems.php pour utiliser un disque public sans lien symbolique\n\n";

echo "  3. Si les problèmes persistent, vérifiez la configuration de votre serveur web:\n";
echo "     - Apache: Vérifiez les règles de réécriture dans .htaccess\n";
echo "     - Nginx: Vérifiez la configuration pour servir les fichiers statiques\n\n";

echo "=================================================================\n";
echo "                     FIN DU SCRIPT DE RÉPARATION\n";
echo "=================================================================\n";
