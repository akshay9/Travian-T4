<?php
class Automation {
    
    private $bountyresarray = array();
    private $bountyinfoarray = array();
    private $bountyproduction = array();
    private $bountyocounter = array();
    private $bountyunitall = array();
    private $bountypop;
    private $bountyOresarray = array();
    private $bountyOinfoarray = array();
    private $bountyOproduction = array();
    private $bountyOpop = 1;
    
	
        public function procResType($ref) {
        global $session;
        switch($ref) {
            case 1: $build = "Favágó"; break;
            case 2: $build = "Agyagbánya"; break;
            case 3: $build = "Vasércbánya"; break;
            case 4: $build = "Búzafarm"; break;
            case 5: $build = "Fűrészüzem"; break;
            case 6: $build = "Agyagégető"; break;
            case 7: $build = "Vasöntöde"; break;
            case 8: $build = "Malom"; break;
            case 9: $build = "Pékség"; break;
            case 10: $build = "Raktár"; break;
            case 11: $build = "Magtár"; break;
            case 12: $build = "Kovács"; break;
            case 14: $build = "Gyakorlótér"; break;
            case 15: $build = "Főépület"; break;
            case 16: $build = "Gyülekezőtér"; break;
            case 17: $build = "Piac"; break;
            case 18: $build = "Követség"; break;
            case 19: $build = "Kaszárnya"; break;
            case 20: $build = "Istálló"; break;
            case 21: $build = "Műhely"; break;
            case 22: $build = "Akadémia"; break;
            case 23: $build = "Rejtekhely"; break;
            case 24: $build = "Városháza"; break;
            case 25: $build = "Rezidencia"; break;
            case 26: $build = "Palota"; break;
            case 27: $build = "Kincstár"; break;
            case 28: $build = "Kereskedelmi központ"; break;
            case 29: $build = "Nagy kaszárnya"; break;
            case 30: $build = "Nagy istálló"; break;
            case 31: $build = "Kőfal"; break;
            case 32: $build = "Földfal"; break;
            case 33: $build = "Cölöpfal"; break;
            case 34: $build = "Kőfaragó"; break;
            case 35: $build = "Sörfőzde"; break;
            case 36: $build = "Csapdakészítő"; break;
            case 37: $build = "Hősök háza"; break;
            case 38: $build = "Nagy raktár"; break;
            case 39: $build = "Nagy magtár"; break;
            case 40: $build = "Világcsoda"; break;
            case 41: $build = "Lóitató"; break;
            case 42: $build = "Műhely2"; break;
            default: $build = "Error"; break;
        }
        return $build;
    }
    
    function recountPop($vid){
    global $database;
        $fdata = $database->getResourceLevel($vid); 
        $popTot = 0;
        
        for ($i = 1; $i <= 40; $i++) {
            $lvl = $fdata["f".$i];
            $building = $fdata["f".$i."t"];
            if($building){
                $popTot += $this->buildingPOP($building,$lvl);
            }       
        }        
        
        $q = "UPDATE ".TB_PREFIX."vdata set pop = $popTot where wref = $vid";
        mysql_query($q);
        
        return $popTot;

    }
  
    function buildingPOP($f,$lvl){
    $name = "bid".$f;
    global $$name;        
        $popT = 0;
        $dataarray = $$name;
    
        for ($i = 0; $i <= $lvl; $i++) {
            $popT += $dataarray[$i]['pop'];
        }
    return $popT;
    }
    
     public function Automation() {
        if(!file_exists("GameEngine/Prevention/cleardeleting.txt") or time()-filemtime("GameEngine/Prevention/cleardeleting.txt")>10) {
            $this->clearDeleting();
        }
        $this->ClearUser();
        $this->ClearInactive();
        $this->pruneResource();
        if(!file_exists("GameEngine/Prevention/loyalty.txt") or time()-filemtime("GameEngine/Prevention/loyalty.txt")>10) {
	        $this->loyaltyRegeneration();
		}
        if(!file_exists("GameEngine/Prevention/updatehero.txt") or time()-filemtime("GameEngine/Prevention/updatehero.txt")>10) {
	        $this->updateHero();
		}
        if(!file_exists("GameEngine/Prevention/celebration.txt") or time()-filemtime("GameEngine/Prevention/celebration.txt")>10) {
	        $this->celebrationComplete();
		}
        if(!file_exists("GameEngine/Prevention/culturepoints.txt") or time()-filemtime("GameEngine/Prevention/culturepoints.txt")>10) {
            $this->culturePoints();
        }
        if(!file_exists("GameEngine/Prevention/research.txt") or time()-filemtime("GameEngine/Prevention/research.txt")>10) {
            $this->researchComplete();
        }
        if(!file_exists("GameEngine/Prevention/starvation.txt") or time()-filemtime("GameEngine/Prevention/starvation.txt")>10) {
	        $this->starvation();
		}
        if(!file_exists("GameEngine/Prevention/build.txt") or time()-filemtime("GameEngine/Prevention/build.txt")>10) {
            $this->buildComplete();
        }
		if(!file_exists("GameEngine/Prevention/auction.txt") or time()-filemtime("GameEngine/Prevention/auction.txt")>10) {
            $this->auctionComplete();
        }
        if(!file_exists("GameEngine/Prevention/market.txt") or time()-filemtime("GameEngine/Prevention/market.txt")>10) {
            $this->marketComplete();
        }
        if(!file_exists("GameEngine/Prevention/training.txt") or time()-filemtime("GameEngine/Prevention/training.txt")>10) {
            $this->trainingComplete();
        }
        if(!file_exists("GameEngine/Prevention/sendreinfunits.txt") or time()-filemtime("GameEngine/Prevention/sendreinfunits.txt")>10) {
            $this->sendreinfunitsComplete();
        }
        if(!file_exists("GameEngine/Prevention/returnunits.txt") or time()-filemtime("GameEngine/Prevention/returnunits.txt")>10) {
            $this->returnunitsComplete();
        }
        if(!file_exists("GameEngine/Prevention/settlers.txt") or time()-filemtime("GameEngine/Prevention/settlers.txt")>10) {
            $this->sendSettlersComplete();
        } 
		if(!file_exists("GameEngine/Prevention/adventures.txt") or time()-filemtime("GameEngine/Prevention/adventures.txt")>10) {
            $this->sendAdventuresComplete();
        } 
        if(!file_exists("GameEngine/Prevention/demolition.txt") or time()-filemtime("GameEngine/Prevention/demolition.txt")>10) {  
            $this->demolitionComplete();    
        }
        if(!file_exists("GameEngine/Prevention/sendunits.txt") or time()-filemtime("GameEngine/Prevention/sendunits.txt")>10) {
            $this->sendunitsComplete();
        }
    }
	
	private function getfieldDistance($coorx1, $coory1, $coorx2, $coory2) {
		$max = 2 * WORLD_MAX + 1;
		$x1 = intval($coorx1);
		$y1 = intval($coory1);
		$x2 = intval($coorx2);
		$y2 = intval($coory2);
		$distanceX = min(abs($x2 - $x1), abs($max - abs($x2 - $x1)));
		$distanceY = min(abs($y2 - $y1), abs($max - abs($y2 - $y1)));
		$dist = sqrt(pow($distanceX, 2) + pow($distanceY, 2));
		return round($dist, 1);
   } 
   
   public function getTypeLevel($tid,$vid) {
        global $village,$database;
        $keyholder = array();
       
            $resourcearray = $database->getResourceLevel($vid);
        
        foreach(array_keys($resourcearray,$tid) as $key) {
            if(strpos($key,'t')) {
                $key = preg_replace("/[^0-9]/", '', $key);
                array_push($keyholder, $key);
            }     
        }
        $element = count($keyholder);
        if($element >= 2) {
            if($tid <= 4) {
                $temparray = array();
                for($i=0;$i<=$element-1;$i++) {
                    array_push($temparray,$resourcearray['f'.$keyholder[$i]]);
                }
                foreach ($temparray as $key => $val) {
                    if ($val == max($temparray)) 
                    $target = $key; 
                }
            }
            else {
                $target = 0;
                for($i=1;$i<=$element-1;$i++) {
                    if($resourcearray['f'.$keyholder[$i]] > $resourcearray['f'.$keyholder[$target]]) {
                        $target = $i;
                    }
                }
            }
        }
        else if($element == 1) {
            $target = 0;
        }
        else {
            return 0;
        }
        if($keyholder[$target] != "") {
            return $resourcearray['f'.$keyholder[$target]];
        }
        else {
            return 0;
        }
    }  
	
	
	private function loyaltyRegeneration() {
        global $database;
        $array = array();
        $q = "SELECT * FROM ".TB_PREFIX."vdata WHERE loyalty < 100";
        $array = $database->query_return($q);
		if(!empty($array)) { 
	        foreach($array as $loyalty) {
                if($this->getTypeLevel(25,$loyalty['wref']) >= 1){
                    $value = $this->getTypeLevel(25,$loyalty['wref']);
                }elseif($this->getTypeLevel(26,$loyalty['wref']) >= 1){
                    $value = $this->getTypeLevel(26,$loyalty['wref']);
                } else {
					$value = 0;
				}
				$newloyalty = min(100,$loyalty['loyalty']+$value*(time()-$loyalty['lastupdate'])*SPEED/(60*60));
                $q = "UPDATE ".TB_PREFIX."vdata SET loyalty = $newloyalty WHERE wref = '".$loyalty['wref']."'";
                $database->query($q);
			}
        }
        $array = array();
        $q = "SELECT * FROM ".TB_PREFIX."odata WHERE loyalty<>100";
        $array = $database->query_return($q);
		if(!empty($array)) { 
	        foreach($array as $loyalty) {
                if($this->getTypeLevel(25,$loyalty['conqured']) >= 1){
                    $value = $this->getTypeLevel(25,$loyalty['conqured']);
                }elseif($this->getTypeLevel(26,$loyalty['conqured']) >= 1){
                    $value = $this->getTypeLevel(26,$loyalty['conqured']);
                } else {
					$value = 0;
				}
				$newloyalty = min(100,$loyalty['loyalty']+$value*(time()-$loyalty['lastupdate'])*SPEED/(60*60));
                $q = "UPDATE ".TB_PREFIX."odata SET loyalty = $newloyalty WHERE wref = '".$loyalty['wref']."'";
                $database->query($q);
			}
        }
		
		$array2 = array();
        $q2 = "SELECT * FROM ".TB_PREFIX."vdata WHERE loyalty>125";
        $array2 = $database->query_return($q2);
		if(!empty($array2)) { 
	        foreach($array2 as $loyalty) {
                $q = "UPDATE ".TB_PREFIX."vdata SET loyalty = 125 WHERE wref = '".$loyalty['wref']."'";
                $database->query($q);
			}
        }
		if(file_exists("GameEngine/Prevention/loyalty.txt")) {
            @unlink("GameEngine/Prevention/loyalty.txt");
        }
    }
    
    private function clearDeleting() {
        global $database;
        $needDelete = $database->getNeedDelete();
        if(count($needDelete) > 0) {
            foreach($needDelete as $need) {
                $needVillage = $database->getVillagesID($need['uid']); //wref
                foreach($needVillage as $village) {
                    $q = "DELETE FROM ".TB_PREFIX."abdata where wref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."bdata where wid = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."enforcement where vref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."fdata where vref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."market where vref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."movement where to = ".$village['wref']." or from = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."odata where wref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."research where vref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."tdata where vref = ".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."training where vref =".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."units where vref =".$village['wref'];
                    $database->query($q);
                    $q = "DELETE FROM ".TB_PREFIX."vdata where wref = ".$village['wref'];
                    $database->query($q);
                    $q = "UPDATE ".TB_PREFIX."wdata set occupied = 0 where id = ".$village['wref'];
                    $database->query($q);
                }
                $q = "DELETE FROM ".TB_PREFIX."mdata where target = ".$need['uid']." or owner = ".$need['uid'];
                $database->query($q);
                $q = "DELETE FROM ".TB_PREFIX."ndata where uid = ".$need['uid'];
                $database->query($q);
                $q = "DELETE FROM ".TB_PREFIX."users where id = ".$need['uid'];
                $database->query($q);
            }
        }
		if(file_exists("GameEngine/Prevention/cleardeleting.txt")) {
            @unlink("GameEngine/Prevention/cleardeleting.txt");
        }
    }
    
    private function ClearUser() {
        global $database;
        if(AUTO_DEL_INACTIVE) {
            $time = time()+UN_ACT_TIME;
            $q = "DELETE from ".TB_PREFIX."users where timestamp >= $time and act != ''";
            $database->query($q);
        }
    }
    
    private function ClearInactive() {
        global $database;
        if(TRACK_USR) {
            $timeout = time()-USER_TIMEOUT*60;
              $q = "DELETE FROM ".TB_PREFIX."active WHERE timestamp < $timeout";
             $database->query($q);
        }
    }
     private function pruneOResource() {
        global $database;
        if(!ALLOW_BURST) {
            $q = "UPDATE ".TB_PREFIX."odata set `wood` = `maxstore` WHERE `wood` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `clay` = `maxstore` WHERE `clay` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `iron` = `maxstore` WHERE `iron` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `crop` = `maxcrop` WHERE `crop` > `maxcrop`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `crop` = 50 WHERE `crop` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `wood` = 0 WHERE `wood` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `clay` = 0 WHERE `clay` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `iron` = 0 WHERE `iron` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `maxstore` = ".(STORAGE_BASE/2)." WHERE `maxstore` <= ".(STORAGE_BASE/2);
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."odata set `maxcrop` = ".(STORAGE_BASE/2)." WHERE `maxcrop` <= ".(STORAGE_BASE/2);
            $database->query($q);
        }
    }
    private function pruneResource() {
        global $database;
        if(!ALLOW_BURST) {
            $q = "UPDATE ".TB_PREFIX."vdata set `wood` = `maxstore` WHERE `wood` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `clay` = `maxstore` WHERE `clay` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `iron` = `maxstore` WHERE `iron` > `maxstore`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `crop` = `maxcrop` WHERE `crop` > `maxcrop`";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `crop` = 50 WHERE `crop` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `wood` = 0 WHERE `wood` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `clay` = 0 WHERE `clay` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `iron` = 0 WHERE `iron` < 0";
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `maxstore` = ".STORAGE_BASE." WHERE `maxstore` <= ".STORAGE_BASE;
            $database->query($q);
            $q = "UPDATE ".TB_PREFIX."vdata set `maxcrop` = ".STORAGE_BASE." WHERE `maxcrop` <= ".STORAGE_BASE;
            $database->query($q);
        }
    }
    
    private function culturePoints() {
        global $database;
        $ourFileHandle = @fopen("GameEngine/Prevention/culturepoints.txt", 'w');
        @fclose($ourFileHandle);
        $time = time()-43200;
        $array = array();
        $q = "SELECT id, lastupdate FROM ".TB_PREFIX."users where lastupdate < $time";
        $array = $database->query_return($q);
        
        foreach($array as $indi) {
            if($indi['lastupdate'] < $time){
                $cp = $database->getVSumField($indi['id'], 'cp');
                $newupdate = time();
                $q = "UPDATE ".TB_PREFIX."users set cp = cp + $cp, lastupdate = $newupdate where id = '".$indi['id']."'";
                $database->query($q);
            }
        }
        if(file_exists("GameEngine/Prevention/culturepoints.txt")) {
            @unlink("GameEngine/Prevention/culturepoints.txt");
        }
    }
    
    private function buildComplete() {
        global $database,$bid18,$bid10,$bid11,$bid38,$bid39;
        $ourFileHandle = @fopen("GameEngine/Prevention/build.txt", 'w');
        @fclose($ourFileHandle);
        $time = time();
        $array = array();
        $q = "SELECT * FROM ".TB_PREFIX."bdata where timestamp < $time";
        $array = $database->query_return($q);
        foreach($array as $indi) {
            $q = "UPDATE ".TB_PREFIX."fdata set f".$indi['field']." = f".$indi['field']." + 1, f".$indi['field']."t = ".$indi['type']." where vref = ".$indi['wid'];
            if($database->query($q)) {
                $level = $database->getFieldLevel($indi['wid'],$indi['field']);
                $pop = $this->getPop($indi['type'],($level-1));
                $database->modifyPop($indi['wid'],$pop[0],0);
                $database->addCP($indi['wid'],$pop[1]);
                if($indi['type'] == 18) {
                    $owner = $database->getVillageField($indi['wid'],"owner");
                    $max = $bid18[$level]['attri'];
                    $q = "UPDATE ".TB_PREFIX."alidata set max = $max where leader = $owner";
                    $database->query($q);
                }

                    if($indi['type'] == 10) {
                      $max=$database->getVillageField($indi['wid'],"maxstore");
                      if($level=='1' && $max==800){ $max-=800; }
                      $max-=$bid10[$level-1]['attri'];      
                      $max+=$bid10[$level]['attri'];  
                      $database->setVillageField($indi['wid'],"maxstore",$max);
                    }
                    
                    if($indi['type'] == 11) {
                      $max=$database->getVillageField($indi['wid'],"maxcrop");
                      if($level=='1' && $max==800){ $max-=800; }
                      $max-=$bid11[$level-1]['attri'];      
                      $max+=$bid11[$level]['attri']; 
                      $database->setVillageField($indi['wid'],"maxcrop",$max);
                    }
                    if($indi['type'] == 38) {
                    $max=$database->getVillageField($indi['wid'],"maxstore");
                    if($level=='1' && $max==800){ $max-=800; }
                    $max-=$bid38[$level-1]['attri'];      
                    $max+=$bid38[$level]['attri'];  
                    $database->setVillageField($indi['wid'],"maxstore",$max);
                    }

                    if($indi['type'] == 39) {
                    $max=$database->getVillageField($indi['wid'],"maxcrop");
                    if($level=='1' && $max==800){ $max-=800; }
                    $max-=$bid39[$level-1]['attri'];      
                    $max+=$bid39[$level]['attri']; 
                    $database->setVillageField($indi['wid'],"maxcrop",$max);
                    }      
          
                $q4 = "UPDATE ".TB_PREFIX."bdata set loopcon = 0 where loopcon = 1 and wid = ".$indi['wid'];
                $database->query($q4);
                $q = "DELETE FROM ".TB_PREFIX."bdata where id = ".$indi['id'];
                $database->query($q);
            }
        }
        if(file_exists("GameEngine/Prevention/build.txt")) {
            @unlink("GameEngine/Prevention/build.txt");
        }
    }
    
    private function getPop($tid,$level) {
        $name = "bid".$tid;
        global $$name,$village;
        $dataarray = $$name;
        $pop = $dataarray[($level+1)]['pop'];
        $cp = $dataarray[($level+1)]['cp'];
        return array($pop,$cp);
    }
    
    private function marketComplete() {
        global $database,$generator;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."send where ".TB_PREFIX."movement.ref = ".TB_PREFIX."send.id and ".TB_PREFIX."movement.proc = 0 and sort_type = 0 and endtime < $time";
        $dataarray = $database->query_return($q);
        foreach($dataarray as $data) {
            
            if($data['wood'] >= $data['clay'] && $data['wood'] >= $data['iron'] && $data['wood'] >= $data['crop']){ $sort_type = "10"; }
            elseif($data['clay'] >= $data['wood'] && $data['clay'] >= $data['iron'] && $data['clay'] >= $data['crop']){ $sort_type = "11"; }
            elseif($data['iron'] >= $data['wood'] && $data['iron'] >= $data['clay'] && $data['iron'] >= $data['crop']){ $sort_type = "12"; }
            elseif($data['crop'] >= $data['wood'] && $data['crop'] >= $data['clay'] && $data['crop'] >= $data['iron']){ $sort_type = "13"; }

            $to = $database->getMInfo($data['to']);
            $from = $database->getMInfo($data['from']);
			$toAlly = $database->getUserField($to['owner'],'alliance',0);
			$fromAlly = $database->getUserField($from['owner'],'alliance',0);
            $database->addNotice($to['owner'],$to['wref'],$toAlly,$sort_type,''.addslashes($from['name']).' به '.addslashes($to['name']).' منابع می فرستد',''.$from['wref'].','.$to['wref'].','.$data['wood'].','.$data['clay'].','.$data['iron'].','.$data['crop'].'',$data['endtime']); 
            if($from['owner'] != $to['owner']) {
                $database->addNotice($from['owner'],$to['wref'],$fromAlly,$sort_type,''.addslashes($from['name']).' به '.addslashes($to['name']).' منابع می فرستد',''.$from['wref'].','.$to['wref'].','.$data['wood'].','.$data['clay'].','.$data['iron'].','.$data['crop'].'',$data['endtime']);
            }
            $database->modifyResource($data['to'],$data['wood'],$data['clay'],$data['iron'],$data['crop'],1);
            $tocoor = $database->getCoor($data['from']);
            $fromcoor = $database->getCoor($data['to']);
            $targettribe = $database->getUserField($database->getVillageField($data['from'],"owner"),"tribe",0);
            $endtime = $this->procDistanceTime($tocoor,$fromcoor,$targettribe,0) + $data['endtime'];
            $database->addMovement(2,$data['to'],$data['from'],$ref,$data['merchant'],$endtime);
            $database->setMovementProc($data['moveid']);
        }
        $q = "UPDATE ".TB_PREFIX."movement set proc = 1 where endtime < $time and sort_type = 2";
        $database->query($q);
		if(file_exists("GameEngine/Prevention/market.txt")) {
            @unlink("GameEngine/Prevention/market.txt");
        }
    }
    
    private function sendunitsComplete() {
        global $bid23,$database,$battle,$village,$technology,$logging,$session;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."attacks where ".TB_PREFIX."movement.ref = ".TB_PREFIX."attacks.id and ".TB_PREFIX."movement.proc = '0' and ".TB_PREFIX."movement.sort_type = '3' and ".TB_PREFIX."attacks.attack_type != '2' and endtime < $time ORDER BY endtime ASC";
        $dataarray = $database->query_return($q);
        
        foreach($dataarray as $data) {
            //set base things
			//$battle->resolveConflict($data);
            $tocoor = $database->getCoor($data['from']);
            $fromcoor = $database->getCoor($data['to']);
            $isoasis = $database->isVillageOases($data['to']);
            $AttackArrivalTime = $data['endtime']; 
            if ($isoasis == 0){
            
            $owntribe = $database->getUserField($database->getVillageField($data['from'],"owner"),"tribe",0);
            $targettribe = $database->getUserField($database->getVillageField($data['to'],"owner"),"tribe",0);
            $ownally = $database->getUserField($database->getVillageField($data['from'],"owner"),"alliance",0);
            $targetally = $database->getUserField($database->getVillageField($data['to'],"owner"),"alliance",0);
            $to = $database->getMInfo($data['to']);
            $from = $database->getMInfo($data['from']);
            $toF = $database->getVillage($data['to']);
            $fromF = $database->getVillage($data['from']);
            
           
            /*--------------------------------
            // Battle part
            --------------------------------*/

                                //get defence units
                                /*    $q = "SELECT * FROM ".TB_PREFIX."units WHERE vref='".$data['to']."'";
                                    $unitlist = $database->query_return($q);

                                    $Defender = array();
                                                $start = ($targettribe == 1)? 1 : (($targettribe == 2)? 11: 21);
                                                $end = ($targettribe == 1)? 10 : (($targettribe == 2)? 20: 30);
                                        for($i=$start;$i<=$end;$i++) {
                                                if($unitlist)
                                                    $Defender['u'.$i] = $unitlist[0]['u'.$i];
                                                else
                                                    $Defender['u'.$i] = '';
                                                }*/
                        //get defence units 
                        $Defender = array();    $rom = $ger = $gal = $nat = $natar = 0;
                        $Defender = $database->getUnit($data['to']);
						
                        $enforcementarray = $database->getEnforceVillage($data['to'],0);
                        if(count($enforcementarray) > 0) {
                            foreach($enforcementarray as $enforce) {
                                for($i=1;$i<=50;$i++) {
                                    $Defender['u'.$i] += $enforce['u'.$i];
                                }
									$Defender['hero'] += $enforce['hero'];
                            }
                        }
                            for($i=1;$i<=50;$i++){
                            	if(!isset($Defender['u'.$i])){
                                    $Defender['u'.$i] = '0';
                                } else {
                                	if($Defender['u'.$i]=='' or $Defender['u'.$i]<='0'){
                                    	$Defender['u'.$i] = '0';                                 
                                	} else {
                                            if($i<=10){ $rom='1'; } 

                                            else if($i<=20){ $ger='1'; } 
                                            else if($i<=30){ $gal='1'; } 
                                            else if($i<=40){ $nat='1'; } 
                                            else if($i<=50){ $natar='1'; } 
                            		}
                               }
                            }
							if(!isset($Defender['hero'])){
                            	$Defender['hero'] = '0';
                            } else {
                            	if($Defender['hero']=='' or $Defender['hero']<='0'){
                            		$Defender['hero'] = '0';                                 
                            	}
                            }
                                    //get attack units            
                                            $Attacker = array();
                                            $start = ($owntribe-1)*10+1;
                                            $end = ($owntribe*10);
                                            $u = (($owntribe-1)*10);                                                            
                                            $catp =  0;                                
                                            $catapult = array(8,18,28,38,48);
                                            $ram = array(7,17,27,37,47);
                                            $chief = array(9,19,29,39,49);
                                            $spys = array(4,14,23,34,44);
                                        for($i=$start;$i<=$end;$i++) {
                                            $y = $i-$u;
                                            $Attacker['u'.$i] = $dataarray[0]['t'.$y];
                                                //there are catas
                                                if(in_array($i,$catapult)) {
                                                $catp += $Attacker['u'.$i];
                                                $catp_pic = $i;
                                                }
                                                if(in_array($i,$ram)) {
                                                $rams += $Attacker['u'.$i];
                                                $ram_pic = $i;
                                                }
                                                if(in_array($i,$chief)) {
                                                $chiefs += $Attacker['u'.$i];
                                                $chief_pic = $i;
                                                }
                                                if(in_array($i,$spys)) {
                                                $chiefs += $Attacker['u'.$i];
                                                $spy_pic = $i;
                                                }
                                         } 
                        $Attacker['hero'] = $dataarray[0]['t11'];       
                        $hero_pic = "hero";
						$Attacker['id'] = $database->getVillageField($data['from'],"owner");
            			$Defender['id'] = $database->getVillageField($data['to'],"owner");
                                    //need to set these variables.
                                    $def_wall = $database->getFieldLevel($data['to'],40);
                                    $att_tribe = $owntribe;
                                    $def_tribe = $targettribe;
                                    $residence = "0";
                                    $attpop = $fromF['pop'];
                                    $defpop = $toF['pop']; 
                                    for ($i=19; $i<40; $i++){
                                        if ($database->getFieldLevel($data['to'],"".$i."t")=='25' OR $database->getFieldLevel($data['to'],"".$i."t")=='26'){
                                            $residence = $database->getFieldLevel($data['to'],$i);
                                            $i=40;
                                        }
                                    }
                        
                                    //type of attack
                                    if($dataarray[0]['attack_type'] == 1){
                                        $type = 1;
                                        $scout = 1;
                                    }
                                    if($dataarray[0]['attack_type'] == 2){
                                        $type = 2;
                                    }
                                    if($dataarray[0]['attack_type'] == 3){
                                        $type = 3;
                                    }
                                    if($dataarray[0]['attack_type'] == 4){
                                        $type = 4;
                                    }
                                
                                    $def_ab = Array (
                                        "b1" => 0, // Blacksmith level
                                        "b2" => 0, // Blacksmith level
                                        "b3" => 0, // Blacksmith level
                                        "b4" => 0, // Blacksmith level
                                        "b5" => 0, // Blacksmith level
                                        "b6" => 0, // Blacksmith level
                                        "b7" => 0, // Blacksmith level
                                        "b8" => 0); // Blacksmith level
                                    
                                    $att_ab = Array ( 
                                        "a1" => 0, // armoury level
                                        "a2" => 0, // armoury level
                                        "a3" => 0, // armoury level
                                        "a4" => 0, // armoury level
                                        "a5" => 0, // armoury level
                                        "a6" => 0, // armoury level
                                        "a7" => 0, // armoury level
                                        "a8" => 0); // armoury level

                        //rams attack
		if($rams > 0 and $type=='3'){ 
			$basearraywall = $database->getMInfo($data['to']); 
			if($database->getFieldLevel($basearraywall['wref'],40)>'0'){
				for ($w=1; $w<2; $w++){  
					if ($database->getFieldLevel($basearraywall['wref'],40)!='0'){
                        
						$walllevel = $database->getFieldLevel($basearraywall['wref'],40);
                        $wallgid = $database->getFieldLevel($basearraywall['wref'],"40t");
                        $wallid = 40;
                        $w='4';
					} else {
						$w = $w--;
					} 
				}                          
			}else{
				$empty = 1;    
			}
		}
                        
                                    $tblevel = '1';                    
                                    $stonemason = "1";

                            
           /*--------------------------------
            // End Battle part
            --------------------------------*/
            }else{
				$Attacker['id'] = $database->getUserField($database->getVillageField($data['from'],"owner"),"id",0);
				$Defender['id'] = 3;
					
				$owntribe = $database->getUserField($database->getVillageField($data['from'],"owner"),"tribe",0);
				$targettribe = 4;
				$ownally = $database->getUserField($database->getVillageField($data['from'],"owner"),"alliance",0);
				$targetally = 0;
				$to = $database->getOMInfo($data['to']);
				$from = $database->getMInfo($data['from']);
				$toF = $database->getOasisV($data['to']);
				$fromF = $database->getVillage($data['from']);
            
            
                        //get defence units 
                        $Defender = array();    $rom = $ger = $gal = $nat = $natar = 0;
                        $Defender = $database->getUnit($data['to']);
                        $enforcementarray = $database->getEnforceVillage($data['to'],0);
                        if(count($enforcementarray) > 0) {
                            foreach($enforcementarray as $enforce) {
                                for($i=1;$i<=50;$i++) {
                                    $Defender['u'.$i] += $enforce['u'.$i];
                                }
									$Defender['hero'] += $enforce['hero'];
                            }
                        }
                            for($i=1;$i<=50;$i++){
                                if(!isset($Defender['u'.$i])){
                                    $Defender['u'.$i] = '0';
                                } else {
                                	if($Defender['u'.$i]=='' or $Defender['u'.$i]<='0'){
                                    	$Defender['u'.$i] = '0';                                 
                                	} else {
                                                 if($i<=10){ $rom='1'; } 
                                            else if($i<=20){ $ger='1'; } 
                                            else if($i<=30){ $gal='1'; } 
                                            else if($i<=40){ $nat='1'; } 
                                            else if($i<=50){ $natar='1'; } 
                                	}
                                }
                            }
								if(!isset($Defender['hero'])){
                                    $Defender['hero'] = '0';
                                } else {
                                	if($Defender['hero']=='' or $Defender['hero']<='0'){
                                    	$Defender['hero'] = '0';                                 
                                	}
                                }
                                    //get attack units            
                                            $Attacker = array();
                                            $start = ($owntribe-1)*10+1;
                                            $end = ($owntribe*10);
                                            $u = (($owntribe-1)*10);                                                            
                                            $catp =  0;                                
                                            $catapult = array(8,18,28,38,48);
                                            $ram = array(7,17,27,37,47);
                                            $chief = array(9,19,29,39,49);
                                            $spys = array(4,14,23,34,44);
                                        for($i=$start;$i<=$end;$i++) {
                                            $y = $i-$u;
                                            $Attacker['u'.$i] = $dataarray[0]['t'.$y];
                                                //there are catas
                                                if(in_array($i,$catapult)) {
                                                $catp += $Attacker['u'.$i];
                                                $catp_pic = $i;
                                                }
                                                if(in_array($i,$ram)) {
                                                $rams += $Attacker['u'.$i];
                                                $ram_pic = $i;
                                                }
                                                if(in_array($i,$chief)) {
                                                $chiefs += $Attacker['u'.$i];
                                                $chief_pic = $i;
                                                }
                                                if(in_array($i,$spys)) {
                                                $chiefs += $Attacker['u'.$i];
                                                $spy_pic = $i;
                                                }
                                         }        
                        $Attacker['hero'] = $dataarray[0]['t11'];       
                        $hero_pic = "hero";
						$Attacker['id'] = $database->getUserField($database->getVillageField($data['from'],"owner"),"id",0);
            			$Defender['id'] = $database->getUserField($database->getVillageField($data['to'],"owner"),"id",0);
                                    //need to set these variables.
                                    $def_wall = 1;
                                    $att_tribe = $owntribe;
                                    $def_tribe = $targettribe;
                                    $residence = "0";
                                    $attpop = $fromF['pop'];
                                    $defpop = 100; 
              
                        
                                    //type of attack
                                    if($dataarray[0]['attack_type'] == 1){
                                        $type = 1;
                                        $scout = 1;
                                    }
                                    if($dataarray[0]['attack_type'] == 2){
                                        $type = 2;
                                    }
                                    if($dataarray[0]['attack_type'] == 3){
                                        $type = 3;
                                    }
                                    if($dataarray[0]['attack_type'] == 4){
                                        $type = 4;
                                    }
									
                                    $def_ab = Array (
                                        "b1" => 0, // Blacksmith level
                                        "b2" => 0, // Blacksmith level
                                        "b3" => 0, // Blacksmith level
                                        "b4" => 0, // Blacksmith level
                                        "b5" => 0, // Blacksmith level
                                        "b6" => 0, // Blacksmith level
                                        "b7" => 0, // Blacksmith level
                                        "b8" => 0); // Blacksmith level
                                    
                                    $att_ab = Array ( 
                                        "a1" => 0, // armoury level
                                        "a2" => 0, // armoury level
                                        "a3" => 0, // armoury level
                                        "a4" => 0, // armoury level
                                        "a5" => 0, // armoury level
                                        "a6" => 0, // armoury level
                                        "a7" => 0, // armoury level
                                        "a8" => 0); // armoury level
                                        
                                        $empty='1'; 
                                        $tblevel = '0'; 
                                        $stonemason = "1";
   
        }
            $battlepart = $battle->calculateBattle($Attacker,$Defender,$def_wall,$att_tribe,$def_tribe,$residence,$attpop,$defpop,$type,$def_ab,$att_ab,$tblevel,$stonemason,$walllevel);
            
            //units attack string for battleraport
            $unitssend_att = ''.$data['t1'].','.$data['t2'].','.$data['t3'].','.$data['t4'].','.$data['t5'].','.$data['t6'].','.$data['t7'].','.$data['t8'].','.$data['t9'].','.$data['t10'].','.$data['t11'].'';
            
            //units defence string for battleraport 
                $unitssend_def[1] = ''.$Defender['u1'].','.$Defender['u2'].','.$Defender['u3'].','.$Defender['u4'].','.$Defender['u5'].','.$Defender['u6'].','.$Defender['u7'].','.$Defender['u8'].','.$Defender['u9'].','.$Defender['u10'].','.$Defender['hero'].'';
				
                $unitssend_def[2] = ''.$Defender['u11'].','.$Defender['u12'].','.$Defender['u13'].','.$Defender['u14'].','.$Defender['u15'].','.$Defender['u16'].','.$Defender['u17'].','.$Defender['u18'].','.$Defender['u19'].','.$Defender['u20'].','.$Defender['hero'].'';
				
                $unitssend_def[3] = ''.$Defender['u21'].','.$Defender['u22'].','.$Defender['u23'].','.$Defender['u24'].','.$Defender['u25'].','.$Defender['u26'].','.$Defender['u27'].','.$Defender['u28'].','.$Defender['u29'].','.$Defender['u30'].','.$Defender['hero'].'';
				
                $unitssend_def[4] = ''.$Defender['u31'].','.$Defender['u32'].','.$Defender['u33'].','.$Defender['u34'].','.$Defender['u35'].','.$Defender['u36'].','.$Defender['u37'].','.$Defender['u38'].','.$Defender['u39'].','.$Defender['u40'].','.$Defender['hero'].'';
				
                $unitssend_def[5] = ''.$Defender['u41'].','.$Defender['u42'].','.$Defender['u43'].','.$Defender['u44'].','.$Defender['u45'].','.$Defender['u46'].','.$Defender['u47'].','.$Defender['u48'].','.$Defender['u49'].','.$Defender['u50'].','.$Defender['hero'].'';
                $unitssend_deff[1] = '?,?,?,?,?,?,?,?,?,?,?,'; 
                $unitssend_deff[2] = '?,?,?,?,?,?,?,?,?,?,?,'; 
                $unitssend_deff[3] = '?,?,?,?,?,?,?,?,?,?,?,'; 
                $unitssend_deff[4] = '?,?,?,?,?,?,?,?,?,?,?,'; 
                $unitssend_deff[5] = '?,?,?,?,?,?,?,?,?,?,?,'; 
            //how many troops died? for battleraport
            if($battlepart['casualties_attacker'][1] == 0) { $dead1 = 0; } else { $dead1 = $battlepart['casualties_attacker'][1]; }
            if($battlepart['casualties_attacker'][2] == 0) { $dead2 = 0; } else { $dead2 = $battlepart['casualties_attacker'][2]; }
            if($battlepart['casualties_attacker'][3] == 0) { $dead3 = 0; } else { $dead3 = $battlepart['casualties_attacker'][3]; }
            if($battlepart['casualties_attacker'][4] == 0) { $dead4 = 0; } else { $dead4 = $battlepart['casualties_attacker'][4]; }
            if($battlepart['casualties_attacker'][5] == 0) { $dead5 = 0; } else { $dead5 = $battlepart['casualties_attacker'][5]; }
            if($battlepart['casualties_attacker'][6] == 0) { $dead6 = 0; } else { $dead6 = $battlepart['casualties_attacker'][6]; }
            if($battlepart['casualties_attacker'][7] == 0) { $dead7 = 0; } else { $dead7 = $battlepart['casualties_attacker'][7]; }
            if($battlepart['casualties_attacker'][8] == 0) { $dead8 = 0; } else { $dead8 = $battlepart['casualties_attacker'][8]; }
            if($battlepart['casualties_attacker'][9] == 0) { $dead9 = 0; } else { $dead9 = $battlepart['casualties_attacker'][9]; }
            if($battlepart['casualties_attacker'][10] == 0) { $dead10 = 0; } else { $dead10 = $battlepart['casualties_attacker'][10]; }
			if($battlepart['casualties_attacker'][11] == 0) { $dead11 = 0; } else { $dead11 = $battlepart['casualties_attacker'][11]; }

            
			//kill own defence
			$q = "SELECT * FROM ".TB_PREFIX."units WHERE vref='".$data['to']."'";
			$unitlist = $database->query_return($q); 
			$start = ($targettribe-1)*10+1;
			$end = ($targettribe*10);
			//FIX
			for($i=$start;$i<=$end;$i++) {
				if($unitlist){
					$dead[$i] += round($battlepart[2] * $unitlist[0]['u'.$i]);
					$database->modifyUnit($data['to'],$i,round($battlepart[2]*$unitlist[0]['u'.$i]),0);
				}
			}
			if($unitlist['hero']){
				$dead['hero'] += round($battlepart[2] * $unitlist[0]['hero']);
				$database->modifyUnit($data['to'],'hero',round($battlepart[2]*$unitlist[0]['hero']),0);
			}
			
			
			//kill other defence in village
			if(count($database->getEnforceVillage($data['to'],0)) > 0) {
				foreach($database->getEnforceVillage($data['to'],0) as $enforce) {
					$life=''; $notlife=''; $wrong='0';
					$tribe = $database->getUserField($database->getVillageField($enforce['from'],"owner"),"tribe",0);
					$start = ($tribe-1)*10+1;
						
					if($tribe == 1){ $rom='1'; }
					else if($tribe == 2){ $ger='1'; }
					else if($tribe == 3){ $gal='1'; }
					else if($tribe == 4){ $nat='1'; }
					else { $natar='1'; }
					
					for($i=$start;$i<=($start+9);$i++) {
						if($enforce['u'.$i] != 0){
							$database->modifyEnforce($enforce['id'],$i,round($battlepart[2]*$enforce['u'.$i]),0);
							$dead[$i] += round($battlepart[2]*$enforce['u'.$i]);
							if($dead[$i] != $enforce['u'.$i]){
								$wrong='1';
							}
						} else {
							$dead[$i]='0';
						}
						$notlife="".$notlife.",".$dead[$i]."";
						$life="".$life.",".$enforce['u'.$i.'']."";
					}
					if($enforce['hero']>'0'){
						$database->modifyEnforce($enforce['id'],'hero',round($battlepart[2]*$enforce['hero']),0);
						$dead['hero'] += round($battlepart[2]*$enforce['hero']);
						if($dead['hero']!=$enforce['hero']){
							$wrong='1';
						}
					} else {
						$dead['hero']='0';
					}
					$notlife="".$notlife.",".$dead['hero']."";
					$life="".$life.",".$enforce['hero']."";
					//NEED TO SEND A RAPPORTAGE!!!
					$data2 = ''.$database->getVillageField($enforce['from'],"owner").','.$to['wref'].','.addslashes($to['name']).','.$tribe.''.$life.''.$notlife.'';
					$fromAlly = $database->getUserField($database->getVillageField($enforce['from'],"owner"),'alliance',0);
					$database->addNotice($database->getVillageField($enforce['from'],"owner"),$to['wref'],$fromAlly,15,'Támogatás'.addslashes($to['name']).' Támadtak',$data2,$AttackArrivalTime);
					//delete reinf sting when its killed all.
					if($wrong=='0'){
						$database->deleteReinf($enforce['id']);
					}
				}
			}
			
			
			$unitsdead_def[1] = ''.$dead[1].','.$dead[2].','.$dead[3].','.$dead[4].','.$dead[5].','.$dead[6].','.$dead[7].','.$dead[8].','.$dead[9].','.$dead[10].','.$dead['hero'].'';
			$unitsdead_def[2] = ''.$dead['11'].','.$dead['12'].','.$dead['13'].','.$dead['14'].','.$dead['15'].','.$dead['16'].','.$dead['17'].','.$dead['18'].','.$dead['19'].','.$dead['20'].','.$dead['hero'].'';
			$unitsdead_def[3] = ''.$dead['21'].','.$dead['22'].','.$dead['23'].','.$dead['24'].','.$dead['25'].','.$dead['26'].','.$dead['27'].','.$dead['28'].','.$dead['29'].','.$dead['30'].','.$dead['hero'].'';
			$unitsdead_def[4] = ''.$dead['31'].','.$dead['32'].','.$dead['33'].','.$dead['34'].','.$dead['35'].','.$dead['36'].','.$dead['37'].','.$dead['38'].','.$dead['39'].','.$dead['40'].','.$dead['hero'].'';
			$unitsdead_def[5] = ''.$dead['41'].','.$dead['42'].','.$dead['43'].','.$dead['44'].','.$dead['45'].','.$dead['46'].','.$dead['47'].','.$dead['48'].','.$dead['49'].','.$dead['50'].','.$dead['hero'].'';
			
			$unitsdead_deff[1] = '?,?,?,?,?,?,?,?,?,?,?,';
			$unitsdead_deff[2] = '?,?,?,?,?,?,?,?,?,?,?,';
			$unitsdead_deff[3] = '?,?,?,?,?,?,?,?,?,?,?,';
			$unitsdead_deff[4] = '?,?,?,?,?,?,?,?,?,?,?,';
			$unitsdead_deff[5] = '?,?,?,?,?,?,?,?,?,?,?,';

            // Set units returning from attack 
            $database->modifyAttack($data['ref'],1,$dead1);
            $database->modifyAttack($data['ref'],2,$dead2);
            $database->modifyAttack($data['ref'],3,$dead3);
            $database->modifyAttack($data['ref'],4,$dead4);
            $database->modifyAttack($data['ref'],5,$dead5);
            $database->modifyAttack($data['ref'],6,$dead6);
            $database->modifyAttack($data['ref'],7,$dead7);
            $database->modifyAttack($data['ref'],8,$dead8);
            $database->modifyAttack($data['ref'],9,$dead9);
            $database->modifyAttack($data['ref'],10,$dead10);
			$database->modifyAttack($data['ref'],11,$dead11);
            

            $unitsdead_att = ''.$dead1.','.$dead2.','.$dead3.','.$dead4.','.$dead5.','.$dead6.','.$dead7.','.$dead8.','.$dead9.','.$dead10.','.$dead11.'';
            //$unitsdead_def = ''.$dead11.','.$dead12.','.$dead13.','.$dead14.','.$dead15.','.$dead16.','.$dead17.','.$dead18.','.$dead19.','.$dead20.'';

            
            //top 10 attack and defence update
            $totaldead_att = ($dead1+$dead2+$dead3+$dead4+$dead5+$dead6+$dead7+$dead8+$dead9+$dead10+$dead11);
            for($i=1;$i<=50;$i++) {
            	$totaldead_def += $dead[''.$i.''];
            }
            $database->modifyPoints($toF['owner'],'dpall',$totaldead_att );
            $database->modifyPoints($from['owner'],'apall',$totaldead_def);
            $database->modifyPoints($toF['owner'],'dp',$totaldead_att );
            $database->modifyPoints($from['owner'],'ap',$totaldead_def);
            $database->modifyPointsAlly($targetally,'Adp',$totaldead_att );
            $database->modifyPointsAlly($ownally,'Aap',$totaldead_def);
            $database->modifyPointsAlly($targetally,'dp',$totaldead_att );
            $database->modifyPointsAlly($ownally,'ap',$totaldead_def);
                    
                
            
            if (!$isoasis){  
				// get toatal cranny value:
				$buildarray = $database->getResourceLevel($data['to']);
				$cranny = 0;    
				for($i=19;$i<39;$i++){
					if($buildarray['f'.$i.'t']==23){
						$cranny += $bid23[$buildarray['f'.$i.'']]['attri'];
					}
				}

            //cranny efficiency
            $atk_bonus = ($owntribe == 2)? (4/5) : 1;
            $def_bonus = ($targettribe == 3)? 2 : 1;
            
            $cranny_eff = ($cranny * $atk_bonus)*$def_bonus;
            
            // work out available resources.
            $this->updateRes($data['to'],$to['owner']);
            $this->pruneResource();
            
            $totclay = $database->getVillageField($data['to'],'clay');
            $totiron = $database->getVillageField($data['to'],'iron');
            $totwood = $database->getVillageField($data['to'],'wood');
            $totcrop = $database->getVillageField($data['to'],'crop');
            }else{
            $cranny_eff = 0;
            
            // work out available resources.
            $this->updateORes($data['to']);
            $this->pruneOResource();
            
            $totclay = $database->getOasisField($data['to'],'clay');
            $totiron = $database->getOasisField($data['to'],'iron');
            $totwood = $database->getOasisField($data['to'],'wood');
            $totcrop = $database->getOasisField($data['to'],'crop');    
            }            
            $avclay = floor($totclay - $cranny_eff);
            $aviron = floor($totiron - $cranny_eff);
            $avwood = floor($totwood - $cranny_eff);
            $avcrop = floor($totcrop - $cranny_eff);
                        
            $avclay = ($avclay < 0)? 0 : $avclay;
            $aviron = ($aviron < 0)? 0 : $aviron;
            $avwood = ($avwood < 0)? 0 : $avwood;
            $avcrop = ($avcrop < 0)? 0 : $avcrop;
            
            
            $avtotal = array($avwood, $avclay, $aviron,  $avcrop);
            
            $av = $avtotal;
            
            // resources (wood,clay,iron,crop)
            $steal = array(0,0,0,0);
            
            //bounty variables
            $btotal = $battlepart['bounty'];
            $bmod = 0;            
            
            
            for($i = 0; $i<5; $i++)
            {
                for($j=0;$j<4;$j++)
                {
                    if(isset($avtotal[$j]))
                    {
                        if($avtotal[$j]<1)
                            unset($avtotal[$j]);
                    }
                }
                if(!$avtotal)
                {
                     // echo 'array empty'; *no resources left to take.
                    break;
                }
                if($btotal <1 && $bmod <1)
                    break;
                if($btotal<1)
                {
                    while($bmod)
                    {
                        //random select
                        $rs = array_rand($avtotal);
                        if(isset($avtotal[$rs]))
                        {
                            $avtotal[$rs] -= 1;
                            $steal[$rs] += 1;
                            $bmod -= 1;
                        }
                    }
                }
                
                // handle unballanced amounts.
                $btotal +=$bmod;
                $bmod = $btotal%count($avtotal);
                $btotal -=$bmod;
                $bsplit = $btotal/count($avtotal);
        
                $max_steal = (min($avtotal) < $bsplit)? min($avtotal): $bsplit;
            
                for($j=0;$j<4;$j++)
                {
                    if(isset($avtotal[$j]))
                    {
                        $avtotal[$j] -= $max_steal;
                        $steal[$j] += $max_steal;
                        $btotal -= $max_steal;
                    }
                }
            }
            
            
            //work out time of return
            $start = ($owntribe-1)*10+1;
            $end = ($owntribe*10);
            
            $unitspeeds = array(6,5,7,16,14,10,4,3,4,5,
                                7,7,6,9,10,9,4,3,4,5,
                                7,6,17,19,16,13,4,3,4,5,
                                7,7,6,9,10,9,4,3,4,5,
                                7,7,6,9,10,9,4,3,4,5);
            
            $speeds = array();
    
            //find slowest unit.
            for($i=1;$i<=11;$i++){
                if ($data['t'.$i] > $battlepart['casualties_attacker'][$i]) {
					if($i == 11){
						if($heroarray) { reset($heroarray); }
						$getVillage = $database->getVillage($data['vref']);
						$heroarray = $database->getHeroData($getVillage['owner']);
                		$speeds[] = $heroarray['speed'];
					}else{
						if($unitarray) { reset($unitarray); }
                		$unitarray = $GLOBALS["u".(($owntribe-1)*10+$i)];
                		$speeds[] = $unitarray['speed'];
					}
                }
            }


// Data for when troops return.
    
                //catapulten kijken :D
            $info_cat = $info_chief = $info_ram = ",";
            
            if ($type=='3'){
                if ($rams!='0'){
                    if (isset($empty)){
                        $info_ram = "".$ram_pic.", A fal már le volt rombolva.";
                    } else

                      if ($battlepart[8]>$battlepart[7]){
                            $info_ram = "".$ram_pic.", A fal le lett rombolva.";
                            $database->setVillageLevel($data['to'],"f".$wallid."",'0');
                            $database->setVillageLevel($data['to'],"f".$wallid."t",'0');
                            $pop=$this->recountPop($data['to']);

                    }elseif ($battlepart[8]==0){
                    
                        $info_ram = "".$ram_pic.",A fal nem sérült meg.";
                    }else{
            
                        $demolish=$battlepart[8]/$battlepart[7];
                        $totallvl = round(sqrt(pow(($walllevel+0.5),2)-($battlepart[8]*8)));
                    $info_ram = "".$ram_pic.",A fal ".$walllevel." szintről ".$totallvl." szintre lett rombolva.";
                            $database->setVillageLevel($data['to'],"f".$wallid."",$totallvl);

                    }                  
                }
            }
            if ($type=='3')
{
    if ($catp!='0')
    {
        
        if($toF['pop']<=0)
        {
              
                 $info_cat = ",".$catp_pic.", A falu le lett rombolva.";
        }
        else
        {
			$basearray = $data['to']; 
			if ($data['ctar2']==0)
            {                               
                $bdo2=mysql_query("select * from " . TB_PREFIX . "fdata where vref = $basearray");
                $bdo=mysql_fetch_array($bdo2);
                
                $rand=$data['ctar1'];
                
                if ($rand != 0)
                {
					$_rand=array();
                    $__rand=array();
                    $j=0;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i.'t']==$rand && $bdo['f'.$i]>0) 
                        {
                            $j++;
                            $_rand[$j]=$bdo['f'.$i];    
                            $__rand[$j]=$i;             
                        }
                    }
                    if (count($_rand)>0)
                    {
                        if (max($_rand)<=0) $rand=0;
                        else
                        {
                            $rand=rand(1, $j);
                            $rand=$__rand[$rand];
                        }  
                    }                                
                    else
                    {
                        $rand=0;
                    }                                 
                }
                
                if ($rand == 0)
                {
                    $list=array();
                    $j=1;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i] > 0)
                        {
                            $list[$j]=$i;
                            $j++;
                        }
                    }
                    $rand=rand(1, $j);
                    $rand=$list[$rand];
                }
				
				$tblevel = $bdo['f'.$rand];
                $tbgid = $bdo['f'.$rand.'t'];
                $tbid = $rand; 
                $needed_cata = round((($battlepart[5] * (pow($tblevel,2) + $tblevel + 1)) / (8 * (round(200 * pow(1.0205,$battlepart[6]))/200) / (1 * $bid34[$stonemason]['attri']/100))) + 0.5);
                if ($battlepart[4]>$needed_cata)
                {
                        $info_cat = "".$tbgid.",".$this->procResType($tbgid)." le lett rombolva.";
						
				$database->setVillageLevel($data['to'],"f".$tbid."",'0');
                    if($tbid>=19) { $database->setVillageLevel($data['to'],"f".$tbid."t",'0'); }
                    $buildarray = $GLOBALS["bid".$tbgid];
                    if ($tbgid==10 || $tbgid==38) {
                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxstore=$t_sql['maxstore']-$buildarray[$tblevel]['attri'];
                        if ($tmaxstore<800) $tmaxstore=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }
                       if ($tbgid==11 || $tbgid==39) {                    
                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxcrop=$t_sql['maxcrop']-$buildarray[$tblevel]['attri'];
                        if ($tmaxcrop<800) $tmaxcrop=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }                                    
                    $pop=$this->recountPop($data['to']);
					if($pop=='0')
                    { 
                        $varray = $database->getProfileVillages($to['owner']);
                        if(count($varray)!='1' AND $to['capital']!='1'){
                                $q = "DELETE FROM ".TB_PREFIX."abdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."bdata where wid = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."enforcement where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."fdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."market where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."movement where to = ".$data['to']." or from = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."odata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."research where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."tdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."training where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."units where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."vdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "UPDATE ".TB_PREFIX."wdata set occupied = 0 where id = ".$data['to'];
                                $database->query($q);
                                $logging->VillageDestroyCatalog($data['to']);
                        }
                    }
                }
                elseif ($battlepart[4]==0)
                {    
					$info_cat = "".$tbgid.",A ".$this->procResType($tbgid)." nem sérült meg.";
					}
                else
                {   
					$demolish=$battlepart[4]/$needed_cata;
                    $totallvl = round(sqrt(pow(($tblevel+0.5),2)-($battlepart[4]*8)));
                    if ($tblevel==$totallvl) 
                        $info_cata=" nem sérült meg.";
                    else
                    {
					
					
					
                               
            
                    $info_cata= " A célpont ".$totallvl." szintről ".$tblevel." szintre lett rombolva.";
                  $buildarray = $GLOBALS["bid".$tbgid];
                        if ($tbgid==10 || $tbgid==38) {
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxstore=$t_sql['maxstore']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxstore<800) $tmaxstore=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }
                        if ($tbgid==11 || $tbgid==39) {                    
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxcrop=$t_sql['maxcrop']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxcrop<800) $tmaxcrop=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }       
                        $pop=$this->recountPop($data['to']);
                    }
                    $info_cat = "".$tbgid.",".$this->procResType($tbgid).$info_cata;
                    $database->setVillageLevel($data['to'],"f".$tbid."",$totallvl);
                }
            }
			else
            {
                $bdo2=mysql_query("select * from " . TB_PREFIX . "fdata where vref = $basearray");
                $bdo=mysql_fetch_array($bdo2);
                $rand=$data['ctar1'];
                if ($rand != 0)
                {
                    $_rand=array();
                    $__rand=array();
                    $j=0;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i.'t']==$rand && $bdo['f'.$i]>0)
                        {
                            $j++;
                            $_rand[$j]=$bdo['f'.$i];  
                            $__rand[$j]=$i;           
                        }
                    }
                    if (count($_rand)>0)
                    {
                        if (max($_rand)<=0) $rand=0;
                        else
                        {
                            $rand=rand(1, $j);
                            $rand=$__rand[$rand];
                        }  
                    }                                
                    else
                    {
                        $rand=0;
                    }                                 
                }
                
                if ($rand == 0)
                {
                    $list=array();
                    $j=0;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i] > 0)
                        {
                            $j++;
                            $list[$j]=$i;
                        }
                    }
                    $rand=rand(1, $j);
                    $rand=$list[$rand];
                }
				$tblevel = $bdo['f'.$rand];
                $tbgid = $bdo['f'.$rand.'t'];
                $tbid = $rand; 
                $needed_cata = round((($battlepart[5] * (pow($tblevel,2) + $tblevel + 1)) / (8 * (round(200 * pow(1.0205,$battlepart[6]))/200) / (1 * $bid34[$stonemason]['attri']/100))) + 0.5);
                if (($battlepart[4]/2)>$needed_cata)
                {
					$info_cat = "".$tbgid.", ".$this->procResType($tbgid)." Le lett rombolva.";
                    $database->setVillageLevel($data['to'],"f".$tbid."",'0');
                    if($tbid>=19) { $database->setVillageLevel($data['to'],"f".$tbid."t",'0'); }
                    $buildarray = $GLOBALS["bid".$tbgid];
                    if ($tbgid==10 || $tbgid==38) {
                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxstore=$t_sql['maxstore']-$buildarray[$tblevel]['attri'];
                        if ($tmaxstore<800) $tmaxstore=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }
                    if ($tbgid==11 || $tbgid==39) {                    
                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxcrop=$t_sql['maxcrop']-$buildarray[$tblevel]['attri'];
                        if ($tmaxcrop<800) $tmaxcrop=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }                                    
                    $pop=$this->recountPop($data['to']);
                    if($pop=='0')
                    { 
                        $varray = $database->getProfileVillages($to['owner']);
                        if(count($varray)!='1' AND $to['capital']!='1'){
                                $q = "DELETE FROM ".TB_PREFIX."abdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."bdata where wid = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."enforcement where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."fdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."market where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."movement where to = ".$data['to']." or from = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."odata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."research where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."tdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."training where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."units where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."vdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "UPDATE ".TB_PREFIX."wdata set occupied = 0 where id = ".$data['to'];
                                $database->query($q);
                                $logging->VillageDestroyCatalog($data['to']);
                        }
                    }
                }
                elseif ($battlepart[4]==0)
                {                        
                    $info_cat = "".$tbgid.",A ".$this->procResType($tbgid)." nem sérült meg.";
                }
                else
                {                
                    $demolish=($battlepart[4]/2)/$needed_cata;
                    $totallvl = round(sqrt(pow(($tblevel+0.5),2)-(($battlepart[4]/2)*8)));
                    if ($tblevel==$totallvl) 
                        $info_cata=" Nem sérült meg.";
                    else
                    {
                        $info_cata=" A célpont ".$totallvl." szintről ".$tblevel." szintre lett lerombolva.";
                        $buildarray = $GLOBALS["bid".$tbgid];
                        if ($tbgid==10 || $tbgid==38) {
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxstore=$t_sql['maxstore']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxstore<800) $tmaxstore=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }
                        if ($tbgid==11 || $tbgid==39) {                    
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxcrop=$t_sql['maxcrop']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxcrop<800) $tmaxcrop=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }       
                        $pop=$this->recountPop($data['to']);
                    }
                    $info_cat = "".$tbgid.",".$this->procResType($tbgid).$info_cata;
                    $database->setVillageLevel($data['to'],"f".$tbid."",$totallvl);
                }
				$bdo2=mysql_query("select * from " . TB_PREFIX . "fdata where vref = $basearray");
                $bdo=mysql_fetch_array($bdo2);
                $rand=$data['ctar2'];
                if ($rand != 99)
                {
                    $_rand=array();
                    $__rand=array();
                    $j=0;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i.'t']==$rand && $bdo['f'.$i]>0) 
                        {
                            $j++;
                            $_rand[$j]=$bdo['f'.$i];   
                            $__rand[$j]=$i;             
                        }
                    }
                    if (count($_rand)>0)
                    {
                        if (max($_rand)<=0) $rand=99;
                        else
                        {
                            $rand=rand(1, $j);
                            $rand=$__rand[$rand];
                        }  
                    }                                
                    else
                    {
                        $rand=99;
                    }                                 
                }
                
                if ($rand == 99)
                {
                    $list=array();
                    $j=0;
                    for ($i=1;$i<=41;$i++)
                    {
                        if ($i==41) $i=99;
                        if ($bdo['f'.$i] > 0)
                        {
                            $j++;
                            $list[$j]=$i;
                        }
                    }
                    $rand=rand(1, $j);
                    $rand=$list[$rand];
                }
                                            
                $tblevel = $bdo['f'.$rand];
                $tbgid = $bdo['f'.$rand.'t'];
                $tbid = $rand; 
                $needed_cata = round((($battlepart[5] * (pow($tblevel,2) + $tblevel + 1)) / (8 * (round(200 * pow(1.0205,$battlepart[6]))/200) / (1 * $bid34[$stonemason]['attri']/100))) + 0.5);
                if (($battlepart[4]/2)>$needed_cata)
                {
					$info_cat .= "<br><tbody class=\"goods\"><tr><th>Információk</th><td colspan=\"11\">
                    <img class=\"unit u".$tbgid."\" src=\"img/x.gif\" alt=\"Katapult\" title=\"Katapult\" /> ".$this->procResType($tbgid)." le lett rombolva..</td></tr></tbody>";
                    $database->setVillageLevel($data['to'],"f".$tbid."",'0');
                    if($tbid>=19) { $database->setVillageLevel($data['to'],"f".$tbid."t",'0'); }
                    $buildarray = $GLOBALS["bid".$tbgid];
                    if ($tbgid==10 || $tbgid==38) {

                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxstore=$t_sql['maxstore']-$buildarray[$tblevel]['attri'];
                        if ($tmaxstore<800) $tmaxstore=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }
                    if ($tbgid==11 || $tbgid==39) {                    
                        $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                        $t_sql=mysql_fetch_array($tsql);
                        $tmaxcrop=$t_sql['maxcrop']-$buildarray[$tblevel]['attri'];
                        if ($tmaxcrop<800) $tmaxcrop=800;
                        $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                        $database->query($q);
                    }                                  
                    $pop=$this->recountPop($data['to']);
                    if($pop=='0')
                    { 
                        $varray = $database->getProfileVillages($to['owner']);
                        if(count($varray)!='1' AND $to['capital']!='1'){
                                $q = "DELETE FROM ".TB_PREFIX."abdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."bdata where wid = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."enforcement where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."fdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."market where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."movement where to = ".$data['to']." or from = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."odata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."research where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."tdata where vref = ".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."training where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."units where vref =".$data['to'];
                                $database->query($q);
                                $q = "DELETE FROM ".TB_PREFIX."vdata where wref = ".$data['to'];
                                $database->query($q);
                                $q = "UPDATE ".TB_PREFIX."wdata set occupied = 0 where id = ".$data['to'];
                                $database->query($q);
                                $logging->VillageDestroyCatalog($data['to']);
                        }
                    }
                }
                elseif ($battlepart[4]==0){                        
                    $info_cat .= "<br><tbody class=\"goods\"><tr><th>Információk</th><td colspan=\"11\">
                    <img class=\"unit u".$tbgid."\" src=\"img/x.gif\" alt=\"Katapult\" title=\"Katapult\" /> A ".$this->procResType($tbgid)." nem sérült meg.</td></tr></tbody>";
                }else{                
                    $demolish=($battlepart[4]/2)/$needed_cata;
                    $totallvl = round(sqrt(pow(($tblevel+0.5),2)-(($battlepart[4]/2)*8)));
                    if ($tblevel==$totallvl){
                        $info_cata=" Nincs sérülés.";
					}else{
                        $info_cata=" A célpont ".$totallvl." szintről ".$tblevel." szintre lett lerombolva.";
                        $buildarray = $GLOBALS["bid".$tbgid];
                        if ($tbgid==10 || $tbgid==38) {
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxstore=$t_sql['maxstore']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxstore<800) $tmaxstore=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`='".$tmaxstore."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }
                        if ($tbgid==11 || $tbgid==39) {                    
                            $tsql=mysql_query("select `maxstore`,`maxcrop` from ".TB_PREFIX."vdata where wref=".$data['to']."");
                            $t_sql=mysql_fetch_array($tsql);
                            $tmaxcrop=$t_sql['maxcrop']+$buildarray[$totallvl]['attri']-$buildarray[$tblevel]['attri'];
                            if ($tmaxcrop<800) $tmaxcrop=800;
                            $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`='".$tmaxcrop."' WHERE wref=".$data['to'];
                            $database->query($q);
                        }       
                        $pop=$this->recountPop($data['to']);
                    }
                        
                    $info_cat .= "<br><tbody class=\"goods\"><tr><th>Információk</th><td colspan=\"11\">
                    <img class=\"unit u".$tbgid."\" src=\"img/x.gif\" alt=\"Katapult\" title=\"Katapult\" /> ".$this->procResType($tbgid).$info_cata."</td></tr></tbody>";
                    $database->setVillageLevel($data['to'],"f".$tbid."",$totallvl);
                }
            }
        }    
    }
}  
            
        //chiefing village
        //there are senators
        if(($data['t9']-$dead9)>0){
            $varray = $database->getProfileVillages($to['owner']);
            //kijken of laatste dorp is, of hoofddorp
            if(count($varray)!='1' AND $to['capital']!='1'){
                //if there is no Palace/Residence
                for ($i=18; $i<39; $i++){
                    if ($database->getFieldLevel($data['to'],"".$i."t")==25 or $database->getFieldLevel($data['to'],"".$i."t")==26){
                        $nochiefing='1';
                            $info_chief = "".$chief_pic.", A Palota vagy a Rezidencia még mindíg áll.";
                    }
                }
                if(!isset($nochiefing)){
                    //$info_chief = "".$chief_pic.",You don't have enought CP to chief a village.";
                    for ($i=0; $i<($data['t9']-$dead9); $i++){
                    $rand+=rand(15,25);
                    }
                    //loyalty is more than 0
                    if(($toF['loyalty']-$rand)>0){
                        $info_chief = "".$chief_pic.", A falu hűsége ".$toF['loyalty']."-ról ".($toF['loyalty']-$rand)."-ra esett.";
                        $database->setVillageField($data['to'],loyalty,($toF['loyalty']-$rand));
                    } else {
                    //you took over the village
                        $artifact = $database->getOwnArtefactInfo($data['to']);
                        $info_chief = "".$chief_pic.", A falu lakosai úgy döntöttek, hogy mostantól a te birodalmadhoz akarnak tartozni..";
                        if ($artifact['vref'] == $data['to']){
                         $database->claimArtefact($data['to'],$data['to'],$database->getVillageField($data['from'],"owner"));
                        }
                        $database->setVillageField($data['to'],loyalty,33);
                        $database->setVillageField($data['to'],owner,$database->getVillageField($data['from'],"owner"));
                        //destroy wall
                        $database->setVillageLevel($data['to'],"f40",0);
                        $database->setVillageLevel($data['to'],"f40t",0);
                        $database->clearExpansionSlot($data['to']);
                        //kill a chief
                        $database->modifyAttack($data['ref'],9,1);
                        
                        
                        $exp1 = $database->getVillageField($data['from'],'exp1');
                        $exp2 = $database->getVillageField($data['from'],'exp2');
                        $exp3 = $database->getVillageField($data['from'],'exp3');
                        
                        if($exp1 == 0){
                            $exp = 'exp1';
                            $value = $data['to'];
                        }
                        elseif($exp2 == 0){
                            $exp = 'exp2';
                            $value = $data['to'];
                        }
                        else{
                            $exp = 'exp3';
                            $value = $data['to'];
                        }
                        $database->setVillageField($data['from'],$exp,$value);
                    }
                }
            } else {
                $info_chief = "".$chief_pic.", A főfalut nem lehet elfoglalni.";
            }
        }
        
		if($data['t11']>0) { 
			if ($isoasis != 0) {         
					//count oasis troops: $troops_o 
					$troops_o=0; 
					$o_unit2=mysql_query("select * from ".TB_PREFIX."units where `vref`='".$data['to']."'"); 
					$o_unit=mysql_fetch_array($o_unit2); 
					for ($i=1;$i<=50;$i++) { 
						$troops_o+=$o_unit[$i]; 
					}                         
					 
					$o_unit2 = mysql_query("select * from ".TB_PREFIX."enforcement where `vref`='".$data['to']."'"); 
					while ($o_unit=@mysql_fetch_array($o_unit2)) { 
						for ($i=1;$i<=50;$i++) { 
							$troops_o+=$o_unit[$i]; 
						}                         
						$troops_o+=$o_unit['hero']; 
					}  
										 
					if ($troops_o<=0) { 
						//check hero mansion level 
						$hero_mansion_level=0; 
						$dbo2=mysql_query("select * from ".TB_PREFIX."fdata where `vref`='".$data['from']."'"); 
						$dbo=mysql_fetch_array($dbo2); 
						for ($i=19;$i<=40;$i++) { 
							if ($dbo['f'.$i.'t']==37) { 
								$hero_mansion_level=$dbo['f'.$i]; 
							} 
						} 
						 
						//check number of occupied oasis  
						$dbo2=mysql_query("select * from ".TB_PREFIX."odata where `conqured`='".$data['from']."'"); 
						$number_o=mysql_num_rows($dbo2); 
						 
						if ($number_o<3) {
							$needed_hero_mansion_level=$number_o*5+10; 
							if ($hero_mansion_level>=$needed_hero_mansion_level) { 
								$dbo2=mysql_query("select * from ".TB_PREFIX."odata where `wref`='".$data['to']."'"); 
								$dbo=mysql_fetch_array($dbo2); 
								$o_owner=$dbo['owner']; 
								$o_conqured=$dbo['conqured']; 
								$o_loyalty=$dbo['loyalty']; 
								$a_uid = $database->getVillageField($data['from'],"owner");
								if ($o_conqured=='0' or $o_conqured!=$data['from']) { 
									mysql_query("UPDATE ".TB_PREFIX."odata SET `conqured`='".$data['from']."', `owner`='".$a_uid."', `name`='Oázis elfoglalható', `lastupdated`='".time()."' WHERE `wref`='".$data['to']."' "); 
									mysql_query("UPDATE ".TB_PREFIX."wdata SET `occupied`='1' WHERE `id`='".$data['to']."' "); 
									$info_chief = "".$hero_pic.", A hősöd elfoglalta az oázist."; 
								} elseif($o_conqured==$data['from']) { 
									$info_chief = "".$hero_pic.", A hősöd már elfoglalta ezt az oázist korábban."; 
								} 
							} else {                                     
								$info_chief = "".$hero_pic.", Hogy, elfoglald az oázist, ".$needed_hero_mansion_level." szintű hősök házára van szükséged."; 
							}
						} else { 
							$info_chief = "".$hero_pic.", Már elfoglaltál 3 oázist."; 
						} 
					}
			}else{
				$artifact = $database->getOwnArtefactInfo($data['to']); 
				if ($artifact['vref'] == $data['to']){ 
					if($database->canClaimArtifact($artifact['vref'],$artifact['size'])) { 
						$database->claimArtefact($data['to'],$data['to'],$database->getVillageField($data['from'],"owner")); 
						$info_chief = "".$hero_pic.", A tervet sikeresen elhozta a hősöd.";   
					}else{ 
						$info_chief = "";
					} 
				} 
			}
		}  
		if($scout){
			if ($data['spy'] == 1){
				$info_spy = "".$spy_pic.",
<tbody><tr><td class=\"empty\" colspan=\"12\"></td></tr></tbody>
<tbody class=\"goods\">
	<tr><th>Nyersanyagok</th><td colspan=\"11\"><div class=\"res\"><div class=\"rArea\"><img class=\"r1\" src=\"img/x.gif\" title=\"Fa\">".round($totwood)."</div><div class=\"rArea\"><img class=\"r2\" src=\"img/x.gif\" title=\"Agyag\">".round($totclay)."</div><div class=\"rArea\"><img class=\"r3\" src=\"img/x.gif\" title=\"Vasérc\">".round($totiron)."</div><div class=\"rArea\"><img class=\"r4\" src=\"img/x.gif\" title=\"Búza\">".round($totcrop)."</div></div></td></tr></tbody>
<tbody class=\"goods\"><tr><th></th><td colspan=\"11\"><div class=\"res\"><div class=\"rArea\"><img class=\"gebIcon g23Icon\" src=\"img/x.gif\" title=\"Rejtekhely\">".$bid23[$crannylevel]['attri']."</div></div></td></tr></tbody>";
				
			}else if($data['spy'] == 2){
				if ($isoasis == 0){  
					$basearray = $database->getMInfo($data['to']);
					$resarray = $database->getResourceLevel($basearray['wref']);
					$crannylevel =0;
					$rplevel = 0;
					$walllevel = 0;
					for($j=19;$j<=40;$j++) {
						if($resarray['f'.$j.'t'] == 25 || $resarray['f'.$j.'t'] == 26 ) {
							$rplevel = $database->getFieldLevel($basearray['wref'],$j);
						}
					}
					for($j=19;$j<=40;$j++) {
						if($resarray['f'.$j.'t'] == 40) {
							$walllevel = $database->getFieldLevel($basearray['wref'],$j);
						}
					}
					for($j=19;$j<=40;$j++) {
						if($resarray['f'.$j.'t'] == 23) {
							$crannylevel = $database->getFieldLevel($basearray['wref'],$j);
						}
					}
				} else {
					$crannylevel = 0;
					$walllevel = 0;
					$rplevel = 0;
				}
                if($tribe == 1){
					$walltitle = "Kőfal";
				}elseif($tribe == 2){
					$walltitle = "Földfal";
				}else{
					$walltitle = "Fal";
				}
                                        
                $info_spy = "".$spy_pic.",
<tbody><tr><td class=\"empty\" colspan=\"12\"></td></tr></tbody>
<tbody class=\"goods\">
	<tr><th>Információk</th><td colspan=\"11\"><div class=\"res\">
<div class=\"rArea\">
<img class=\"gebIcon g".$rpid."Icon\" src=\"img/x.gif\" title=\"".$rptitle."\">A palota vagy a rezidencia ".$rplevel." szintű<Br>
<img class=\"gebIcon g3".$tribe."Icon\" src=\"img/x.gif\" title=\"".$walltitle."\">A ".$walltitle."  ".$walllevel." szintű
</div>
</div></td></tr></tbody>";
            
			}
                
			$data2 = ''.$from['owner'].','.$from['wref'].','.$owntribe.','.$unitssend_att.','.$unitsdead_att.',0,0,0,0,0,'.$to['owner'].','.$to['wref'].','.addslashes($to['name']).','.$targettribe.',,,'.$rom.','.$unitssend_def[1].','.$unitsdead_def[1].','.$ger.','.$unitssend_def[2].','.$unitsdead_def[2].','.$gal.','.$unitssend_def[3].','.$unitsdead_def[3].','.$nat.','.$unitssend_def[4].','.$unitsdead_def[4].','.$natar.','.$unitssend_def[5].','.$unitsdead_def[5].','.$info_ram.','.$info_cat.','.$info_chief.','.$info_spy.'';
		}else{
			$data2 = ''.$from['owner'].','.$from['wref'].','.$owntribe.','.$unitssend_att.','.$unitsdead_att.','.$steal[0].','.$steal[1].','.$steal[2].','.$steal[3].','.$battlepart['bounty'].','.$to['owner'].','.$to['wref'].','.addslashes($to['name']).','.$targettribe.',,,'.$rom.','.$unitssend_def[1].','.$unitsdead_def[1].','.$ger.','.$unitssend_def[2].','.$unitsdead_def[2].','.$gal.','.$unitssend_def[3].','.$unitsdead_def[3].','.$nat.','.$unitssend_def[4].','.$unitsdead_def[4].','.$natar.','.$unitssend_def[5].','.$unitsdead_def[5].','.$info_ram.','.$info_cat.','.$info_chief.','.$info_spy.'';
		}

            // When all troops die, sends no info.
            $data_fail = ''.$from['owner'].','.$from['wref'].','.$owntribe.','.$unitssend_att.','.$unitsdead_att.','.$steal[0].','.$steal[1].','.$steal[2].','.$steal[3].','.$battlepart['bounty'].','.$to['owner'].','.$to['wref'].','.addslashes($to['name']).','.$targettribe.',,,'.$rom.','.$unitssend_def[1].','.$unitsdead_deff[1].','.$ger.','.$unitssend_def[2].','.$unitsdead_deff[2].','.$gal.','.$unitssend_def[3].','.$unitsdead_deff[3].','.$nat.','.$unitssend_def[4].','.$unitsdead_deff[4].','.$natar.','.$unitssend_def[5].','.$unitsdead_deff[5].',,,';
            
            //Undetected and detected in here.
            if($scout){
                for($i=1;$i<=11;$i++){
                    if($battlepart['casualties_attacker'][$i]){
						$toAlly = $database->getUserField($to['owner'],'alliance',0);
                        $database->addNotice($to['owner'],$to['wref'],$toAlly,0,''.addslashes($from['name']).' kikémleli: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
                        break;
                    }
                }
            } else {
                if ($totaldead_def == 0){
					$getu = $database->getUnit($to['wref']);
					$toAlly = $database->getUserField($to['owner'],'alliance',0);
					for($i=1;$i<=50;$i++){ if($getu['u'.$i]!=0){ $eee = $getu['u'.$i]; } }
					if($eee==0){
						$database->addNotice($to['owner'],$to['wref'],$toAlly,7,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
					}else{
						$database->addNotice($to['owner'],$to['wref'],$toAlly,4,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
					}
					
            	} else {
					$toAlly = $database->getUserField($to['owner'],'alliance',0);
            		$database->addNotice($to['owner'],$to['wref'],$toAlly,5,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).' ',$data2,$AttackArrivalTime);
                }
            }    
            //to here
            
            // If the dead units not equal the ammount sent they will return and report
            if($unitsdead_att != $unitssend_att)
            {
                $endtime = $this->procDistanceTime($from,$to,min($speeds),1) + $AttackArrivalTime;
                //$endtime = $this->procDistanceTime($from,$to,min($speeds),1) + time();
                if($type == 1) {
					$fromAlly = $database->getUserField($from['owner'],'alliance',0);
                    $database->addNotice($from['owner'],$to['wref'],$fromAlly,0,''.addslashes($from['name']).' kikémleli: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
                }else {
                    if ($totaldead_att == 0){
					$fromAlly = $database->getUserField($from['owner'],'alliance',0);
                    $database->addNotice($from['owner'],$to['wref'],$fromAlly,1,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
                    }else{ 
					$fromAlly = $database->getUserField($from['owner'],'alliance',0);
                    $database->addNotice($from['owner'],$to['wref'],$fromAlly,2,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).'',$data2,$AttackArrivalTime);
                    }       
                }
                
				
                $database->setMovementProc($data['moveid']);
				$datar = "".$steal[0].",".$steal[1].",".$steal[2].",".$steal[3].",".$battlepart['bounty']."";
                $database->addMovement(4,$to['wref'],$from['wref'],$data['ref'],$datar,$endtime);
                
                // send the bounty on type 6.
                if($type !== 1)
                {
                    $reference = $database->sendResource($steal[0],$steal[1],$steal[2],$steal[3],0,0);
				if($isoasis==0){
                    $database->modifyResource($to['wref'],$steal[0],$steal[1],$steal[2],$steal[3],0);
				}else{
					$database->modifyOasisResource($to['wref'],$steal[0],$steal[1],$steal[2],$steal[3],0);
				}
                    $database->addMovement(6,$to['wref'],$from['wref'],$reference,$datar,$endtime);
                    //$database->updateVillage($to['wref']);
                    $totalstolentaken=($totalstolentaken-($steal[0]+$steal[1]+$steal[2]+$steal[3]));
                    $database->modifyPoints($from['owner'],'RR',$steal[0]+$steal[1]+$steal[2]+$steal[3]);
                    $database->modifyPoints($to['owner'],'RR',$totalstolentaken);
                    $database->modifyPointsAlly($targetally,'RR',$totalstolentaken );
                }
            }
            else //else they die and don't return or report.
            {
                $database->setMovementProc($data['moveid']);
                if($type == 1){
					$fromAlly = $database->getUserField($from['owner'],'alliance',0);
                    $database->addNotice($from['owner'],$to['wref'],$fromAlly,0,''.addslashes($from['name']).' kikémleli: '.addslashes($to['name']).'',$data_fail,$AttackArrivalTime);
                }else{
					$fromAlly = $database->getUserField($from['owner'],'alliance',0);
                    $database->addNotice($from['owner'],$to['wref'],$fromAlly,3,''.addslashes($from['name']).' támadja: '.addslashes($to['name']).'',$data_fail,$AttackArrivalTime); 
                    }
            }
        

            
        
        }
            if(file_exists("GameEngine/Prevention/sendunits.txt")) {
                @unlink("GameEngine/Prevention/sendunits.txt");
            }
    }
    
    private function sendreinfunitsComplete() {
        global $bid23,$database,$battle;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."attacks where ".TB_PREFIX."movement.ref = ".TB_PREFIX."attacks.id and ".TB_PREFIX."movement.proc = '0' and ".TB_PREFIX."movement.sort_type = '3' and ".TB_PREFIX."attacks.attack_type = '2' and endtime < $time";
        $dataarray = $database->query_return($q);
        
        foreach($dataarray as $data) {
            //set base things
            $owntribe = $database->getUserField($database->getVillageField($data['from'],"owner"),"tribe",0);
            $targettribe = $database->getUserField($database->getVillageField($data['to'],"owner"),"tribe",0);
            $to = $database->getMInfo($data['to']);
            $from = $database->getMInfo($data['from']);
            $toF = $database->getVillage($data['to']);
            $fromF = $database->getVillage($data['from']);
			$HeroTransfer = 0;

			//check to see if we're only sending a hero between own villages and there's a Mansion at target village
			if($data['t11'] != 0) {
				if($database->getVillageField($data['from'],"owner") == $database->getVillageField($data['to'],"owner")) {
					$check = $database->getEnforce($data['to'],$data['from']);
					//don't reinforce, addunit instead
					$database->modifyUnit($data['to'],'hero',1,1);
					$database->modifyEnforce($check['id'],'hero',1,1);
					$database->modifyHero2('wref', $data['to'], $database->getVillageField($data['from'],"owner"), 0);
					$HeroTransfer = 1;
				}else{
					$check = $database->getEnforce($data['to'],$data['from']);
					if($database->checkEnforce($data['to'],$data['from'])!=0){
						$database->modifyEnforce($check['id'],'hero',1,1);
					}else{
						$database->addHeroEnforce($data);
					}
					$HeroTransfer = 1;
				}
			}
			if(!$HeroTransfer) {	
	            //check if there is defence from town in to town
		        $check = $database->getEnforce($data['to'],$data['from']);
			    if (!isset($check['id'])){
				    //no: 
					$database->addEnforce($data);
				} else{
				 //yes
					 $start = ($owntribe-1)*10+1;
					 $end = ($owntribe*10);
				 //add unit.
					 $j='1';
					 for($i=$start;$i<=$end;$i++){
				        $database->modifyEnforce($check['id'],$i,$data['t'.$j.''],1); $j++;
					}
				}	
            }
            //send rapport
            $unitssend_att = ''.$data['t1'].','.$data['t2'].','.$data['t3'].','.$data['t4'].','.$data['t5'].','.$data['t6'].','.$data['t7'].','.$data['t8'].','.$data['t9'].','.$data['t10'].','.$data['t11'].'';
            $data_fail = ''.$from['wref'].','.$from['owner'].','.$owntribe.','.$unitssend_att.','.$to['wref'].','.$to['owner'].'';
			$fromAlly = $database->getUserField($from['owner'],'alliance',0);
            $database->addNotice($from['owner'],$to['wref'],$fromAlly,8,''.addslashes($from['name']).' Falu '.addslashes($to['name']).' Egységek küldése',$data_fail,$AttackArrivalTime);
			if($from['owner'] != $to['owner']) {
				$toAlly = $database->getUserField($from['owner'],'alliance',0);
				$database->addNotice($to['owner'],$to['wref'],$toAlly,8,''.addslashes($from['name']).' Falu '.addslashes($to['name']).' Egységek küldése',$data_fail,$AttackArrivalTime);
			}
            //update status
            $database->setMovementProc($data['moveid']); 

        }
		if(file_exists("GameEngine/Prevention/sendreinfunits.txt")) {
                @unlink("GameEngine/Prevention/sendreinfunits.txt");
            }
    }
    
    private function returnunitsComplete() {
        global $database;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."attacks where ".TB_PREFIX."movement.ref = ".TB_PREFIX."attacks.id and ".TB_PREFIX."movement.proc = '0' and ".TB_PREFIX."movement.sort_type = '4' and endtime < $time";
        $dataarray = $database->query_return($q);
        
        foreach($dataarray as $data) {
        
        $tribe = $database->getUserField($database->getVillageField($data['to'],"owner"),"tribe",0);
        
        if($tribe == 1){ $u = ""; } elseif($tribe == 2){ $u = "1"; } elseif($tribe == 3){ $u = "2"; } elseif($tribe == 4){ $u = "3"; } else{ $u = "4"; }
        $database->modifyUnit($data['to'],$u."1",$data['t1'],1);
        $database->modifyUnit($data['to'],$u."2",$data['t2'],1);
        $database->modifyUnit($data['to'],$u."3",$data['t3'],1);
        $database->modifyUnit($data['to'],$u."4",$data['t4'],1);
        $database->modifyUnit($data['to'],$u."5",$data['t5'],1);
        $database->modifyUnit($data['to'],$u."6",$data['t6'],1);
        $database->modifyUnit($data['to'],$u."7",$data['t7'],1);
        $database->modifyUnit($data['to'],$u."8",$data['t8'],1);
        $database->modifyUnit($data['to'],$u."9",$data['t9'],1);
        $database->modifyUnit($data['to'],$tribe."0",$data['t10'],1);
        $database->modifyUnit($data['to'],"hero",$data['t11'],1); 
        $database->setMovementProc($data['moveid']);
        }
        
        // Recieve the bounty on type 6.
        
        $q = "SELECT * FROM ".TB_PREFIX."movement, ".TB_PREFIX."send where ".TB_PREFIX."movement.ref = ".TB_PREFIX."send.id and ".TB_PREFIX."movement.proc = 0 and sort_type = 6 and endtime < $time";
        $dataarray = $database->query_return($q);
        foreach($dataarray as $data) {
            
            if($data['wood'] >= $data['clay'] && $data['wood'] >= $data['iron'] && $data['wood'] >= $data['crop']){ $sort_type = "10"; }
            elseif($data['clay'] >= $data['wood'] && $data['clay'] >= $data['iron'] && $data['clay'] >= $data['crop']){ $sort_type = "11"; }
            elseif($data['iron'] >= $data['wood'] && $data['iron'] >= $data['clay'] && $data['iron'] >= $data['crop']){ $sort_type = "12"; }
            elseif($data['crop'] >= $data['wood'] && $data['crop'] >= $data['clay'] && $data['crop'] >= $data['iron']){ $sort_type = "13"; }

            $to = $database->getMInfo($data['to']);
            $from = $database->getMInfo($data['from']);
            $database->modifyResource($data['to'],$data['wood'],$data['clay'],$data['iron'],$data['crop'],1);
            //$database->updateVillage($data['to']);
            $database->setMovementProc($data['moveid']);
        }
        $this->pruneResource();
		if(file_exists("GameEngine/Prevention/returnunits.txt")) {
            @unlink("GameEngine/Prevention/returnunits.txt");
        }
    }           
    
    private function sendSettlersComplete() {
        global $database, $building;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement where proc = 0 and sort_type = 5 and endtime < $time";
        $dataarray = $database->query_return($q);
            foreach($dataarray as $data) {
                    $to = $database->getMInfo($data['from']);
                    $user = $database->getUserField($to['owner'],'username',0);
                    $taken = $database->getVillageState($data['to']);
                    if($taken['occupied'] == 0){
                        $database->setFieldTaken($data['to']);
                        $database->addVillage($data['to'],$to['owner'],$user,'0');
                        $database->addResourceFields($data['to'],$database->getVillageType($data['to']));
                        $database->addUnits($data['to']);
                        $database->addTech($data['to']);
                        $database->addABTech($data['to']);
                        $database->setMovementProc($data['moveid']);
                        
                        $exp1 = $database->getVillageField($data['from'],'exp1');
                        $exp2 = $database->getVillageField($data['from'],'exp2');
                        $exp3 = $database->getVillageField($data['from'],'exp3');
                        
                        if($exp1 == 0){
                            $exp = 'exp1';
                            $value = $data['to'];
                        }
                        elseif($exp2 == 0){
                            $exp = 'exp2';
                            $value = $data['to'];
                        }
                        else{
                            $exp = 'exp3';
                            $value = $data['to'];
                        }
                        $database->setVillageField($data['from'],$exp,$value);
                    }
                    else{
                        // here must come movement from returning settlers
                        $database->setMovementProc($data['moveid']);
                    }
            }
			if(file_exists("GameEngine/Prevention/settlers.txt")) {
                @unlink("GameEngine/Prevention/settlers.txt");
            }
    }
	
	private function sendAdventuresComplete() {
        global $database, $building, $session;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."movement where proc = 0 and sort_type = 9 and endtime <= $time";
		$dataarray = $database->query_return($q);
			foreach($dataarray as $data) {
				$from = $database->getMInfo($data['from']);
				$to = $database->getAInfo($data['to']);
                $owner = $database->getUserField($from['owner'],'username',0);
				$ally = $database->getUserField($from['owner'],'alliance',0);
				$tribe = $database->getUserField($from['owner'],'tribe',0);
				$ownerID = $from['owner'];
				$coor = $database->getCoor($data['to']);
				$getHero = $database->getHeroData($ownerID);
				$getAdv = $database->getAdventure($ownerID, $data['to']);
				$heroface = $database->HeroFace($ownerID);
				
				$btype = rand(0,15);
				
				if($btype==1){
					if($time >= (COMMENCE+604800)){
						$ntype = array(1=>1,2,4,5,7,8,10,11,13,14);
					}
					elseif($time >= (COMMENCE+1209600)){
						$ntype = array(1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
					}
					else{
						$ntype = array(1=>1,4,7,10,13);
					}
				}
				/*elseif($btype==2){
					if($time >= (COMMENCE+604800)){
						$ntype = array(1=>82,83,85,86,88,89,91,92);
					}
					elseif($time >= (COMMENCE+1209600)){
						$ntype = array(1=>82,83,84,85,86,87,88,89,90,91,92,93);
					}
					else{
						$ntype = array(1=>82,85,88,91);
					}
				}*/
				elseif($btype==3){
					if($time >= (COMMENCE+604800)){
						$ntype = array(1=>61,62,64,65,67,68,73,74,79,80);
					}
					elseif($time >= (COMMENCE+1209600)){
						
						$ntype = array(1=>61,62,63,64,65,66,67,68,69,73,74,75,76,77,78,79,80,81);
					}
					else{
						$ntype = array(1=>61,64,67,73,79);
					}
				}
				elseif($btype==4){
					if($time >= (COMMENCE+604800)){
						if($tribe==1){
							$ntype = array(1=>16,17,19,20,22,23,25,26,28,29);
						}elseif($tribe==2){
							$ntype = array(1=>46,47,49,50,52,53,55,56,58,59);
						}elseif($tribe==3){
							$ntype = array(1=>31,32,34,35,37,38,40,41,43,44);
						}
					}
					elseif($time >= (COMMENCE+1209600)){
						if($tribe==1){
							$ntype = array(1=>16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
						}elseif($tribe==2){
							$ntype = array(1=>46,47,48,49,50,51,52,53,54,55,56,57,58,59,60);
						}elseif($tribe==3){
							$ntype = array(1=>31,32,33,34,35,36,37,38,39,40,41,42,43,44,45);
						}
					}
					else{
						if($tribe==1){
							$ntype = array(1=>16,19,22,25,28);
						}elseif($tribe==2){
							$ntype = array(1=>46,49,52,55,58);
						}elseif($tribe==3){
							$ntype = array(1=>31,34,37,40,43);
						}
					}
				}
				elseif($btype==5){
					if($time >= (COMMENCE+604800)){
						$ntype = array(1=>94,95,97,98,100,101);
					}
					elseif($time >= (COMMENCE+1209600)){
						$ntype = array(1=>94,95,96,98,99,100,101,102);
					}
					else{
						$ntype = array(1=>94,97,100);
					}
				}
				elseif($btype==6){
					if($time >= (COMMENCE+604800)){
						$ntype = array(1=>103,104);
					}
					elseif($time >= (COMMENCE+1209600)){
						$ntype = array(1=>103,104,105);
					}
					else{
						$ntype = array(1=>103);
					}
				}
				elseif($btype>=7){
					$ntype = array(7=>112,113,114,107,106,108,110,109,111);
				}
				if($getAdv['dif']==0){
					$exp = rand(0,40);
					$sgh = 1000;
				}else{
					$exp = rand(10,80);
					$sgh = 2000;
				}
				$health = round((3.007 / $getHero['power']) * $sgh,1);
				
				$database->modifyHero2('experience', $exp, $ownerID, 1);
				$database->setMovementProc($data['moveid']);
				$database->editTableField('adventure', 'end', 1, 'wref', $data['to']);
				
				if(($getHero['health']-$health)<=0){
					$database->modifyHero2('dead', 1, $ownerID, 0);
					$database->modifyHero2('health', $health, $ownerID, 2);
					$database->addNotice($ownerID,$data['to'],$ally,9,''.addslashes($from['name']).' felfedezi: ('.addslashes($coor['x']).'|'.addslashes($coor['y']).')',''.$from['wref'].',dead,A hős kalandozás közben meghalt.,,'.$health.','.$exp.'',$data['endtime']);
				}else{
					if($btype>=7){
						$nntype = $ntype[$btype];
						if($btype==9){
							$num = rand(6,20);
						}elseif($btype==12 or $btype==13 or $btype==15){
							$num = 1;
						}else{
							$num = rand(20,50);
						}
						if($btype<=11 or $btype>=14){
							if($database->checkHeroItem($ownerID, $btype)){
								$id = $database->getHeroItemID($ownerID, $btype);
								$database->editHeroNum($id, $num, 1);
							}else{
								$database->addHeroItem($ownerID, $btype, $nntype, $num);
							}
						}else{
							$database->addHeroItem($ownerID, $btype, $nntype, $num);
						}
					}else{
						if($btype==1 or $btype>2){
							$num = 1;
							$s2 = rand(1, count($ntype));
							$nntype = $ntype[$s2];
							$database->addHeroItem($ownerID, $btype, $nntype, $num);
						}
					}
					if($btype==0 or $btype==2){
						$database->addNotice($ownerID,$data['to'],$ally,9,''.addslashes($from['name']).' felfedezi: ('.addslashes($coor['x']).'|'.addslashes($coor['y']).')',''.$from['wref'].',,A hős nem talált semmi értékeset,,'.$health.','.$exp.'',$data['endtime']);
					}else{
						$database->addNotice($ownerID,$data['to'],$ally,9,''.addslashes($from['name']).' felfedezi: ('.addslashes($coor['x']).'|'.addslashes($coor['y']).')',''.$from['wref'].','.$btype.','.$nntype.','.$num.','.$health.','.$exp.'',$data['endtime']);
					}
					$database->modifyHero2('health', $health, $ownerID, 2);
					$ref = $database->addAttack($from['wref'],0,0,0,0,0,0,0,0,0,0,1,3,0,0,0);
					$AttackArrivalTime = $data['endtime']; 
					$speeds = array();
					$speeds[] = $getHero['speed'];
					$endtime = $this->procDistanceTime($from,$to,min($speeds),1) + $AttackArrivalTime;
					$database->addMovement(4,$data['to'],$data['from'],$ref,'0,0,0,0,0',$endtime);
				}
			}
			$q2 = "SELECT * FROM ".TB_PREFIX."adventure where time <= $time";
			$dataarray2 = $database->query_return($q2);
			foreach($dataarray2 as $data2) {
				$database->editTableField('adventure', 'end', 1, 'id', $data2['id']);
			}
			
			if(file_exists("GameEngine/Prevention/adventures.txt")) {
                @unlink("GameEngine/Prevention/adventures.txt");
            }
    }
    
    private function researchComplete() {
        global $database;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."research where timestamp < $time";
        $dataarray = $database->query_return($q);
        foreach($dataarray as $data) {
            $sort_type = substr($data['tech'],0,1);
            switch($sort_type) {
                case "t":
                $q = "UPDATE ".TB_PREFIX."tdata set ".$data['tech']." = 1 where vref = ".$data['vref'];
                break;
                case "a":
                case "b":
                $q = "UPDATE ".TB_PREFIX."abdata set ".$data['tech']." = ".$data['tech']." + 1 where vref = ".$data['vref'];
                break;
            }
            $database->query($q);
            $q = "DELETE FROM ".TB_PREFIX."research where id = ".$data['id'];
            $database->query($q);
        }
		if(file_exists("GameEngine/Prevention/research.txt")) {
            @unlink("GameEngine/Prevention/research.txt");
        }
    }
    
    private function updateRes($bountywid,$uid) {
        global $session;
        $this->bountyLoadTown($bountywid);
        $this->bountycalculateProduction($bountywid,$uid);
        $this->bountyprocessProduction($bountywid);
    }
    
    private function updateORes($bountywid) {
        global $session;
        $this->bountyLoadOTown($bountywid);
        $this->bountycalculateOProduction($bountywid);
        $this->bountyprocessOProduction($bountywid);
    }
    private function bountyLoadOTown($bountywid) {
        global $database,$session,$logging,$technology;
        $this->bountyOinfoarray = $database->getOasisV($bountywid);
        $this->bountyOresarray = $database->getResourceLevel($bountywid);
        $this->bountyOpop = 2;
        
    }
    private function bountyLoadTown($bountywid) {
        global $database,$session,$logging,$technology;
        $this->bountyinfoarray = $database->getVillage($bountywid);
        $this->bountyresarray = $database->getResourceLevel($bountywid);
        $this->bountyoasisowned = $database->getOasis($bountywid);
        $this->bountyocounter = $this->bountysortOasis();
        $this->bountypop = $this->bountyinfoarray['pop'];
    
        //$unitarray = $database->getUnit($bountywid);
        //if(count($unitarray) > 0) {
        //    for($i=1;$i<=50;$i++) {
        //        $this->bountyunitall['u'.$i] = $unitarray['u'.$i];
        //    }
        //}
        //$enforcearray = $database->getEnforceVillage($bountywid,0);
        //if(count($enforcearray) > 0) {
        //    foreach($enforcearray as $enforce) {
        //        for($i=1;$i<=50;$i++) {
        //            $this->bountyunitall['u'.$i] += $enforce['u'.$i];
        //        }
        //    }
        //}
    }
    
    private function bountysortOasis() {
        $crop = $clay = $wood = $iron = 0;
        foreach ($this->bountyoasisowned as $oasis) {
        switch($oasis['type']) {
                case 1:
				$wood += 1;
				break;
                case 2:
                $wood += 2;
                break;
                case 3:
                $wood += 1;
                $crop += 1;
                break;
                case 4:
				$clay += 1;
				break;
                case 5:
                $clay += 2;
                break;
                case 6:
                $clay += 1;
                $crop += 1;
                break;
                case 7:
				$iron += 1;
				break;
                case 8:
                $iron += 2;
                break;
                case 9:
                $iron += 1;
                $crop += 1;
                break;
                case 10:
                case 11:
                $crop += 1;
                break;
                case 12:
                $crop += 2;
                break;
            }
        }
        return array($wood,$clay,$iron,$crop);
    }
    
    function getAllUnits($base) {
        global $database;
        $ownunit = $database->getUnit($base);
        $enforcementarray = $database->getEnforceVillage($base,0);
        if(count($enforcementarray) > 0) {
            foreach($enforcementarray as $enforce) {
                for($i=1;$i<=50;$i++) {
                    $ownunit['u'.$i] += $enforce['u'.$i];
                }
					$ownunit['hero'] += $enforce['hero'];
            }
        }
        $movement = $database->getVillageMovement($base);
        if(!empty($movement)) {
            for($i=1;$i<=50;$i++) {
                $ownunit['u'.$i] += $movement['u'.$i];
            }
				$ownunit['hero'] += $movement['hero'];
        }
        return $ownunit;
    }    
    
    public function getUpkeep($array,$type) {
        $upkeep = 0;
        switch($type) {
            case 0:
            $start = 1;
            $end = 50;
            break;
            case 1:
            $start = 1;
            $end = 10;
            break;
            case 2:
            $start = 11;
            $end = 20;
            break;
            case 3:
            $start = 21;
            $end = 30;
            break;
            case 4:
            $start = 31;
            $end = 40;
            break;
            case 5:
            $start = 41;
            $end = 50;
            break;
        }    
        for($i=$start;$i<=$end;$i++) {
            $unit = "u".$i;
            global $$unit;
            $dataarray = $$unit;
            $upkeep += $dataarray['pop'] * $array[$unit];
        }
		
        return $upkeep;
    }
	
	private function bountycalculateOProduction($bountywid) { 
        global $technology,$database;
        $this->bountyOproduction['wood'] = $this->bountyGetOWoodProd();
        $this->bountyOproduction['clay'] = $this->bountyGetOClayProd();
        $this->bountyOproduction['iron'] = $this->bountyGetOIronProd();
        $this->bountyOproduction['crop'] = $this->bountyGetOCropProd();
    }
    private function bountycalculateProduction($bountywid,$uid) { 
        global $technology,$database,$village,$session;
        $normalA = $database->getOwnArtefactInfoByType($bountywid,4);  
        $largeA = $database->getOwnUniqueArtefactInfo($uid,4,2);
        $uniqueA = $database->getOwnUniqueArtefactInfo($uid,4,3);
        $upkeep = $this->getUpkeep($this->getAllUnits($bountywid),0);
		$q = "SELECT * FROM ".TB_PREFIX."hero where uid = $session->uid";
        $heroData = $database->query_return($q);
		
		if($heroData['dead']==0 && $village->capital){
			$hwood = $heroData['r1'];
			$hclay = $heroData['r2'];
			$hiron = $heroData['r3'];
			$hcrop = $heroData['r4'];
			$hproduct = $heroData['r0'];
		}
		
        $this->bountyproduction['wood'] = $this->bountyGetWoodProd()+$hwood+$hproduct;
		$this->bountyproduction['clay'] = $this->bountyGetClayProd()+$hclay+$hproduct;
		$this->bountyproduction['iron'] = $this->bountyGetIronProd()+$hiron+$hproduct;
				
        if ($uniqueA['size']==3 && $uniqueA['owner']==$uid){
        $this->bountyproduction['crop'] = $this->bountyGetCropProd()-$this->bountypop-(($upkeep)-round($upkeep*0.50))+$hcrop+$hproduct;  
        
        }else if ($normalA['type']==4 && $normalA['size']==1 && $normalA['owner']==$uid){
        $this->bountyproduction['crop'] = $this->bountyGetCropProd()-$this->bountypop-(($upkeep)-round($upkeep*0.25))+$hcrop+$hproduct;
        
        }else if ($largeA['size']==2 && $largeA['owner']==$uid){
         $this->bountyproduction['crop'] = $this->bountyGetCropProd()-$this->bountypop-(($upkeep)-round($upkeep*0.25))+$hcrop+$hproduct;   
       
        }else{
        $this->bountyproduction['crop'] = $this->bountyGetCropProd()-$this->bountypop-$upkeep+$hcrop+$hproduct;   
    }
        }
    
    private function bountyprocessProduction($bountywid) {
        global $database;
        $timepast = time() - $this->bountyinfoarray['lastupdate'];
        $nwood = ($this->bountyproduction['wood'] / 3600) * $timepast;
        $nclay = ($this->bountyproduction['clay'] / 3600) * $timepast;
        $niron = ($this->bountyproduction['iron'] / 3600) * $timepast;
        $ncrop = ($this->bountyproduction['crop'] / 3600) * $timepast;
        $database->modifyResource($bountywid,$nwood,$nclay,$niron,$ncrop,1);
        $database->updateVillage($bountywid);
    }
    private function bountyprocessOProduction($bountywid) {
        global $database;
        $timepast = time() - $this->bountyOinfoarray['lastupdated'];
        $nwood = ($this->bountyOproduction['wood'] / 3600) * $timepast;
        $nclay = ($this->bountyOproduction['clay'] / 3600) * $timepast;
        $niron = ($this->bountyOproduction['iron'] / 3600) * $timepast;
        $ncrop = ($this->bountyOproduction['crop'] / 3600) * $timepast;
        $database->modifyOasisResource($bountywid,$nwood,$nclay,$niron,$ncrop,1);
        $database->updateOasis($bountywid);
    }
    
    private function bountyGetWoodProd() {
        global $bid1,$bid5,$session;
        $wood = $sawmill = 0;
        $woodholder = array();
        for($i=1;$i<=38;$i++) {
            if($this->bountyresarray['f'.$i.'t'] == 1) {
                array_push($woodholder,'f'.$i);
            }
            if($this->bountyresarray['f'.$i.'t'] == 5) {
                $sawmill = $this->bountyresarray['f'.$i];
            }
        }
        for($i=0;$i<=count($woodholder)-1;$i++) { $wood+= $bid1[$this->bountyresarray[$woodholder[$i]]]['prod']; }
        if($sawmill >= 1) {
            $wood += $wood /100 * $bid5[$sawmill]['attri'];
        }
        if($this->bountyocounter[0] != 0) {
            $wood += $wood*0.25*$this->bountyocounter[0];
        }
//        $wood += $wood*$this->bountyocounter[0]*0.25;
        $wood *= SPEED;
        return round($wood);
    }
	
    private function bountyGetOWoodProd() {
        global $session;
        $wood = 0;
        $wood += 40;
        $wood *= SPEED;
        return round($wood);
    }
    private function bountyGetOClayProd() {
        global $session;
        $clay = 0;
        $clay += 40;
        $clay *= SPEED;
        return round($clay);
    }
	private function bountyGetOIronProd() {
        global $session;
        $iron = 0;
        $iron += 40; 
        $iron *= SPEED;
        return round($iron);
    }
    
    private function bountyGetOCropProd() {
        global $session;
        $crop = 0;
        $crop += 40;
        $crop *= SPEED;
        return round($crop);
    }
    private function bountyGetClayProd() {
        global $bid2,$bid6,$session;
        $clay = $brick = 0;
        $clayholder = array();
        for($i=1;$i<=38;$i++) {
            if($this->bountyresarray['f'.$i.'t'] == 2) {
                array_push($clayholder,'f'.$i);
            }
            if($this->bountyresarray['f'.$i.'t'] == 6) {
                $brick = $this->bountyresarray['f'.$i];
            }
        }
        for($i=0;$i<=count($clayholder)-1;$i++) { $clay+= $bid2[$this->bountyresarray[$clayholder[$i]]]['prod']; }
        if($brick >= 1) {
            $clay += $clay /100 * $bid6[$brick]['attri'];
        }
        if($this->bountyocounter[1] != 0) {
            $clay += $clay*0.25*$this->bountyocounter[1];
        }
//        $clay += $clay*$this->bountyocounter[1]*0.25;
        $clay *= SPEED;
        return round($clay);
    }
    
    private function bountyGetIronProd() {
        global $bid3,$bid7,$session;
        $iron = $foundry = 0;
        $ironholder = array();
        for($i=1;$i<=38;$i++) {
            if($this->bountyresarray['f'.$i.'t'] == 3) {
                array_push($ironholder,'f'.$i);
            }
            if($this->bountyresarray['f'.$i.'t'] == 7) {
                $foundry = $this->bountyresarray['f'.$i];
            }
        }
        for($i=0;$i<=count($ironholder)-1;$i++) { $iron+= $bid3[$this->bountyresarray[$ironholder[$i]]]['prod']; }
        if($foundry >= 1) {
            $iron += $iron /100 * $bid7[$foundry]['attri'];
        }
        if($this->bountyocounter[2] != 0) {
            $iron += $iron*0.25*$this->bountyocounter[2];
        }
//        $iron += $iron*$this->bountyocounter[2]*0.25;
        $iron *= SPEED;
        return round($iron);
    }
    
    private function bountyGetCropProd() {
        global $bid4,$bid8,$bid9,$session;
        $crop = $grainmill = $bakery = 0;
        $cropholder = array();
        for($i=1;$i<=38;$i++) {
            if($this->bountyresarray['f'.$i.'t'] == 4) {
                array_push($cropholder,'f'.$i);
            }
            if($this->bountyresarray['f'.$i.'t'] == 8) {
                $grainmill = $this->bountyresarray['f'.$i];
            }
            if($this->bountyresarray['f'.$i.'t'] == 9) {
                $bakery = $this->bountyresarray['f'.$i];
            }
        }
        for($i=0;$i<=count($cropholder)-1;$i++) { $crop+= $bid4[$this->bountyresarray[$cropholder[$i]]]['prod']; }
        if($grainmill >= 1) {
            $crop += $crop /100 * $bid8[$grainmill]['attri'];
        }
        if($bakery >= 1) {
            $crop += $crop /100 * $bid9[$bakery]['attri'];
        }
        if($this->bountyocounter[3] != 0) {
            $crop += $crop*0.25*$this->bountyocounter[3];
        }
        
//        $crop += $crop*$this->bountyocounter[3]*0.25;
        $crop *= SPEED;
        return round($crop);
    }

    private function trainingComplete() {
        global $database;
        $trainlist = $database->getTrainingList();
        if(count($trainlist) > 0) {
            foreach($trainlist as $train) {
                $database->updateTraining($train['id'],0);
                $trained = 0;
                if($train['eachtime'] == 0) { $train['eachtime'] = 1; }
                $timepast = $train['timestamp'] - $train['commence'];
                if ($timepast >= 0) {
                    $trained = floor($timepast/$train['eachtime']);
                    $pop = $train['pop'] * $trained;
                    if($trained >= $train['amt']) {
                        $trained = $train['amt'];
                    }
                    $database->modifyUnit($train['vref'],($train['unit']>60?$train['unit']-60:$train['unit']),$trained,1);
                    if($train['amt']-$trained <= 0) {
                        $database->trainUnit($train['id'],0,0,0,0,1,1);
                    }
                    if($trained > 0) {
                        $database->modifyCommence($train['id']);
                    }
                    $database->updateTraining($train['id'],$trained);
                }
            }
        }
		if(file_exists("GameEngine/Prevention/training.txt")) {
            @unlink("GameEngine/Prevention/training.txt");
        }
    }
    
    private function procDistanceTime($coor,$thiscoor,$ref,$mode) {
        global $bid14,$database,$generator;
        $resarray = $database->getResourceLevel($generator->getBaseID($coor['x'],$coor['y']));
        $xdistance = ABS($thiscoor['x'] - $coor['x']);
        if($xdistance > WORLD_MAX) {
            $xdistance = (2*WORLD_MAX+1) - $xdistance;
        }
        $ydistance = ABS($thiscoor['y'] - $coor['y']);
        if($ydistance > WORLD_MAX) {
            $ydistance = (2*WORLD_MAX+1) - $ydistance;
        }
        $distance = SQRT(POW($xdistance,2)+POW($ydistance,2));
        if(!$mode) {
        	if($ref == 1) {
            	$speed = 16;
        	}
        	else if($ref == 2) {
                $speed = 12;
            }
            else if($ref == 3) {
                $speed = 24;
            }
            else if($ref == 300) {
                $speed = 5;
            }
            else {
                $speed = 1;
            }
        }
        else {
            $speed = $ref;
            if($this->getsort_typeLevel(14,$resarray) != 0) {
                $speed = $distance <= TS_THRESHOLD ? $speed : $speed * ( ( TS_THRESHOLD + ( $distance - TS_THRESHOLD ) * $bid14[$this->getsort_typeLevel(14,$resarray)]['attri'] / 100 ) / $distance ) ;
            }
        }
        
        return round(($distance/$speed) * 3600 / INCREASE_SPEED);

    }
    
    private function getsort_typeLevel($tid,$resarray) {
        global $village;
        $keyholder = array();
        foreach(array_keys($resarray,$tid) as $key) {
            if(strpos($key,'t')) { 
                $key = preg_replace("/[^0-9]/", '', $key);
                array_push($keyholder, $key);
            } 
        }
        $element = count($keyholder);
        if($element >= 2) {
            if($tid <= 4) {
                $temparray = array();
                for($i=0;$i<=$element-1;$i++) {
                    array_push($temparray,$resarray['f'.$keyholder[$i]]);
                }
                foreach ($temparray as $key => $val) {
                    if ($val == max($temparray)) 
                    $target = $key; 
                }
            }
            else {
                for($i=0;$i<=$element-1;$i++) {
                    //if($resarray['f'.$keyholder[$i]] != $this->getsort_typeMaxLevel($tid)) {
                    //    $target = $i;
                    //}
                }
            }
        }
        else if($element == 1) {
            $target = 0;
        }
        else {
            return 0;
        }
        if($keyholder[$target] != "") {
            return $resarray['f'.$keyholder[$target]];
        }
        else {
            return 0;
        }
    }
    
    private function celebrationComplete() {
        global $database;
        $varray = $database->getCel(); 
            foreach($varray as $vil){
                $id = $vil['wref'];
                $type = $vil['type'];
                $user = $vil['owner'];
                if($type == 1){$cp = 500;}else if($type == 2){$cp = 2000;}
                $database->clearCel($id);
                $database->setCelCp($user,$cp);
            }
		if(file_exists("GameEngine/Prevention/celebration.txt")) {
            @unlink("GameEngine/Prevention/celebration.txt");
        }
    }
    
    private function demolitionComplete() {
        global $building,$database;
        $varray = $database->getDemolition();
        foreach($varray as $vil) {
            if ($vil['timetofinish'] <= time()) {
                $type = $database->getFieldType($vil['vref'],$vil['buildnumber']);
                $level = $database->getFieldLevel($vil['vref'],$vil['buildnumber']);
                $buildarray = $GLOBALS["bid".$type];
                if ($type==10 || $type==38) {
                    $q = "UPDATE `".TB_PREFIX."vdata` SET `maxstore`=`maxstore`-".$buildarray[$level]['attri']."+".max(0,$buildarray[$level-1]['attri'])." WHERE wref=".$vil['vref'];
                    $database->query($q);
                    $q = "UPDATE ".TB_PREFIX."vdata SET `maxstore`=".STORAGE_BASE." WHERE `maxstore`<= ".STORAGE_BASE." AND wref=".$vil['vref'];
                    $database->query($q);
                }
                if ($type==11 || $type==39) {
                    $q = "UPDATE `".TB_PREFIX."vdata` SET `maxcrop`=`maxcrop`-".$buildarray[$level]['attri']."+".max(0,$buildarray[$level-1]['attri'])." WHERE wref=".$vil['vref'];
                    $database->query($q);
                    $q = "UPDATE ".TB_PREFIX."vdata SET `maxcrop`=".STORAGE_BASE." WHERE `maxcrop`<=".STORAGE_BASE." AND wref=".$vil['vref'];
                    $database->query($q);
                }
                if ($level==1) { $clear=",f".$vil['buildnumber']."t=0"; } else { $clear=""; }
                $q = "UPDATE ".TB_PREFIX."fdata SET f".$vil['buildnumber']."=".($level-1).$clear." WHERE vref=".$vil['vref'];
                $database->query($q);
                $pop=$this->getPop($type,$level-1);
                $database->modifyPop($vil['vref'],$pop[0],1);
                $database->delDemolition($vil['vref']);
            }
        }
		if(file_exists("GameEngine/Prevention/demolition.txt")) {
            @unlink("GameEngine/Prevention/demolition.txt");
        }
    }
	
	private function updateHero(){
		global $database,$session;
		$time = time();
		$q = "SELECT * FROM ".TB_PREFIX."hero where dead = 0";
		$harray = $database->query_return($q);
		if(!empty($harray)) {
	        foreach($harray as $hdata) {
				if($hdata['health']<100) {
					if(($time-$hdata['lastupdate'])>=3600) {
						$health = round(($hdata['autoregen']/24),1);
						$database->modifyHero("health",$health,$hdata['heroid'],1);
						$database->modifyHero("lastupdate",time(),$hdata['heroid'],0);
					}
				}
				if($hdata['lastadv']!=0){
					if(($time-$hdata['lastadv'])>=rand(7200,14400)) {
						$exprand = rand(2,10);
						$database->modifyHero("experience",$exprand,$hdata['heroid'],1);
						$database->addAdventure($hdata['wref'], $hdata['uid']);
						$database->modifyHero("lastadv",time(),$hdata['heroid'],0);
					}
				}
				$hero_levels = $GLOBALS["hero_levels"];
				if($hdata['experience']>=$hero_levels[$hdata['level']+1]){
					$database->modifyHero("level",1,$hdata['heroid'],1);
					$database->modifyHero("points",4,$hdata['heroid'],1);
				}
			}
		}
		$q2 = "SELECT * FROM ".TB_PREFIX."training where unit = 0";
		$dataarray2 = $database->query_return($q2);
		foreach($dataarray2 as $data3) {
			if($data3['eachtime']<=time()){
				$database->trainHero($data3['id'],0,1);
				$getVil = $database->getMInfo($data3['vref']);
				$database->modifyHero2('dead', 0, $getVil['owner'], 0);
    			$database->modifyHero2('health', 100, $getVil['owner'], 0);
    			$database->editTableField('units', 'hero', 1, 'vref', $data3['vref']);
			}
		}
		$q2 = "SELECT * FROM ".TB_PREFIX."units where hero > 1";
		$dataarray2 = $database->query_return($q2);
		foreach($dataarray2 as $data3) {
    		$database->editTableField('units', 'hero', 1, 'vref', $data3['vref']);
		}
		if(file_exists("GameEngine/Prevention/updatehero.txt")) {
			@unlink("GameEngine/Prevention/updatehero.txt");
		}
				
	}
	
	private function auctionComplete() {
		global $database;
        $time = time();
        $q = "SELECT * FROM ".TB_PREFIX."auction where finish = 0 and time < $time";
        $dataarray = $database->query_return($q);
            foreach($dataarray as $data) {
					$ownerID = $data['owner'];$biderID = $data['uid'];
					$silver = $data['silver'];$btype = $data['btype'];
                    if($data['finish'] != 1){
						if($btype==7 || $btype==8 || $btype==9 || $btype==10 || $btype==11 || $btype==14 || $btype==15){
							if($database->checkHeroItem($biderID, $btype)){
								$database->editHeroNum($database->getHeroItemID($biderID, $btype), $data['num'], 1);
							}else{
								$database->addHeroItem($biderID, $data['btype'], $data['type'], $data['num']);
							}
						}else{
							$database->addHeroItem($biderID, $data['btype'], $data['type'], $data['num']);
						}
						$database->setSilver($ownerID, $silver, 1);
						//$database->setSilver($biderID, $silver, 0);
                    }
			$q = "UPDATE ".TB_PREFIX."auction set finish=1 where id = ".$data['id'];
			$database->query($q);
            }
		if(file_exists("GameEngine/Prevention/auction.txt")) {
        	@unlink("GameEngine/Prevention/auction.txt");
        }
    }
	
	private function starvation() {
		global $database,$technology;
		$TroopStarvesEvery = 100;
        $q = "SELECT * FROM ".TB_PREFIX."vdata WHERE crop<0";
        $array = $database->query_return($q);
		if(!empty($array)) { 
	        foreach($array as $village) {
				$TroopsStarved = floor($village['crop'] / $TroopStarvesEvery) + 1;
				$ownunit = $database->getUnit($base);
				$TopUnit = $TopUnitCount = 0;
				for($i=1;$i<=50;$i++) {
					if($ownunit['u'.$i] > $TopUnitCount) { $TopUnit = $i; $TopUnitCount = $ownunit['u'.$i]; } 
				}
				if($TopUnitCount > 0) {
					// Remove TroopsStarved from TopUnit
				} else {
					// Repeat check for reinforcements
				}
				$q = "UPDATE ".TB_PREFIX."vdata set `crop` = 40 WHERE wref=".$village['wref'];
				$database->query($q);
			}
		}

		if(file_exists("GameEngine/Prevention/starvation.txt")) {
            @unlink("GameEngine/Prevention/starvation.txt");
        }
	}
}

$automation = new Automation;
?>