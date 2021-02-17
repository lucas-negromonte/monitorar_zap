<?php

class LogHelper extends Conn
{
	public static function logErro($description='', $files='', $url='', $line_error='')
	{
		$model = new Conn();
		$db = $model->getBase();

		$time = (empty($hora) ? date('H:i:s') : $hora);
		
		$date = (empty($data) ? date('Y-m-d') : $data);
		try
		{
			$sql_log = $db->prepare("INSERT INTO log_error (url, files, description, line, date, time) VALUES (:url, :files, :description, :line, :date, :time)");
			$sql_log->bindValue(':url', $url, PDO::PARAM_STR);
			$sql_log->bindValue(':files', $files, PDO::PARAM_STR);
			$sql_log->bindValue(':description', $description, PDO::PARAM_STR);
			$sql_log->bindValue(':line', $line_error, PDO::PARAM_STR);
			$sql_log->bindValue(':date', $date, PDO::PARAM_STR);
			$sql_log->bindValue(':time', $time, PDO::PARAM_INT);
			$sql_log->execute();

			return true;

		}catch(PDOException $e){
			LogHelper::logErro($e->getMessage(), basename(__FILE__), $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $e->getLine());
			return false;
		}
	}
}