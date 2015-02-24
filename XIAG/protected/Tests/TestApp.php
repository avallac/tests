<?php


namespace Tests;

use Anonymizer\Models\UrlModel;

class TestApp
{
    private $testurl = 'http://testing.com';
    private $testurl_invalid = 'http://testing.co=';
    private $db;
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function runS1()
    {
        print "==========================<br>";
        print "Section 1: Config file<br>";
        print "Test 1: Configuration array - ";
        if (is_array($this->config)) {
            print "OK<br>";
        } else {
            print "Configuration file don't load";
            die();
        }
        print "Test 2: DB settings - ";
        if (is_array($this->config['db'])) {
            print "OK<br>";
        } else {
            print "don't find. Please set 'db' section.";
            die();
        }
        print "Test 3: Hash length - ";
        if (isset($this->config['hash_length'])) {
            if (is_int($this->config['hash_length']) && $this->config['hash_length'] > 0) {
                print "OK<br>";
            } else {
                print "Wrong value 'hash_length'. Using default value.<br>";
            }
        } else {
            print "'hash_length' don't find. Using default value.<br>";
        }
        print "Test 4: Hash alphabet - ";
        if (isset($this->config['hash_alphabet'])) {
            print "OK<br>";
        } else {
            print "'hash_alphabet' don't find. Using default value.<br>";
        }
        print "Test 5: Base url - ";
        if (isset($this->config['base_url'])) {
            if (filter_var($this->config['base_url'], FILTER_VALIDATE_URL)) {
                print "OK<br>";
            } else {
                print "Wrong value 'base_url'.<br>";
                die;
            }
        } else {
            print "don't find. Please set 'base_url'.";
            die;
        }
    }

    public function runS2()
    {
        print "==========================<br>";
        print "Section 2: database<br>";
        print "Test 1: Connect to database - ";
        try {
            $db = $this->config['db'];
            $this->db = new \PDO($db['dsn'], $db['username'], $db['password']);
        } catch (\PDOException $e) {
            print "Can't connect to database. Error: '" . $e->getMessage() . "'";
            die();
        }
        print "OK<br>";

        print "Test 2: Create UrlModel - ";
        try {
            $urlModel = new UrlModel($this->config);
        } catch (\PDOException $e) {
            print 'Can\'t create url object.';
            die();
        }
        print "OK<br>";

        print "Test 3: Check table in database - ";
        try {
            $result = $this->db->query('SELECT 1 FROM `' . $urlModel->table_name . '` LIMIT 1');
        } catch (PDOException $e) {
            print 'Can\'t create query';
            die();
        }
        if ($result) {
            print "OK<br>";
        } else {
            print "table '" . $urlModel->table_name . "' doesn't exist<br>";
            die();
        }

        print "Test 4: Check invalid URL - ";
        if (!$urlModel->saveUrl($this->testurl_invalid)) {
            print "OK<br>";
        } else {
            print "terribly\n";
            die();
        }

        print "Test 4: Create short URL - ";
        if ($hash = $urlModel->saveUrl($this->testurl)) {
            print "OK<br>";
        } else {
            print "Can't create short url\n";
            die();
        }

        print "Test 5: Search short URL in db - ";
        if ($urlModel->checkHash($hash)) {
            print "OK<br>";
        } else {
            print "Can't find hash\n";
            die();
        }

        unset($urlModel);
        $urlModel = new UrlModel($this->config);
        $urlModel->loadHash($hash);
        print "Test 6:  Check URL - ";
        if ($urlModel->getUrl() === $this->testurl) {
            print "OK<br>";
        } else {
            print "URL isn't equal original\n";
            die();
        }

        print "Remove test URL from db<br>";
        $sth = $this->db->prepare('delete FROM `' . $urlModel->table_name . '` where hash = :hash');
        $sth->bindParam(':hash', $hash);
        $sth->execute();
    }

    public function ok()
    {
        print "==========================<br>";
        print "Tests completed. Please set 'forbidden_tests' to 1.";
    }
}