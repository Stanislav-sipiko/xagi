<?php

class XAGI {

var $stin=NULL;
var $stout=NULL;
var $stlog=NULL;
var $agivar=NULL;

function XAGI($log_file=NULL) {
    $this->stin=fopen('php://stdin','r');
    $this->stout=fopen('php://stdout','w');
    if ($log_file) {
		$this->stlog=fopen($log_file,'a');
    }
    $this->agivar = array();
    while (!feof($this->stin)) {
		$temp = fgets($this->stin);
		$temp = str_replace("\n","",$temp);
		if ($temp=="") {
			break;
		}
		$s=explode(": ",$temp);
		$this->agivar[$s[0]]=trim($s[1]);    
    }
    $this->debug("ok");
    
}    


function astRead() {
    return str_replace("\n","",fgets($this->stin,4096));
}

function astWrite($cmd) {
    fwrite($this->stout,"$cmd"."\n");
    fflush($this->stout);
}

function cmd($cmd) {
    $this->astWrite($cmd);
    return $this->astRead();
}

function debug($str) {
    if ($this->strlog) {
		fwrite($this->stlog,date("[H:i:s]",time())."$str\n");
    }
} 

function close() {
    fclose($this->stin);
    fclose($this->stout);
    if ($this->strlog) {
		fclose($this->stlog);
    }
}


function getUID() {
    return $this->agivar["agi_uniqueid"];
}
function getChannel() {
    return $this->agivar["agi_channel"];
}
function getExtension() {
    return $this->agivar["agi_extension"];
}
function getCallerId() {
    return $this->agivar["agi_callerid"];
}
function getCallerIdName() {
    return $this->agivar["agi_calleridname"];
}
function getCallerIdRDNIS() {
    return $this->agivar["agi_rdnis"];
}

function setVariable($name, $value) {
    $this->cmd("SET VARIABLE $name ".str_replace(" ", "\ ",$value));
}
function getVariable($name) {
    $resp = trim($this->cmd("GET VARIABLE $name"));
    $parts = explode(" ",$resp);
    if ($parts[1]=="result=1") {
		$val = explode("(",$resp);
		return substr($val[1],0,-1);
    }
    return NULL;
}

} //eof class



?>
