<?php
/**
* Copress helper
*
* @package PG_Core
* @subpackage application
* @category	helpers
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Mikhail Makeev <mmakeev@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2009-12-02 15:07:07 +0300 (Ср, 02 дек 2009) $ $Author: irina $
**/
if ( ! function_exists('program_compress'))
{
	function program_compress($layout, $file_type = 'js')
	{
		$layout = str_replace("\r","",$layout);
		$literal_strings = array();
		$lines = explode("\n",$layout);
		$clean = "";
		$inComment = false;
		$literal = "";
		$inQuote = false;
		$escaped = false;
		$quoteChar = "";
		$css = ($file_type == 'css') ? true : false;
		for($i=0;$i<count($lines);$i++)
		{
			$line = $lines[$i];
			$inNormalComment = false;
			for($j=0;$j<strlen($line);$j++)
			{
				$c = substr($line,$j,1);
				$d = substr($line,$j,2);
				if(!$inQuote && !$inComment)
				{
					if(($c=="\"" || $c=="'") && !$inComment && !$inNormalComment)
					{
						$inQuote = true;
						$inComment = false;
						$escaped = false;
						$quoteChar = $c;
						$literal = $c;
					}
					else if($d=="/*" && !$inNormalComment)
					{
						$inQuote = false;
						$inComment = true;
						$escaped = false;
						$quoteChar = $d;
						$literal = $d;
						$j++;
					}
					else if($d=="//" and !$css)
					{
						$inNormalComment = true;
						$clean .= $c;
					}
					else
					{
						$clean .= $c;
					}
				}
				else
				{
					if($c == $quoteChar && !$escaped && !$inComment)
					{
						$inQuote = false;
						$literal .= $c;
						$clean .= "___" . count($literal_strings) . "___";
						array_push($literal_strings,$literal);
					}
					else if($inComment && $d=="*/")
					{
						$inComment = false;
						$literal .= $d;
						if ($css)
						{
							$clean .= '';
						}
						else
						{
							$clean .= "___" . count($literal_strings) . "___";
						}
						
						array_push($literal_strings,$literal);
						$j++;
					}
					else if($c == "\\" && !$escaped)
						$escaped = true;
					else
						$escaped = false;
					$literal .= $c;
				}
			}
			if($inComment) $literal .= "\n";
			$clean .= "\n";
		}
		$lines = explode("\n",$clean);
		for($i=0;$i<count($lines);$i++)
		{
			$line = $lines[$i];
			if (!$css)
			{
				$line = preg_replace("/\/\/(.*)/","",$line);
			}
			$line = trim($line);
			$line = preg_replace("/\s+/"," ",$line);

//			if ($css)
//			{
//				$line = preg_replace("/\s*([!\}\{;,&=\|\+\/\)\(:])\s*/","\\1",$line);
//			}
//			else
//			{
//				$line = preg_replace("/\s*([!\}\{;,&=\|\-\+\*\/\)\(:])\s*/","\\1",$line);
//			}
			$lines[$i] = $line;
		}
		$layout = implode("\n",$lines);
		$layout = preg_replace("/[\n]+/","\n",$layout);
		$layout = preg_replace("/;\n/",";",$layout);
		$layout = preg_replace("/[\n]*\{[\n]*/","{",$layout);
		for($i=0;$i<count($literal_strings);$i++)
		{
			$layout = str_replace("___".$i."___",$literal_strings[$i],$layout);
		}

		return $layout;
	}
}