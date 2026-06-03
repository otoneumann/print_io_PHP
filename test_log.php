<?php
echo "PHP User: " . get_current_user();
echo "<br>PHP Effective User: " . posix_getpwuid(posix_geteuid())['name'];
?>