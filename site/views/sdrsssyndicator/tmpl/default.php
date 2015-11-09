<?php
	defined('_JEXEC') or die('Restricted access');
	include_once(JPATH_COMPONENT.'/views/sdrsssyndicator/tmpl/feedcreator.class.php');
	JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
	$mainframe = JFactory::getApplication();
	$tzoffset=$this->tp;
    $tzoffset2 = sprintf("%01.2f",$tzoffset);
	$feedid = $this->id;
	$docache = intval($this->cache)>0?1:0;
	$numWords = $this->numWords > 0  ? $this->numWords: 10000; // numWord == 0 represents ALL
	$filename = JPATH_COMPONENT."/feed/feed".$feedid.".xml";
	$rss = new UniversalFeedCreator();
	if (intval($docache)==1) {
	    $rss->useCached($this->type,$filename,$this->cache); // use cached version if age<1 hour. May not return!
	}

	$rss->title 				= htmlspecialchars($this->title, ENT_QUOTES);
	$rss->description			= $this->description;
	$rss->link 				= JURI::root();

	$u = JFactory::getURI();
	$rss->syndicationURL 			= $u->toString();
	$rss->descriptionHtmlSyndicated 	= true;
    $rss->tzoneg=$tzoffset2;
	$image 					= new FeedImage();
	$image->title 				= $mainframe->getCfg('sitename');
	$image->url 				= $this->imgUrl;
	$image->link 				= JURI::root();
	$image->description			= $mainframe->getCfg('sitename');
	$image->descriptionHtmlSyndicated	= true;

	if ( $this->imgUrl!="") { $rss->image = $image; }
	$rows = $this->content;
    //$rowsk2 = $this->contentk2;
	//$isk2 = $this->isk2;
    //$rssb = null;
	//used to trigger content plugins below
	JPluginHelper::importPlugin( 'content' );
    $dispatcher = JDispatcher::getInstance();

	foreach ($rows as $row) {
		$item 		 = new FeedItem();
		$item->title = htmlspecialchars($row->title);
		$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug),false,2);
		$item->guid = $item->link;
		$AddReadMoreLink = false;
		$words2='';
		switch ($this->fulltext) {
			case 0:
			case 1:
				$words = $row->introtext;
				break;
			case 2:
				$words = $row->introtext.$row->fulltext;
				break;
			case 3:
				$words = $row->fulltext;
				
				break;
			case 4:
				$words = $row->introtext;
				$words2 = $row->fulltext;
				break;
			case 5:
				$words = $row->introtext;
				$words2 = $row->introtext.$row->fulltext;
				break;
		}

		if($this->fulltext == 0)
		{
			$AddReadMoreLink = false;

		}

		if (($this->fulltext == 1 or $AddReadMoreLink)and($this->fulltext != 4) and ($this->fulltext != 5)) {
			if (strlen(trim($row->mtext)) > 0 or $AddReadMoreLink){
				$words .= "\n<p><a href=\"" . $item->link . "\">" . JText::sprintf('Read more...') . "</a></p>";
			}
		}
    	if (($this->fulltext == 4) or ($this->fulltext == 5)) {
			$words2=addAbsoluteURL($words2);
			$imgtab=first_img_src($words2);
			$ids=allvideof($words2);//
			//получам массив с id  видео
			if (sizeof($ids)>0) {
				for($z=0;$z<sizeof($ids);$z++) {
					$rez =$this->model->getVideo($ids[$z]);
					$media_gr=new media_group();
					$media_gr->content_url=$rez[0]->video;
					$media_gr->player_url='';
					$media_gr->thumbnail_url=$rez[0]->thumb;
					$item->media_group[]=$media_gr;
					//print_r($media_gr);die();
				}
			}
			preg_match('/.*({youtube}).*/is',$words2,$you);
			if (sizeof($you)>0) {
				$enclosure = new EnclosureItem();
				$enclosure->url =$item->link;
				$enclosure->type='video/x-flv';
				$item->enclosure[] = $enclosure;
			}
			if ($imgtab != false )
			{
				foreach ($imgtab as $imagese) {
					$enclosure = new EnclosureItem();
					$enclosure->url = $imagese;
					$extimage = strtolower(substr(strrchr($imagese, '.'), 1));
					if ($extimage=="jpg") $extimage="jpeg";
					$enclosure->type = "image/"."$extimage";
					//$enclosure->length = sprintf("%u", filesize($imagese));
					$enclosure->length = fsize($imagese);
					$item->enclosure[] = $enclosure;
				}
			}
			$links = first_link ($words2);
			if ($links != false) {
			$i=0;
			for ($i = 0; $i < count($links[0]); $i++) {
			$Yandexrelateds = new YandexrelatedItem();
			$Yandexrelateds->url = $links[0][$i];
			$Yandexrelateds->text = $links[1][$i];
			$item->yandexrelated[$i] = $Yandexrelateds;
			}
			}

		}
		//if ((!intval($this->renderHTML))or ($this->fulltext == 4) or ($this->fulltext == 5)){
		if (!intval($this->renderHTML)){
		  //Remove HTML tags if told not to render them
		  $words = noHTML ($words);
		  $words2 = noHTML ($words2);
		} else {
		
		  if ((!intval($this->renderImages))or ($this->fulltext == 4) or ($this->fulltext == 5)) {
		    $words = delImagesFromHTML($words);
			$words2 = delImagesFromHTML($words2);
		  }
		}

	
		$words = addAbsoluteURL($words);
		$textwords = str_replace("\n", " ", $words);
		$wordst = explode(" ", $textwords);
		$counttext = 0;
		foreach($wordst as $wordt)
		{
		if(strlen($wordt) > 3) $counttext++;
		}

		if ($counttext > $numWords)
		{
		    $AddReadMoreLink = true;
		    $words = word_limiter($words, $numWords);
			$words = $words ;
		}
		switch ($this->categoryItem) {


			case 2:
				$item->category = $row->catName;
				break;
			case 0:
			default:
				$item->category = '';
				break;

		}

		//$words = preg_replace('/.*{*[^\}]}.+/','',$words);
       // $words2 = preg_replace('/.+{*[^\}]}.+/','',$words2);
	    $words = preg_replace('/({youtube}[^{}]+{\/youtube})/is','',$words);
        $words2 = preg_replace('/({youtube}[^{}]+{\/youtube})/is','',$words2);
		$words = preg_replace('/({phocagallery[^{}]+})/is','',$words);
        $words2 = preg_replace('/({phocagallery[^{}]+})/is','',$words2);
		//{phocagallery view=category|categoryid=315|limitstart=0|limitcount=0}
		
		$item->description 			= $words;
		$item->descriptionfull		= $words2;
		$item->descriptionHtmlSyndicated	= true;
		$item->date = date("r",strtotime($row->dsdate));
		$item->zonatime=$tzoffset;
		$item->source 				= JURI::root();

		if ($this->renderAuthorFormat){
			$author = trim($row->authorAlias);

			if (empty($author)) $author = $row->author;

			$item->author 	= $author;
			$item->authorEmail	= $row->authorEmail;
		}
		if ($this->yandexgenre) {
			$item->yandexgenre = $this->yandexgenre;

		}
	  $dispatcher->trigger( 'onPreparesdRSSFeedRow', array( &$item ) );


		$rss->addItem($item);
	}

	//If needed, trigger content plugins on the feed as a whole.
	//TODO - expand this to allow for individual paramters for the plugin instances
	$dispatcher->trigger( 'onPreparesdRSSFeed', array( $rss ) );

        ob_end_clean();
       ob_start();
 	//If we are using the cache and the time out is greater than 0, then generate and use a file.
 	//Otherwise generate the feed on the fly
	if (intval($docache)==1 && $this->cache > 0)
	{
		$rss->saveFeed($this->type,$filename,true);
	} else {
		$rss->outputFeed($this->type);
	}

function noHTML($words) {
    $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
	$words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
	$words = preg_replace('/<!--.+?-->/','',$words);
	//$words = preg_replace('/{.+?}/','',$words);
	$words = strip_tags($words);
	$words = preg_replace("/'/",'&apos;',$words);
	$words = preg_replace('/&nbsp;/',' ',$words);
	//$words = preg_replace('/&amp;/','&',$words);
	//$words = preg_replace('/&quot;/','"',$words);

	return $words;
}
function nospecsimvol($words) {
    $words = preg_replace('/&/','&amp;',$words);
	$words = preg_replace('/</','&lt;',$words);
	$words = preg_replace('/>/','&gt;',$words);
	$words = preg_replace("/'/",'&apos;',$words);
	$words = preg_replace('/"/','&quot;',$words);


	return $words;
}

function addAbsoluteURL($html) {
	$root_url = JURI::root();
	$html = preg_replace('@href="(?!http://)(?!https://)(?!mailto:)([^"]+)"@i', "href=\"{$root_url}\${1}\"", $html);
	$html = preg_replace('@src="(?!http://)(?!https://)([^"]+)"@i', "src=\"{$root_url}\${1}\"", $html);

	return $html;
}

/*
** Delete all the images from the url
*/
function delImagesFromHTML($html, $instances = -1) {
  $html = preg_replace('/<img\\s.*>/i','', $html, $instances);

  return $html;
}

/* >> MAD 2007/10/09
 * Added function word_limiter
 */
function word_limiter($string, $limit = 100) {
	$words = array();
	$string = eregi_replace(" +", " ", $string);
	$array = explode(" ", $string);
	//$limit = (count($array) <= $numwords) ? count($array) : $numwords;
	for($k=0;$k < $limit;$k++)
	{
		if(($limit>0 && $limit == $k)||!isset($array[$k]))
			break;
		if (eregi("[0-9A-Za-zÀ-ÖØ-öø-ÿ]", $array[$k]))
			$words[$k] = $array[$k];
	}
	$txt = implode(" ", $words);
	return $txt;
}


	function first_img_src($html) {
        if (stripos($html, '<img') !== false) {
		
			$img=null;
			$i=0;
            $imgsrc_regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
            preg_match_all($imgsrc_regex, $html, $matches);
            unset($imgsrc_regex);
            unset($html);
			foreach ($matches[2] as $imagese) {
				//$purl=parse_url ($imagese);
				//if (stripos($purl['host'], 'www.')===0) {
					//вырезаем 4 символо слева.
				//	$host_s=substr($purl['host'],4);
				//	$root_url = JURI::root();
			 
				//	if (stripos($root_url,$host_s) !==false) {
						// вносим в массив
				//		$img[$i]=$imagese;
						
			 
				//	}
				//} else {
					$img[$i]=$imagese;
				//}
			$i++;
			}	
			//print_r($matches); die();
            if (is_array($img) && !empty($img)) {
			//print_r($img); die();
                return $img;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	function first_youtube ($html) {
		 if (stripos($html, '<iframe') !== false) {
			preg_match('#<\s*iframe[^\>]*src\s*=\s*\"http://[wW\.]*youtube\.com/embed/[^\"]+\"#iU', $html, $youtube);
			if (is_array($youtube) && !empty($youtube[0])) {
				return true;
			} else {
				return false;
			}
		}
	}
	function first_link ($html) {
		if (stripos($html, '<a') !== false) {
			$imgsrc_regex = '/<a\s+.*?href="(?!mailto:)([^"]+)"[^>]*>([^<]+)<\/a>/is';
			preg_match_all($imgsrc_regex, $html, $matches2);
			//print_r($matches2);
			$matchessum[1]=$matches2[2];
			$matchessum[0]=$matches2[1];
            unset($imgsrc_regex);
            unset($html);
            if (is_array($matchessum[0]) && !empty($matchessum[0])) {
                return $matchessum;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
function fsize($path)
{
	$fp = fopen($path,"r");
	$inf = stream_get_meta_data($fp);
	fclose($fp);
	foreach($inf["wrapper_data"] as $v)
		if (stristr($v,"content-length"))
		{	
			$v = explode(":",$v);
			return trim($v[1]);
		}
}

function allvideof($word) {
//print_r($word);
	//{avsplayer videoid=1 playerid=1}
	if (stripos($word, '{avsplayer') !== false) {
		preg_match_all('#{avsplayer[\s]+videoid=([0-9]+)[^}]*}#is', $word, $video,PREG_PATTERN_ORDER);
		//print_r($video);die();
		return $video[1]; //получаем массив id  видео.
	} else {
		return array();
	}

}
