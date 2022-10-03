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

use alxjzx100\Q3Stats;

$stats = new Q3Stats();
var_dump( $stats->getStats('XX.XX.XX.XX', 'PORT') );
````