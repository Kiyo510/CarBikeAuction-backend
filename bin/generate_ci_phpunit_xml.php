<?php

$baseDir = realpath('./');
$files = array_slice($argv, 1);
$xmlFileStringData = [];
foreach ($files as $file) {
    $xmlFileStringData[] = "<file>{$baseDir}/{$file}</file>";
}
$testFileString = implode("\n", $xmlFileStringData);
$template = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="{$baseDir}vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="{$baseDir}/vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Test Case">
            {$testFileString}
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>"{$baseDir}/app"</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_DATABASE" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_STORE" value="array"/>
        <!-- <env name="DB_CONNECTION" value="sqlite"/> -->
        <!-- <env name="DB_DATABASE" value=":memory:"/> -->
        <env name="MAIL_MAILER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
XML;

file_put_contents("/tmp/ci_phpunit.xml", $template);
