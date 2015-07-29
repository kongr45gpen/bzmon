<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Process\Process;

$username = 'callsign';
$password = 'password';

$server = 'localhost';
$port   = 5154;

$process = new Process("bzadmin -ui stdout $username:$password@$server:$port");
$process->setTimeout(null);
$process->run(function ($type, $buffer) use ($process) {
    $buffer = explode('\n', trim($buffer));

    foreach ($buffer as $line) {
        $line = trim($line);
        echo "$line\n";

        $pattern = '/^\[Admin\] SERVER: \*\*"([^"]*)" reports: (.*)/';
        $matches = [];

        if (preg_match($pattern, $line, $matches)) {
            $author = $matches[1];
            $message = $matches[2];

            echo "$author reports: $message @ $server:$port";
        }
    }
});
