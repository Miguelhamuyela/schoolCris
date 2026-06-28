<?php

if (!function_exists('image_data_url')) {
    /**
     * Converte uma imagem em um data URL para uso em PDFs
     *
     * @param string $path Caminho para a imagem
     * @return string Data URL
     */
    function image_data_url($path) {
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return '';
    }
} 