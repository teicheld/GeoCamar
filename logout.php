<?php
session_start();
?>
<html>
<body>
<?php
session_unset();
session_destroy();
require 'index.php';
?>
</body>
</html>	
