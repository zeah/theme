<?php
$logger = EmoLogger::get_instance();
$logger->welcome_user();

echo '<!DOCTYPE html><html lang="no"><head>';
wp_head();
echo '<meta name="viewport" content="width=device-width, initial-scale=1"></head><body>';