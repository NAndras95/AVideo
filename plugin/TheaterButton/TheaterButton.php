<?php

global $global;
require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class TheaterButton extends PluginAbstract {

    public function getDescription() {
        return "Add next theater switch button to the control bar";
    }

    public function getName() {
        return "TheaterButton";
    }

    public function getUUID() {
        return "f7596843-51b1-47a0-8bb1-b4ad91f87d6b";
    }
    
    public function getPluginVersion() {
        return "1.1";   
    }

    public function getEmptyDataObject() {
        $obj = new stdClass();
        $obj->show_switch_button = true;
        $obj->compress_is_default = false;
        return $obj;
    }
    
    public function getHeadCode() {
        global $global;
        if (!$this->showButton()) {
            return "";
        }
        $tmp = "mainVideo";
        if(!empty($_SESSION['type'])){
            if(($_SESSION['type']=="audio")||($_SESSION['type']=="linkAudio")){
                $tmp = "mainAudio";
            }
        }
        $css = '<link href="' . $global['webSiteRootURL'] . 'plugin/TheaterButton/style.css" rel="stylesheet" type="text/css"/>';
        $css .= '<script>var videoJsId = "'.$tmp.'";</script>';
        return $css;
    }
    public function getJSFiles(){
        global $global, $autoPlayVideo, $isEmbed;
        if (!$this->showButton()) {
            return "";
        }
        $obj = $this->getDataObject();
        if(!empty($obj->show_switch_button)){
            return array("plugin/TheaterButton/script.js","plugin/TheaterButton/addButton.js");
        }
        return array("plugin/TheaterButton/script.js");
    }
    public function getFooterCode() {
        global $global, $autoPlayVideo, $isEmbed;
        if (!$this->showButton()) {
            return "";
        }
        $obj = $this->getDataObject();
        $js = '';
        if(empty($obj->show_switch_button)){
            if($obj->compress_is_default){
                $js .= '<script>$(document).ready(function () {if (typeof compress === "function" && videojs){compress(videojs);}});</script>';
            }else{
                $js .= '<script>$(document).ready(function () {if (typeof expand === "function" && videojs){expand(videojs);}});</script>';
            }
        }
        
        return $js;
    }
    
    private function showButton(){
        global $global, $isEmbed, $advancedCustom, $video;
        
        if (empty($_GET['videoName']) && empty($_GET['u']) && empty($_GET['link'])) {
            return false;
        }
        
        if(!empty($video) && $video['type']=='notfound'){
            return false;
        }
        
        if (isMobile()) {
            return false;
        }
        if(!empty($_GET['videoName'])){
            $videoT = Video::getVideoFromCleanTitle($_GET['videoName']);
            if(($isEmbed==1 || $videoT['type']=='embed') && $advancedCustom->disableYoutubePlayerIntegration){
                return false;
            }
            if($videoT['type']=='article' || $videoT['type']=='pdf' || $videoT['type']=='image' || $videoT['type']=='zip'){
                return false;
            }
        }
        return true;
    }
        
    public function getTags() {
        return array('free', 'buttons', 'video player');
    }



}
