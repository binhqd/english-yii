<?php
$conn_id = ftp_connect('192.168.1.25');
$login_result = ftp_login($conn_id, 'jl', '123123');
$file_name = 'main.php';
$file = dirname(__FILE__) . "/$file_name";//$_FILES['myfile']['tmp_name'];
if (ftp_put($conn_id, '/var/www/uploads/'.$file_name , $file, FTP_BINARY))
 echo "successfully uploaded $file\n";
else
 echo "There was a problem while uploading $file\n";
ftp_close($conn_id);
