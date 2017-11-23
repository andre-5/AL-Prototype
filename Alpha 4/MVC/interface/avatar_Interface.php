<?php
interface avatar_Interface
{
    function getAvatarID();
    function setAvatarID($var);
    function getProfileID();
    function setProfileID($var);
    function getMapID();
    function setMapID($var);
    function getStamina();
    function setStamina($var);
    function getMaxStamina();
    function setMaxStamina($var);
    function getZoneID();
    function setZoneID($var);
    function getInventory();
    function setInventory($var);
    function addInventoryItem($var);
    function removeInventoryItem($var);
    function getMaxInventorySlots();
    function setMaxInventorySlots($var);
    function getPartyID();
    function setPartyID($var);
    function getReady();
    function toggleReady($var);
    function getavatarTempRecord();
    function setavatarTempRecord($var);
    function addAvatarTempRecord($day,$var);
    function getSingleAvatarTempRecord($day);
    function getAvatarSurvivableTemp();
    function setAvatarSurvivableTemp($var);
    function getAchievements();
    function setAchievements($var);
    function addAchievement($var);
    function removeAchievement($var);
    function addPartyVotePlayer($var);
    function removePartyVotePlayer($var);
    function changePartyVote($var,$vote);
    function clearVotes();
    function getPartyVote();
    function getResearchStats();
    function setResearchStats($type,$var);
    function getResearched();
    function setResearched($var);
    function addResearched($var);
    function removeResearched($var);
    function getPlayStatistics();
    function setPlayStatistics($var);
    function addPlayStatistics($var,$count);
    function removePlayStatistics($var,$count);
    function getTempModLevel();
    function setTempModLevel($var);
    function getFindingChanceMod();
    function setFindingChanceMod($var);
    function getFindingChanceFail();
    function setFindingChanceFail($var);
    function increaseFindingChanceFail($var);
    function resetFindingChanceFail();
    function getShrineScore();
    function setShrineScore($var);
    function addShineScore($shine,$day);
}