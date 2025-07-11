<?php
// config.php
define('BASE_URL', 'http://localhost/silinen');

function base_url($path = '') {
    return BASE_URL . ltrim($path, '/');
}
?>