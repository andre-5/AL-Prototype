<?php
include_once($_SERVER['DOCUMENT_ROOT']."/MVC/connection/db_conx.php");
class storageModel extends storage
{
    private function __construct($storageModel)
    {
        $this->storageID = $storageModel['storageID'];
        $this->zoneID = $storageModel['zoneID'];
        $this->items = json_decode($storageModel['items']);
        $this->maximumCapacity = $storageModel['maximumCapacity'];
        $this->storageLevel = $storageModel['storageLevel'];
    }



    public static function getStorage($storageID,$zoneID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT * FROM Storage WHERE storageID= :storageID OR zoneID= :zoneID LIMIT 1');
        $req->bindParam(':storageID', $storageID);
        $req->bindParam(':zoneID', $zoneID);
        $req->execute();
        $storageModel = $req->fetch();
        return new storageModel($storageModel);
    }


    public static function insertStorage($storageController, $type){
        $db = db_conx::getInstance();
        if ($type == "Insert") {
            $req = $db->prepare("INSERT INTO Storage (storageID, zoneID, items, maximumCapacity, storageLevel) VALUES (:storageID,:zoneID,:items,:maximumCapacity, :storageLevel)");
        } elseif ($type == "Update"){
            $req = $db->prepare("UPDATE Storage SET zoneID= :zoneID, items= :items,maximumCapacity= :maximumCapacity, storageLevel= :storageLevel WHERE storageID= :storageID");
        }
        $req->bindParam(':storageID', $storageController->getStorageID());
        $req->bindParam(':zoneID', $storageController->getZoneID());
        $req->bindParam(':items', json_encode($storageController->getItems()));
        $req->bindParam(':maximumCapacity', $storageController->getMaximumCapacity());
        $req->bindParam(':storageLevel', $storageController->getStorageLevel());
        $req->execute();
    }

}