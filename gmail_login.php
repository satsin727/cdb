<?php

$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'resume4cdb@gmail.com';
$password = 'jdwrhjobayhmzzzm';

/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

?>