<?php
// Ce script crée ou répare le lien symbolique entre storage/app/public et public/storage

// Chemin vers le répertoire de stockage public
$targetFolder = __DIR__ . '/../storage/app/public';

// Chemin vers le lien symbolique dans le dossier public
$linkFolder = __DIR__ . '/storage';

// Supprimer le lien symbolique existant s'il existe
if (is_dir($linkFolder) || is_link($linkFolder)) {
    if (PHP_OS_FAMILY === 'Windows') {
        // Windows nécessite une approche différente pour les liens symboliques
        exec('rmdir ' . escapeshellarg($linkFolder));
    } else {
        unlink($linkFolder);
    }
}

// Créer le lien symbolique
if (PHP_OS_FAMILY === 'Windows') {
    // Sur Windows, nous utilisons la commande mklink
    exec('mklink /D ' . escapeshellarg($linkFolder) . ' ' . escapeshellarg($targetFolder));
    echo "Lien symbolique créé sous Windows.\n";
} else {
    // Sur Unix/Linux
    symlink($targetFolder, $linkFolder);
    echo "Lien symbolique créé sous Unix/Linux.\n";
}

// Vérifier que le lien symbolique a été créé
if (is_link($linkFolder) || is_dir($linkFolder)) {
    echo "Le lien symbolique a été créé avec succès.\n";
} else {
    echo "Échec de la création du lien symbolique. Veuillez vérifier les permissions.\n";
}

// S'assurer que le dossier cvs existe dans storage/app/public
$cvsFolder = __DIR__ . '/../storage/app/public/cvs';
if (!is_dir($cvsFolder)) {
    mkdir($cvsFolder, 0755, true);
    echo "Dossier cvs créé.\n";
}

echo "Terminé.\n";
