<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Log extends CI_Log {

	/**
	 * Predefined logging levels
	 *
	 * @var array
	 */
    protected $_levels = array(
		// 'DISABLES' => 0, 
        'ERROR' => 1,
        'DEBUG' => 2,
        'INFO'  => 3,
		'ALL'   => 4,

		// Custom Log level
        'BLOG'   => 5
    );

	public function __construct() {
		parent::__construct();
	}

	// 아래부분 출처: https://extbrain.tistory.com/110?category=275792
	/*
	public function write_log($level, $msg) {

        if ($this->_enabled === FALSE) {
            return FALSE;
        }
        
        $level = strtoupper($level);
        
        if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
        	&& ! isset($this->_threshold_array[$this->_levels[$level]])) {
            return FALSE;
        }
        
		// 로그 종류에 따라 파일 분리
        // $filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
        $filepath = $this->_log_path.strtolower($level) . '-' . date('Y-m-d') . '.' .$this->_file_ext;
        $message = '';
        
        if ( ! file_exists($filepath)) {
            $newfile = TRUE;
            // Only add protection to php files
            if ($this->_file_ext === 'php') {
                $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
            }
        }
        
        if ( ! $fp = @fopen($filepath, 'ab')) {  // if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
            return FALSE;
        }
        
        flock($fp, LOCK_EX);
        
        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->_date_fmt, 'u') !== FALSE){
            $microtime_full = microtime(TRUE);
            $microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
            $date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
            $date = $date->format($this->_date_fmt);
        }
        else {
            $date = date($this->_date_fmt);
        }
        
        // $message .= $this->_format_line($level, $date, $msg);
        $message .= $this->_format_line_simple($date, $msg);
        
        for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result){
            if (($result = fwrite($fp, self::substr($message, $written))) === FALSE) {
                break;
            }
        }
        
        flock($fp, LOCK_UN);
        fclose($fp);
        
        if (isset($newfile) && $newfile === TRUE) {
            chmod($filepath, $this->_file_permissions);
        }
        
        return is_int($result);
    }
    
    protected function _format_line_simple($date, $message)
    {
        return $date.' --> '.$message."\n";
    }
	*/
}

