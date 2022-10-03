Quake 3 Stats
======
What is this?
-------------

This library allows you to receive stats from Quake 3 servers via UDP port.

🛠Installation
------------
Install Composer (https://getcomposer.org/download/)

As Library via Composer:

    composer require alxjzx100/Q3Stats

🔌 Usage
------------
````php
<?php
require 'vendor/autoload.php';

use Alxjzx100\Q3Stats\Q3Stats;

$stats = new Q3Stats('XX.XX.XX.XX', 'PORT');
var_dump( $stats->getStats() );
````