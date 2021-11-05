<?php

namespace App\Helpers;

class LdapLogin
{
    protected $ldap_host;
    protected $ldap_dn;
    protected $ldap_usr_dom;
    protected $ldap_contexts;
    
    public function __construct() {
        $this->ldap_host = get_config('ldap_host');
        $this->ldap_dn = get_config('ldap_dn');
        $this->ldap_usr_dom = get_config('ldap_usr_dom');
        $this->ldap_contexts = get_config('ldap_contexts');
    }
    
    public function login($username, $password) {
        if(empty($username) || empty($password)) {
            return false;
        }
    
        $ldapconnection = $this->ldap_connect();
        $ldap_user_dn = $this->find_userdn($ldapconnection, $username);
    
        // If ldap_user_dn is empty, user does not exist
        if (!$ldap_user_dn) {
            @ldap_close($ldapconnection);
            \Log::error('Cannot find ' . $username);
            return false;
        }
    
        // Try to bind with current username and password
        $ldap_login = @ldap_bind($ldapconnection, $ldap_user_dn, $password);
        if (!$ldap_login) {
            \Log::error('Error password ' . $username);
            return false;
        }
    
        @ldap_close($ldapconnection);
    
        return $ldap_login;
    }
    
    protected function ldap_connect() {
        $ldap = \ldap_connect($this->ldap_host);
        \ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
        \ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
    
        if ($this->ldap_dn) {
            $bind = @\ldap_bind($ldap, $this->ldap_dn, $this->ldap_usr_dom);
        }
        else {
            $bind = @\ldap_bind($ldap);
        }
    
        if ($bind) {
            return $ldap;
        }
        
        \Log::error('Cannot connect to server: ' . $this->ldap_host);
        return false;
    }
    
    protected function find_userdn($ldapconnection, $username) {
        $ldap_user_dn = false;
    
        $contexts = explode(';', $this->ldap_contexts);
        $objectclass = '(objectClass=user)';
        $search_attrib = 'samaccountname';
        $search_sub = true;
        
        foreach ($contexts as $context) {
            $context = trim($context);
            if (empty($context)) {
                continue;
            }
        
            if ($search_sub) {
                $ldap_result = @\ldap_search($ldapconnection, $context,
                    '(&'.$objectclass.'('.$search_attrib.'='. $this->ldap_filter_addslashes($username).'))',
                    array($search_attrib));
            } else {
                $ldap_result = @\ldap_list($ldapconnection, $context,
                    '(&'.$objectclass.'('.$search_attrib.'='. $this->ldap_filter_addslashes($username).'))',
                    array($search_attrib));
            }
        
            if (!$ldap_result) {
                continue;
            }
        
            $entry = \ldap_first_entry($ldapconnection, $ldap_result);
            if ($entry) {
                $ldap_user_dn = \ldap_get_dn($ldapconnection, $entry);
                break;
            }
        }
    
        return $ldap_user_dn;
    }
    
    protected function ldap_filter_addslashes($text) {
        $text = str_replace('\\', '\\5c', $text);
        $text = str_replace(array('*',    '(',    ')',    "\0"),
            array('\\2a', '\\28', '\\29', '\\00'), $text);
        return $text;
    }
}