<?php
if (!defined('PROJECT_ROOT')) exit(include($_SERVER['DOCUMENT_ROOT']."/error/404.php"));
class avatarModel extends avatar
{
    private function __construct($avatarModel)
    {
        $this->avatarID = intval($avatarModel['avatarID']);
        $this->profileID = $avatarModel['profileID'];
        $this->mapID = intval($avatarModel['mapID']);
        $this->stamina = intval($avatarModel['stamina']);
        $this->maxStamina = intval($avatarModel['maxstamina']);
        $this->zoneID = intval($avatarModel['zoneID']);
        $this->inventory = json_decode($avatarModel['inventory']);
        $this->maxInventorySlots = intval($avatarModel['maxinventoryslots']);
        $this->partyID = intval($avatarModel['partyID']);
        $this->readiness = $avatarModel['readiness'];
        if (is_object(json_decode($avatarModel['avatarTempRecord']))) {
            $this->avatarTempRecord = get_object_vars(json_decode($avatarModel['avatarTempRecord']));
        } else {
            $this->avatarTempRecord = array();
        }
        $this->avatarSurvivableTemp = intval($avatarModel['avatarsurvivabletemp']);
        if (is_object(json_decode($avatarModel['achievements']))) {
            $this->achievements = get_object_vars(json_decode($avatarModel['achievements']));
        } else {
            $this->achievements = array();
        }
        if (is_object(json_decode($avatarModel['partyVote']))) {
            $this->partyVote = get_object_vars(json_decode($avatarModel['partyVote']));
        } else {
            $this->partyVote = array();
        }
        $this->researchStats = (json_decode($avatarModel['researchStats']));
        $this->researched = (json_decode($avatarModel['researched']));
        if (is_object(json_decode($avatarModel['playStatistics']))) {
            $this->playStatistics = get_object_vars(json_decode($avatarModel['playStatistics']));
        } else {
            $this->playStatistics = array();
        }
        $this->tempModLevel = intval($avatarModel['tempModLevel']);
        $this->findingChanceMod = intval($avatarModel['findingChanceMod']);
        $this->findingChanceFail = intval($avatarModel['findingChanceFail']);
        if (is_object(json_decode($avatarModel['shrineScore']))) {
            $this->shrineScore = get_object_vars(json_decode($avatarModel['shrineScore']));
        } else {
            $this->shrineScore = array();
        }
        $this->forumPosts = json_decode($avatarModel['forumPosts']);
        if (is_object(json_decode($avatarModel['statusArray']))) {
            $this->statusArray = get_object_vars(json_decode($avatarModel['statusArray']));
        } else {
            $this->statusArray = array();
        }
    }

    public static function findAvatarID($avatarID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT * FROM Avatar WHERE avatarID= :avatarID LIMIT 1');
        $req->execute(array('avatarID' => $avatarID));
        $avatarModel = $req->fetch();
        return new avatarModel($avatarModel);

    }

    public static function insertAvatar($controller, $type){
        $db = db_conx::getInstance();
        if ($type == "Insert") {
            $req = $db->prepare("INSERT INTO Avatar (avatarID, profileID, mapID, stamina, maxstamina, zoneID, inventory, maxinventoryslots, partyID, readiness, avatarTempRecord, avatarsurvivabletemp, achievements, partyVote, researchStats, researched, playStatistics, tempModLevel, findingChanceMod, findingChanceFail, shrineScore, forumPosts, statusArray) VALUES (:avatarID, :profileID, :mapID, :stamina, :maxStamina, :zoneID, :inventory, :maxInventorySlots, :partyID, :readiness, :avatarTempRecord, :avatarSurvivableTemp, :achievements, :partyVote, :researchStats, :researched, :playStatistics, :tempModLevel, :findingChanceMod, :findingChanceFail, :shrineScore, :forumPosts, :statusArray)");
        } elseif ($type == "Update") {
            $req = $db->prepare("UPDATE Avatar SET profileID= :profileID, mapID= :mapID, stamina= :stamina, maxstamina= :maxStamina, zoneID = :zoneID, inventory= :inventory, maxinventoryslots= :maxInventorySlots, partyID= :partyID, readiness= :readiness, avatarTempRecord= :avatarTempRecord, avatarsurvivabletemp= :avatarSurvivableTemp, achievements= :achievements, partyVote= :partyVote, researchStats= :researchStats, researched= :researched, playStatistics= :playStatistics, tempModLevel= :tempModLevel, findingChanceMod= :findingChanceMod, findingChanceFail= :findingChanceFail, shrineScore= :shrineScore, forumPosts= :forumPosts, statusArray= :statusArray WHERE avatarID= :avatarID");;
        }
        $req->bindParam(':avatarID', intval($controller->getAvatarID()));
        $req->bindParam(':profileID', $controller->getProfileID());
        $req->bindParam(':mapID', intval($controller->getMapID()));
        $req->bindParam(':stamina', $controller->getStamina());
        $req->bindParam(':maxStamina', $controller->getMaxStamina());
        $req->bindParam(':zoneID', intval($controller->getZoneID()));
        $req->bindParam(':inventory', json_encode($controller->getInventory()));
        $req->bindParam(':maxInventorySlots', $controller->getMaxInventorySlots());
        $req->bindParam(':partyID', intval($controller->getPartyID()));
        $req->bindParam(':readiness', $controller->getReady());
        $req->bindParam(':avatarTempRecord', json_encode($controller->getavatarTempRecord()));
        $req->bindParam(':avatarSurvivableTemp', $controller->getAvatarSurvivableTemp());
        $req->bindParam(':achievements', json_encode($controller->getachievements()));
        $req->bindParam(':partyVote', json_encode($controller->getPartyVote()));
        $req->bindParam(':researchStats', json_encode($controller->getResearchStats()));
        $req->bindParam(':researched', json_encode($controller->getResearched()));
        $req->bindParam(':playStatistics', json_encode($controller->getPlayStatistics()));
        $req->bindParam(':tempModLevel', $controller->getTempModLevel());
        $req->bindParam(':findingChanceMod', $controller->getFindingChanceMod());
        $req->bindParam(':findingChanceFail', $controller->getFindingChanceFail());
        $req->bindParam(':shrineScore', json_encode($controller->getShrineScore()));
        $req->bindParam(':forumPosts', json_encode($controller->getForumPosts()));
        $req->bindParam(':statusArray', json_encode($controller->getStatusArray()));
        $req->execute();
        if ($type == "Insert"){
            $check = intval($db->lastInsertId());
            return $check;
        }
    }

    public static function deleteAvatar($avatarID){
        $db = db_conx::getInstance();
        $req = $db->prepare('DELETE FROM Avatar WHERE avatarID= :avatarID LIMIT 1');
        $req->execute(array('avatarID' => $avatarID));
        $avatarModel = $req->fetch();
        return new avatarModel($avatarModel);

    }

    public static function getAllMapAvatars($mapID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT * FROM Avatar WHERE mapID= :mapID');
        $req->execute(array('mapID' => $mapID));
        $avatarList = $req->fetchAll();;
        $avatarArray = [];
        foreach ($avatarList as $avatar) {
            $tempID = $avatar["avatarID"];
            $avatarArray[$tempID] = new avatarModel($avatar);
        }
        return $avatarArray;
    }


    public static function getAllPartyAvatars($partyID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT * FROM Avatar WHERE partyID= :partyID');
        $req->execute(array('partyID' => $partyID));
        $avatarList = $req->fetchAll();;
        $avatarArray = [];
        foreach ($avatarList as $avatar) {
            $tempID = $avatar["avatarID"];
            $avatarArray[$tempID] = new avatarModel($avatar);
        }
        return $avatarArray;
    }

}