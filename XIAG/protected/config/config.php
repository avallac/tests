<?php
return array(
    'base_url' => 'http://url.avallac.ru',                     // need only for ajax output
    'db' => array(
        'dsn' => 'mysql:dbname=link;host=localhost',
        'username' => 'root',
        'password' => '123',
    ),
    'hash_length' => 8,                                         // the length of the hash in the short link
    'hash_alphabet' => 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789', // allowed characters in hash
    'messages' => array(
        'try_again' => 'Please try again later.',
        'cant_create' => 'Can\'t create url.',
        'query_empty' => 'Query can\'t be empty.'
    ),
    'forbidden_tests' => 0                                      // forbidden test's controller
);
