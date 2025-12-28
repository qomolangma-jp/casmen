<?php
echo "PHP is working!";
echo "<br>PHP Version: " . phpversion();
echo "<br>Memory Limit: " . ini_get('memory_limit');
echo "<br>Upload Max Filesize: " . ini_get('upload_max_filesize');
echo "<br>Post Max Size: " . ini_get('post_max_size');
