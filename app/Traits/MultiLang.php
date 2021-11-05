<?php

namespace App\Traits;

trait MultiLang
{
    public function getLang($filed)
    {
        if (\App::getLocale() === 'en') {
            if ($this->{$filed} . '_en') {
                return $this->{$filed} . '_en';
            }
        }
        return $this->{$filed};
    }
}