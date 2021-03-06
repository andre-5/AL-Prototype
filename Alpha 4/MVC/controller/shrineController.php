<?php
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/shrine.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/zoneController.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/shrinePlayerView.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/partyController.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/avatarController.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/controller/mapController.php");
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/model/shrineModel.php");
class shrineController extends shrine
{
    public function __construct($id)
    {
        if ($id != ""){
            $shrineModel = shrineModel::getShrineByID($id);
            $this->shrineID = $shrineModel->getShrineID();
            $this->mapID = $shrineModel->getMapID();
            $this->zoneID = $shrineModel->getZoneID();
            $this->shrineType = $shrineModel->getShrineType();
            $this->history = $shrineModel->getHistory();
            $this->currentArray = $shrineModel->getCurrentArray();
            $this->shrineName = $shrineModel->getShrineName();
            $this->description = $shrineModel->getDescription();
            $this->shrineIcon = $shrineModel->getShrineIcon();
            $this->worshipCost = $shrineModel->getWorshipCost();
            $this->worshipDescription = $shrineModel->getWorshipDescription();
            $this->minParty = $shrineModel->getMinParty();
            $this->maxParty = $shrineModel->getMaxParty();
            $this->shrineBonus = $shrineModel->getShrineBonus();
            $this->blessingMessage = $shrineModel->getBlessingMessage();
            $this->calculateTotalTribute();
        }
    }

    public function returnViewVars($avatarID){
        $this->currentArrayView = $this->getArrayDetails($avatarID);
        $this->currentArray = null;
        $this->shrineBonus = null;
        return get_object_vars($this);
    }

    public function returnSmallVars(){
        $this->currentArray = null;
        $this->shrineID = null;
        $this->history = null;
        $this->mapID = null;
        $this->zoneID = null;
        $this->worshipCost = null;
        $this->worshipDescription = null;
        $this->minParty = null;
        $this->maxParty = null;
        $this->description = null;
        $this->shrineBonus = null;
        return get_object_vars($this);
    }

    public function convertCurrentArray($day){
        $this->totalTribute = $this->getHistoryDay($day);
    }

    public static function createNewShrine($type,$zoneID)
    {
        $zone = new zoneController($zoneID);
        $shrine = new shrineController("");
        $shrine->setShrineID(shrineModel::createShrineID());
        $shrine->setMapID($zone->getMapID());
        $shrine->setZoneID($zone->getZoneID());
        $shrine->setShrineType($type);
        $shrine->setHistory(array());
        $shrine->setCurrentArray(array());
        return $shrine;
    }

    function insertShrine(){
        shrineModel::insertShrine($this,"Insert");
    }

    function updateShrine(){
        shrineModel::insertShrine($this,"Update");
    }

    public static function findShrine($zoneID){
        return shrineModel::findShrineInZone($zoneID);
    }

    private function getArrayDetails($avatarID){
        $avatar = new avatarController($avatarID);
        $party = new partyController($avatar->getPartyID());
        $final = [];
        $count = 0;
        foreach ($this->currentArray as $key=>$value){
            $temp = new shrinePlayerView($key,$value,$party->getPlayersKnown());
            $final[$count] = $temp->returnVars();
            $count++;
        }
        return $final;
    }

    public static function getMapScores($mapID,$object){
        $shrines = shrineModel::getMapShrineScores($mapID);
        $shrineArray = [];
        foreach ($shrines as $single){
            $temp = new shrineController($single);
            if ($object === true) {
                $shrineArray[$single] = $temp;
            } else {
                $shrineArray[$single] = $temp->returnSmallVars();
            }
        }
        return $shrineArray;
    }

    public static function getOldMapScores($mapID,$day){
        $shrines = shrineModel::getMapShrineScores($mapID);
        $shrineArray = [];
        foreach ($shrines as $single){
            $temp = new shrineController($single);
            $temp->convertCurrentArray($day);
            $shrineArray[$single] = $temp->returnSmallVars();

        }
        return $shrineArray;
    }

    public static function highestScoreShrine($mapID){
        $shrineArray = self::getMapScores($mapID,true);
        $highestShrine = "ERROR";
        $highestScore = 0;
        $equalArray = [];
        foreach ($shrineArray as $shrine){
            if ($shrine->getTotalTribute() > $highestScore){
                $highestShrine = $shrine->getShrineID();
                $highestScore = $shrine->getTotalTribute();
            } elseif ($shrine->getTotalTribute() === $highestScore){
                array_push($equalArray,$shrine->getShrineID());
            }
        }
        if ($highestShrine !== "ERROR") {
            if (in_array($highestShrine, $equalArray) === true) {
                return $equalArray;
            } else {
                return $highestShrine;
            }
        } else {
            return $highestShrine;
        }
    }

    public static function resetShrines($mapID){
        $shrines = shrineModel::getMapShrineScores($mapID);
        $map = new mapController($mapID);
        foreach ($shrines as $single){
            $temp = new shrineController($single);
            $temp->addHistory($map->getCurrentDay(),$temp->getTotalTribute());
            $temp->setCurrentArray(array());
            $temp->updateShrine();
        }
    }
}