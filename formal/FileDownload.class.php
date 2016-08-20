<?php 
/** php下载类,支持断点续传 
*  Date:  2013-06-30 
*  Author: test 
*  Ver:  1.0 
* 
*  Func: 
*  download: 下载文件 
*  setSpeed: 设置下载速度 
*  getRange: 获取header中Range 
*/
  
class FileDownload{ // class start 
  
  private $_speed = 512;  // 下载速度 
  
  /** 下载 
  * @param String $file  要下载的文件路径 
  * @param String $name  文件名称,为空则与下载的文件名称一样 
  * @param boolean $reload 是否开启断点续传 
  */
  public function download($file, $name='', $reload=false){
    //$file=iconv("gb2312", "utf-8", $file);
    if(file_exists($file) || $GLOBALS['file_exist_override']){ 
      if($name==''){ 
        $name = (basename($file)); 
      } 
      $fp = fopen($file, 'rb');
      

      $file_size = filesize($file); 
      $ranges = $this->getRange($file_size); 
      
      header('cache-control:public'); 
      header('content-type:application/octet-stream');
      //dev_echo($file);
      //dev_echo($name);
      header("content-disposition:attachment; filename=\"".$name."\"");
      if($reload && $ranges != null){ // 使用续传 
        header('HTTP/1.1 206 Partial Content'); 
        header('Accept-Ranges:bytes'); 
          
        // 剩余长度 
        header(sprintf('content-length:%u',$ranges['end']-$ranges['start'])); 
          
        // range信息 
        header(sprintf('content-range:bytes %s-%s/%s', $ranges['start'], $ranges['end'], $file_size)); 
          
        // fp指针跳到断点位置 
        fseek($fp, sprintf('%u', $ranges['start'])); 
      }else{ 
        header('HTTP/1.1 200 OK'); 
        header('content-length:'.$file_size); 
      } 
      while(!feof($fp)){
        echo fread($fp, round($this->_speed*1024,0)); 
        ob_flush(); 
        //sleep(1); // 用于测试,减慢下载速度 
      } 
      return ($fp!=null) && fclose($fp); 
  
    }else{ 
      return ''; 
    } 
  } 
  
  /** 设置下载速度 
  * @param int $speed 
  */
  public function setSpeed($speed){ 
    if(is_numeric($speed) && $speed>16 && $speed<4096){ 
      $this->_speed = $speed; 
    } 
  } 
  
  /** 获取header range信息 
  * @param int  $file_size 文件大小 
  * @return Array 
  */
  private function getRange($file_size){ 
    if(isset($_SERVER['HTTP_RANGE']) && !empty($_SERVER['HTTP_RANGE'])){ 
      $range = $_SERVER['HTTP_RANGE']; 
      $range = preg_replace('/[\s|,].*/', '', $range); 
      $range = explode('-', substr($range, 6)); 
      if(count($range)<2){ 
        $range[1] = $file_size; 
      } 
      $range = array_combine(array('start','end'), $range); 
      if(empty($range['start'])){ 
        $range['start'] = 0; 
      } 
      if(empty($range['end'])){ 
        $range['end'] = $file_size; 
      } 
      return $range; 
    } 
    return null; 
  } 
} // class end 
  
?>