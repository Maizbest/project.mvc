<?php

echo "<h1>404</h1>";
if (file_exists('debug')) {
    echo '<h3>' . $e->getMessage() . '</h3><br>';
}
