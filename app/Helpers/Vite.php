<?php

// if (!function_exists('vite')) {
//     function vite(string $entry): string
//     {
//         // Путь к manifest.json
//         $manifestPath = public_path('build/manifest.json');

//         if (!file_exists($manifestPath)) {
//             throw new Exception('Файл manifest.json не найден. Выполни npm run build');
//         }

//         $manifest = json_decode(file_get_contents($manifestPath), true);

//         if (!isset($manifest[$entry])) {
//             throw new Exception("Входная точка '{$entry}' не найдена в manifest.json");
//         }

//         $asset = $manifest[$entry];
//         $filePath = asset('build/' . $asset['file']);

//         // Если это JS-файл — добавляем как script
//         if (str_ends_with($entry, '.js')) {
//             return "<script type=\"module\" src=\"{$filePath}\"></script>";
//         }

//         // Если это CSS-файл — добавляем как link
//         if (str_ends_with($entry, '.css')) {
//             return "<link rel=\"stylesheet\" href=\"{$filePath}\">";
//         }

//         return '';
//     }
// }

if (!function_exists('vite_build')) {
    function vite_build(string $entry): string
    {
        // Путь к manifest.json
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            throw new Exception("Файл manifest.json не найден. Выполни npm run build");
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (!isset($manifest[$entry])) {
            throw new Exception("Входная точка '{$entry}' не найдена в manifest.json");
        }

        $asset = $manifest[$entry];
        $filePath = asset('build/' . $asset['file']);

        return "<script type='module' src='{$filePath}'></script>";
    }
}
