<?php
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

// Chercher le fichier User_dashboard.php
$paths = [
    __DIR__ . '/application/controllers/User_dashboard.php',
    __DIR__ . '/application/modules/Home/controllers/User_dashboard.php',
    __DIR__ . '/application/modules/User_dashboard/controllers/User_dashboard.php',
];

foreach ($paths as $path) {
    echo "Chemin: " . $path . " - " . (file_exists($path) ? "EXISTE" : "N'EXISTE PAS") . "<br>";
}
?>