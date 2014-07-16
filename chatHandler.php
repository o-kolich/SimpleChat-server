<?php
$messageN = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
$timeStamp = filter_input(INPUT_POST, 'timeStamp', FILTER_SANITIZE_SPECIAL_CHARS);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$dom = new DOMDocument("1.0", "utf-8");
$dom->load("chatHistory.xml");
$root=$dom->GetElementsByTagName('root')->item(0);

if($id === '0'){
    $clanek = $dom->createElement("message",'');
    $zprava = $dom->createElement("text", $messageN);
    $znacka = $dom->createElement("timeStamp", $timeStamp);
    $clanek->appendChild($zprava);
    $clanek->appendChild($znacka);
    $root->appendChild($clanek);
    $dom->save("chatHistory.xml");
    }
if($id === '1'){
    $clanky = $dom->getElementsByTagName('message');
    $newMessages = new stdClass();
    $count = 0;
    foreach ($clanky as $message){
        $stamp = $message->getElementsByTagName('timeStamp')->item(0)->nodeValue;
        if($stamp > $timeStamp){
            $newMessages->timeStamp = $stamp;
            $text = $message->getElementsByTagName('text')->item(0)->nodeValue;
            $newMessages->messages[$count] = $text;
            $count++;
        }
        }
    if(!$newMessages->timeStamp){$returningJSON = NULL;}
    else {$returningJSON = json_encode(objectToArray($newMessages));}
    echo $returningJSON;
    }
if($id === '2'){
    $root->parentNode->removeChild($root);
    $rootN = $dom->createElement("root",' ');
    $dom->appendChild($rootN);
    $dom->save("chatHistory.xml");
    }
    
function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the object
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		return array_map(__FUNCTION__, $d);
	} else {
		// Return array
		return $d;
	}
}
?>
