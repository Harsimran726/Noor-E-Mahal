<?php
$files = glob("static/*.png");
echo "TOTAL PNG IN static/: " . count($files) . "\n";
foreach ($files as $f) {
    echo "FILE: [" . $f . "]\n";
}

$uploads = glob("static/uploads/*.{jpg,png,jpeg}", GLOB_BRACE);
echo "TOTAL FILES IN static/uploads/: " . count($uploads) . "\n";
foreach ($uploads as $u) {
    echo "UPLOAD: [" . $u . "]\n";
}
