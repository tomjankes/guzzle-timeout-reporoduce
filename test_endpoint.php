<?php
/**
 * Put this file in document root
 *
 * Simulates web service that takes long to respond
 */

$timeout_in_miliseconds = 2000;
usleep($timeout_in_miliseconds * 1000);
echo "Done.";
