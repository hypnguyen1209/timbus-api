<?php
class Timbus {
    private function CURL($API, $param) {
        $ch = curl_init($API);
        $array = $param;
        curl_setopt_array($ch, array(
            CURLOPT_URL => $API,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3833.0 Safari/537.36',
            CURLOPT_POST => 1,
            CURLOPT_ENCODING => 1,
            CURLOPT_POSTFIELDS => $array,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Accept-Encoding: gzip, deflate',
                'Accept-Language: en,vi-VN;q=0.9,vi;q=0.8,fr-FR;q=0.7,fr;q=0.6,en-US;q=0.5',
                'Connection: keep-alive',
                'Content-Length: '.strlen($array),
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Host: timbus.vn',
                'Origin: http://timbus.vn',
                'Referer: http://timbus.vn/',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3833.0 Safari/537.36',
                'X-Requested-With: XMLHttpRequest'
            ), 
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true
        ));
        $re = curl_exec($ch);
        curl_close($ch);
        return $re;
    }
    public function ThongTinXe($id) {
        $API = "http://timbus.vn/Engine/Business/Search/action.ashx";
        $param = 'act=fleetdetail&fid='.$id;
        $re = CURL($API, $param);
        $Reponse = json_decode($re);
        $data = [
            'ID'=>$Reponse->dt->FleetID,
            'Enterprise'=>$Reponse->dt->Enterprise,
            'Xe'=>$Reponse->dt->Code,
            'Timing'=>$Reponse->dt->Frequency,
            'Cost'=>$Reponse->dt->Cost,
            'FirstStation'=>$Reponse->dt->FirstStation,
            'LastStation'=>$Reponse->dt->LastStation,
            'Go'=>$Reponse->dt->Go->Route,
            'Re'=>$Reponse->dt->Re->Route
            ];
        return json_encode($data);
    }
    public function Route($Lng, $Lat) {
        $API = "http://timbus.vn/Engine/Business/Route/action.ashx";
        $param = 'act=geo2add&lng='.$Lng.'&lat='.$Lat;
        $re = CURL($API, $param);
        $Reponse = json_decode($re);
        $add = $Reponse->dt->Address;
        $addr = explode(", ", $add);
        return json_encode(['Lng'=>$addr[0],'Lat'=>$addr[1]]);
    }
    public function  SearchBus($query) {
        $API = "http://timbus.vn/Engine/Business/Search/action.ashx";
        $param = 'act=searchfull&typ=1&key='.urlencode($query);
        $re = CURL($API, $param);
        $Reponse = json_decode($re);
        $Total= $Reponse->dt->Total;
        $data = [];
        $countdata = count($Reponse->dt->Data);
        for($i = 0; $i< $countdata; $i++) {
            $data[$i] = ['ID'=>$Reponse->dt->Data[$i]->ObjectID,'Name'=>$Reponse->dt->Data[$i]->Name,'Xe'=>$Reponse->dt->Data[$i]->FleedCode,'Info'=>$Reponse->dt->Data[$i]->Data];
        }
        $jsond = ['Total'=>$Total,'Data'=>$data];
        return json_encode($jsond);
    }
    public function  SearchBusStop($query) {
        $API = "http://timbus.vn/Engine/Business/Search/action.ashx";
        $param = 'act=searchfull&typ=2&key='.urlencode($query);
        $re = CURL($API,$param);
        $Reponse = json_decode($re);
        $Total= $Reponse->dt->Total;
        $data = [];
        
        $countdata = count($Reponse->dt->Data);
        for($i = 0; $i< $countdata; $i++) {
            $Lng = $Reponse->dt->Data[$i]->Geo->Lng;
            $Lat = $Reponse->dt->Data[$i]->Geo->Lat;
            $map = json_decode(Route($Lng, $Lat));
            $Lnge = $map['Lng'];
            $Late = $map['Lat'];
            $data[$i] = ['ID'=>$Reponse->dt->Data[$i]->ObjectID,'Street'=>$Reponse->dt->Data[$i]->Street,'Name'=>$Reponse->dt->Data[$i]->Name,'Xe'=>$Reponse->dt->Data[$i]->FleetOver,'map'=>'https://maps.googleapis.com/maps/api/staticmap?center='.$Late.','.$Lnge.'&markers=size:mid%7Ccolor:red%7C'.$Late.','.$Lnge.'&zoom=16&size=600x600&maptype=roadmap&key=AIzaSyBukG4uq4dqnkPkh-Xot4oHsVqEDZNQL4o'];
        }
        $jsond = ['Total'=>$Total,'Data'=>$data];
        return json_encode($jsond);
    }
    public function Timing($stateID) {
        $API = "http://timbus.vn/Engine/Business/Vehicle/action.ashx";
        $param = 'act=partremained&State=true&StationID='.$stateID.'&FleetOver=';
        $re = CURL($API, $param);
        $Reponse = json_decode($re);
        $Total= $Reponse->dt->Total;
        $data = [];
        $countdata = count($Reponse->dt);
        for($i = 0; $i< $countdata; $i++) {
            $data[$i] = ['Xe'=>$Reponse->dt[$i]->Fleet,'BienKiemSoat'=>$Reponse->dt[$i]->BienKiemSoat,'ChieuXe'=>$Reponse->dt[$i]->FleetCode,'KhoangCach'=>$Reponse->dt[$i]->PartRemained,'Timing'=>$Reponse->dt[$i]->TimeRemained];
        }
        $jsond = ['Data'=>$data];
        return json_encode($jsond);
    }

}
