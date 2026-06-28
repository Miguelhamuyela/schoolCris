<?php
// Verifica se a extensão GD está habilitada
if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
    // Definir o tipo de conteúdo como uma imagem PNG
    header('Content-Type: image/png');

    // Largura e altura da imagem
    $width = 300;
    $height = 300;

    // Criar uma nova imagem
    $image = imagecreatetruecolor($width, $height);

    // Definir cores
    $background_color = imagecolorallocate($image, 240, 240, 240); // Cinza claro para o fundo
    $circle_color = imagecolorallocate($image, 255, 127, 0); // Laranja para o círculo (mesma cor do layout)

    // Preencher o fundo
    imagefill($image, 0, 0, $background_color);

    // Desenhar um círculo no centro
    $center_x = $width / 2;
    $center_y = $height / 2;
    $circle_radius = $height / 3;
    imagefilledellipse($image, $center_x, $center_y, $circle_radius, $circle_radius, $circle_color);

    // Adicionar texto 'IP' (Instituto Politécnico)
    $text = 'IP';
    $font_size = 5; // Tamanho da fonte
    imagestring($image, $font_size, $center_x - 10, $center_y - 8, $text, $background_color);

    // Enviar a imagem para o navegador
    imagepng($image);

    // Liberar memória
    imagedestroy($image);
} else {
    // Se GD não estiver disponível, redireciona para uma imagem estática
    header('Location: /dashboard/assets/img/undraw_profile.svg');
}
?> 