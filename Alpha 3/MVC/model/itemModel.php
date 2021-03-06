<?php
class itemModel extends item
{
    private function __construct($itemModel)
    {
        if (isset($itemModel['ItemID'])) {
            $this->itemID = $itemModel['ItemID'];
            $this->mapID = $itemModel['MapID'];
            $this->itemTemplateID = $itemModel['itemTemplateID'];
            $this->identity = $itemModel['identity'];
            $this->icon = $itemModel['icon'];
            $this->description = $itemModel['description'];
            $this->itemType = $itemModel['itemType'];
            $this->findingChances = $itemModel['findingchances'];
            $this->fuelValue = $itemModel['fuelvalue'];
            $this->maxCharges = $itemModel['maxcharges'];
            $this->currentCharges = $itemModel['currentcharges'];
            $this->itemStatus = $itemModel['itemstatus'];
            $this->usable = $itemModel['useable'];
            $this->itemType = $itemModel['itemtype'];
            $this->survivalBonus = $itemModel['survivalBonus'];
        } else {
            $this->itemID = "X";
            $this->mapID = "X";
            $this->itemTemplateID = $itemModel['templateID'];
            $this->identity = $itemModel['identity'];
            $this->icon = $itemModel['icon'];
            $this->description = $itemModel['description'];
            $this->itemType = $itemModel['itemType'];
            $this->findingChances = $itemModel['findingchances'];
            $this->fuelValue = $itemModel['fuelvalue'];
            $this->maxCharges = $itemModel['maxcharges'];
            $this->currentCharges = $itemModel['maxcharges'];
            $this->itemStatus = "normal";
            $this->usable = $itemModel['useable'];
            $this->itemType = $itemModel['itemtype'];
            $this->survivalBonus = $itemModel['survivalBonus'];
        }
    }

    //This returns the next value in the counter columnn in order to add to the new item information
    public static function createItemID(){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT ItemID FROM Item ORDER BY ItemID DESC LIMIT 1');
        $req->execute();
        $tempID = $req->fetch();
        return $tempID['ItemID']+1;
    }


    public static function insertItem($itemController, $type){
        $db = db_conx::getInstance();
        if ($type == "Insert") {
            $req = $db->prepare("INSERT INTO Item (ItemID,MapID,itemTemplateID,currentcharges,itemstatus) VALUES (:itemID,:mapID,:itemTemplateID,:currentCharges,:itemStatus)");
        } elseif ($type == "Update"){
            $req = $db->prepare("UPDATE Item SET MapID= :mapID,itemTemplateID= :itemTemplateID, currentcharges= :currentCharges,itemstatus= :itemStatus WHERE ItemID= :itemID");
        }
        $req->bindParam(':itemID', $itemController->getItemID());
        $req->bindParam(':mapID', $itemController->getMapID());
        $req->bindParam(':itemTemplateID', $itemController->getItemTemplateID());
        $req->bindParam(':currentCharges', $itemController->getCurrentCharges());
        $req->bindParam(':itemStatus', $itemController->getItemStatus());
        $req->execute();
    }

    public static function deleteItem($itemID){
        $db = db_conx::getInstance();
        $req = $db->prepare('DELETE FROM Item WHERE ItemID= :itemID LIMIT 1');
        $req->execute(array('itemID' => $itemID));
        $req->fetch();
        return "Success";
    }

    public static function getItem($itemID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT Item.ItemID, Item.MapID, Item.itemTemplateID, ItemTemplate.identity, ItemTemplate.icon, ItemTemplate.description, ItemTemplate.itemtype, ItemTemplate.findingchances, ItemTemplate.fuelvalue, ItemTemplate.maxcharges, ItemTemplate.biomeLocations, ItemTemplate.useable, Item.itemstatus,  Item.currentcharges, ItemTemplate.survivalBonus  FROM Item INNER JOIN ItemTemplate ON Item.itemTemplateID = ItemTemplate.templateID AND Item.ItemID = :itemID');
        $req->execute(array('itemID' => $itemID));
        $itemModel = $req->fetch();
        return new itemModel($itemModel);
    }

    public static function findBiomeItems($biome){
        $adjustedBiome = "%".$biome."%";
        $db = db_conx::getInstance();
        $req = $db->prepare("SELECT templateID, findingchances FROM ItemTemplate WHERE biomeLocations LIKE :biome");
        $req->execute(array('biome' => $adjustedBiome));
        $biomeList = $req->fetchAll();
        $biomeArray = [];
        foreach ($biomeList as $item) {
            $tempArray = [$item['templateID'],$item['findingchances']];
            array_push($biomeArray,$tempArray);
        }
        return $biomeArray;
    }

    public static function newItem($templateID){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT *  FROM ItemTemplate WHERE templateID = :template');
        $req->execute(array('template' => $templateID));
        $itemModel = $req->fetch();
        return new itemModel($itemModel);
    }


    public static function getItemArray($itemArray){
        $search = implode(',',$itemArray);
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT Item.ItemID, Item.MapID, Item.itemTemplateID, ItemTemplate.identity, ItemTemplate.icon, ItemTemplate.description, ItemTemplate.itemtype, ItemTemplate.findingchances, ItemTemplate.fuelvalue, ItemTemplate.maxcharges, ItemTemplate.biomeLocations, ItemTemplate.useable, Item.itemstatus,  Item.currentcharges, ItemTemplate.survivalBonus  FROM Item INNER JOIN ItemTemplate ON Item.itemTemplateID = ItemTemplate.templateID AND Item.ItemID IN ('.$search.')');
        $req->execute();
        $itemModel = $req->fetchAll();
        $foundItems = [];
        $counter = 0;
        foreach ($itemModel as $item) {
            $newObject = new itemModel($item);
            $foundItems[$newObject->getIdentity().$counter] = $newObject->returnVars();
            $counter++;
        }
        ksort($foundItems);
        return $foundItems;
    }

    public static function getAllItemDetails(){
        $db = db_conx::getInstance();
        $req = $db->prepare('SELECT templateID, identity, icon, description  FROM ItemTemplate');
        $req->execute();
        $itemModel = $req->fetchAll();
        $foundItems = [];
        $counter = 0;
        foreach ($itemModel as $item) {
            $newObject = new itemModel($item);
            $foundItems[$counter] = $newObject;
            $counter++;
        }
        return $foundItems;
    }

    public static function changeAllMapItems($from,$to,$mapID){
        $db = db_conx::getInstance();
        $req = $db->prepare("UPDATE Item SET itemTemplateID= :changeItem WHERE MapID= :mapID AND itemTemplateID= :currentItem");
        $req->bindParam(':currentItem', $from);
        $req->bindParam(':mapID', $mapID);
        $req->bindParam(':changeItem', $to);
        $req->execute();

    }
}