<?php
	/**
     * save file
     * 
     * @param string $fileName filename(relative path)
     * @param string $text file content
     * @return boolean 
     */
    function saveFile($fileName, $text) {
        if (!$fileName || !$text)
            return false;

        if (makeDir(dirname($fileName))) {
            if ($fp = fopen($fileName, "w")) {
                if (@fwrite($fp, $text)) {
                    fclose($fp);
                    return true;
                } else {
                    fclose($fp);
                    return false;
                } 
            } 
        } 
        return false;
    } 

    /**
     * create directory
     * 
     * @param string $dir directory name
     * @param int $mode permission
     * @return boolean 
     */
    function makeDir($dir, $mode = "0777") {
        if (!dir) return false;

        if(!file_exists($dir)) {
            return mkdir($dir,$mode,true);
        } else {
            return true;
        }
        
    }
?>
