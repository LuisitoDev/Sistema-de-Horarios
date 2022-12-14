<?php
use App\Models\Dispositivo;
use App\Models\CicloEscolar;

if(!function_exists('get_client_mac')){
    function get_client_mac($ip)
    {
        $cmd = 'arp -a '. $ip;
        $status = 0;

        $return = [];

        exec($cmd, $return, $status);

        $macAddress = null;
        if($status === 0) {
            if(isset($return[3])){
                $macAddress = preg_split('/\s+/', $return[3], -1, PREG_SPLIT_NO_EMPTY);

                $macAddress = isset($macAddress[1]) ? $macAddress[1] : null;
            }

        }

        return $macAddress;
    }
}

if(!function_exists('start_mac_session')){
    function start_mac_session($mac, $request)
    {

    }
}
if(!function_exists('getUserByMacAddress')){
    function getUserByMacAddress($ip){

        $mac = get_client_mac($ip);

        if($mac == null)
            return null;

        $device = Dispositivo::join('usuarios', 'dispositivos.id_usuario', '=', 'usuarios.id')->where('dispositivos.direccion_mac', '=', $mac)->first();

        if($device){
            return $device->id_usuario;
        }else {
            return null;
        }

    }
}

if(!function_exists('getSchoolCycle')){
    function getSchoolCycle(){
        $Ciclo_Escolar = null;

        if(Session::has('Id_Ciclo_Escolar')){
            $Id_Ciclo_Escolar = Session::get('Id_Ciclo_Escolar');
            $Ciclo_Escolar = CicloEscolar::where('id', $Id_Ciclo_Escolar)->first();
        }
        else{
            $Ciclo_Escolar = CicloEscolar::orderBy('fecha_ingreso', 'DESC')->first();
        }

        return $Ciclo_Escolar;

    }
}
