<?php

interface profile_Interface
{
    function getProfileID();
    function setProfileID($profileID);
    function getPassword();
    function setPassword($password);
    function getProfilePicture();
    function setProfilePicture($profilePicture);
    function getEmail();
    function setEmail($email);
    function getLastLogin();
    function setLastLogin($lastLogin);
    function getLoginIP();
    function setLoginIP($loginIP);
    function getAccountType();
    function setAccountType($accountType);
    function getGameStatus();
    function setGameStatus($gameStatus);
    function getAvatar();
    function setAvatar($avatar);
    function getAchievements();
    function setAchievements($achievements);
    function addAchievements($achievements);
    function getBio();
    function setBio($var);
    function getCountry();
    function setCountry($var);
    function getGender();
    function setGender($var);
    function getAge();
    function setAge($var);
    function getPlayStatistics();
    function setPlayStatistics($var);
    function addPlayStatistics($var,$count);
    function removePlayStatistics($var,$count);
    function getUploadSecurity();
    function setUploadSecurity();
    function getPasswordRecovery();
    function setPasswordRecovery();
    function getPasswordRecoveryTimer();
    function setPasswordRecoveryTimer();
    function getCookieKey();
    function setCookieKey();
}