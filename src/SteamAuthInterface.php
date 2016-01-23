<?php

namespace EliuFlorez\SteamAuth;

interface SteamAuthInterface
{
    public function redirect();
    public function validate();
    public function getAuthUrl();
    public function getSteamId();
    public function getUserInfo();
}
