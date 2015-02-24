<?php

namespace Anonymizer\Models;

/**
* UrlModel
*
* Save/load URL in DB
*
* @author Petrenko P.O. <avallac@academ.org>
*/
class UrlModel extends Model
{
   /**
   * The table name in the database
   * 
   * @var string
   */
    public $table_name = 'urls';

   /**
   * Original user's url
   * 
   * @var string
   */
    private $url = '';

   /**
   * Hash for short url
   * 
   * @var string
   */
    private $hash = '';

    public function getUrl()
    {
        return $this->url;
    }

    public function getHash()
    {
        return $this->hash;
    }

    /**
    * Save url
    *
    * Save url in DB and return new hash
    *
    * @param string $url user's url
    * @return string 
    */
    public function saveUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        $this->url = $url;
        do {
            $this->hash = $this->genHash();
        } while ($this->checkHash($this->hash));
        $sth = $this->db->prepare('INSERT INTO `' . $this->table_name . '` (`url`,`hash`) values (:url,:hash)');
        $sth->bindParam(':hash', $this->hash);
        $sth->bindParam(':url', $this->url);
        $sth->execute();
        return $this->hash;
    }

    /**
    * Load hash
    *
    * Load record from DB
    *
    * @param string $hash hash
    */
    public function loadHash($hash)
    {
        $sth = $this->db->prepare('SELECT `url`,`hash` FROM `'.$this->table_name.'` where hash = :hash limit 1');
        $sth->bindParam(':hash', $hash);
        $sth->execute();
        $ret = $sth->fetch();
        $this->url = $ret['url'];
        $this->hash = $ret['hash'];
    }

    /**
    * Check hash
    *
    * Checking the uniqueness of hash in DB
    *
    * @param string $hash hash
    * @return boolean
    */
    public function checkHash($hash)
    {
        $sth = $this->db->prepare('SELECT `url` FROM `' . $this->table_name . '` where hash = :hash limit 1');
        $sth->bindParam(':hash', $hash);
        $sth->execute();
        if ($sth->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Gen hash
    *
    * Generate random hash (by the rules of the configuration)
    *
    * @return string
    */
    private function genHash()
    {
        $hash_length = isset($this->config['hash_length']) ? $this->config['hash_length'] : 8;
        $hash_alphabet = isset($this->config['hash_alphabet'])
            ? $this->config['hash_alphabet'] : 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($hash_alphabet);
        $string = '';
        for ($i = 0; $i < $hash_length; $i++) {
            $string .= substr($hash_alphabet, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
}
