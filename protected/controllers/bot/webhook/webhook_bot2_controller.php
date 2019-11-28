<?php
class webhook_bot2_controller extends bot_project
{
    const ENDPOINT = "https://api.telegram.org/bot" . BOT_2;
	public function end()
	{
        $content = file_get_contents("php://input");
        $this->log_write_moderator($content);

        $update = json_decode($content, TRUE);
        $this->writeLog('moderator', $update);

        if(!$update) {
            $this->forbidden();
        }
        if(key_exists("callback_query", $update)){
            exit;
        }else {
            $message = $update["message"];
            $chatId = $message["chat"]["id"];
            if(isset($message["caption"])){
                $text=$message["caption"];
            }else{
                $text = $message["text"];
            }

            $user_telegram = $message['from']['username'];
            $first_name = ucfirst($message['from']['first_name']);
            $last_name = ucfirst($message['from']['last_name']);
            $messageId =$message['message_id'];

            $id_user = $message["from"]["id"];
        }




        $good_users = array(
            BOT_NAME,
            MODERATOR_BOT,
            CHAT_ADMIN,
            str_replace('@', '',CHANNEL_NAME),
            str_replace('@', '', CHANNEL_NAME),
            str_replace('@', '',MODERATOR_BOT),
            str_replace('@', '',CHAT_NAME)
        );
        
        if(in_array($user_telegram, $good_users)){
            return;
        }
        if(array_key_exists('forward_from', $message)){
            if(in_array($message['forward_from']['username'], $good_users)) {
                if($message['forward_from']['username'] == BOT_NAME) {
                    if(strpos($message['text'], "The withdrawal operation has been successfully completed")) {
                        preg_match("/\/tx\/([^\n]+)/", $message['text'], $matches);
                        if($tx_id = $matches[1]) {
                            if(deposit_service::forwardWithdrawalPrize($tx_id)) {
                                return;
                            }
                        }
                    }
                }
                return;
            } else {
                $this->deleteMsg($chatId, $messageId);
            }
        }
        if(array_key_exists('forward_from_chat',$message) ) {
            if (!in_array($message['forward_from_chat']['username'], $good_users)) {
                $this->deleteMsg($chatId, $messageId);
            } else {
                return;
            }
        }
        if($this->getBotCommand($text)) {
            $this->deleteMsg($chatId, $messageId);
//            tbot_class::reply($chatId, $this->fetch('moderator/command_request'), $messageId, BOT_2);
            return;
        }
        $telegram_bots = $this->getTelegramBot($text);
        $BotMention = $this->getBotMention($text);




        //1) Ссылки только на наши проекты
        $goodDomain = array(
            'youtu.be',
            'blockexplorer.com',
            'blockchain.com',
            't.me',
            'www.blockchain.com',
        );


        foreach ($goodDomain as $domain){
            $goodDomain[]='www.'.$domain;
        }

        //
        $goodBots = array(
            BOT_NAME,
            MODERATOR_BOT,
            str_replace('@', '', CHANNEL_NAME),
            str_replace('@', '', CHAT_NAME),
        );

        if($telegram_bots) {
            foreach ($telegram_bots as $bot){
                if(!in_array($bot,$goodBots)){
                    $this->log_write_moderator("Forbidden linked bot: $bot");
                    $this->deleteMsg($chatId,$messageId);
                }
            }
        }

        if($BotMention) {
            foreach ($BotMention as $bot){
                echo $bot;
                if(!in_array($bot,$goodBots)){
                    $this->log_write_moderator("Forbidden mentioned bot $bot ");
                    $this->deleteMsg($chatId,$messageId);
                }
            }
        }





        //https://bpal.io/activity/i/XQNGAYWin
        //https://t.me/joinchat/FAbRsA8M3jiLTp2vfbwHFA
        //http://t.me/BitcoinDiggerLinkbot?start=632924634
        $url_arr = $this->GetURL($text);



        if($url_arr){
            foreach ($url_arr as $url) {
                $this->log_write_moderator("Text with link: $url");
                $domain = $this->getDomain($url);
                $this->log_write_moderator("This is link to $domain");
                if (!in_array($domain, $goodDomain)) {
                    $this->log_write_moderator("Forbidden link");
                    $this->deleteMsg($chatId,$messageId);
                }

            }
        } elseif($entities = $this->UrlInMsgTelegram($message) and !$url_arr) {
            foreach($entities  as $k => $item) {
                foreach ($goodDomain as $domain) {
                    if(false !== strpos($item, $domain)) {
                        unset($entities[$k]);
                    }
                }

            }
            if($entities) {
                $this->log_write_moderator("Could not read link");
                $this->deleteMsg($chatId,$messageId);
            }

        }
        $this->success();
	}





    function getTelegramBot($text){
        preg_match_all("/t\.me\/([_A-z0-9]+)/", $text, $matches);
        $bots_arr = $matches[1];

        preg_match_all("/telegram\.me\/([_A-z0-9]+)/", $text, $matches);
        if($matches[1]) {
            foreach ($matches[1] as $match) {
                $bots_arr[]  = $match;
            }
        }
        if(count($bots_arr)){
            return $bots_arr;
        }
        return false;
    }
    
    
    
    function getBotMention($text){
        preg_match_all("/@([_A-z0-9]+)/", $text, $matches);
        $bots_arr = $matches[1];
        if(count($bots_arr)){
            return $bots_arr;
        }
        return false;
    }

    function getBotCommand($text){
        return (strpos($text,'/menu@BingoClub_bot') !== false || strpos($text,'/menu'));
        preg_match_all("/(\/[_A-z0-9]+)\@/", $text, $matches);
        $bots_arr = $matches[1];
        if(count($bots_arr)){
            return $bots_arr;
        }
        return false;
    }
    
    
    
    
    
    
    
    
    //$urls = isURL($message);
    
    //$bots_arr = $this->getTelegramBot($this->remove_emoji($message_arr['text']));
    
    
    
    
    function UrlInMsgTelegram($message)
    {
        $res = [];
        if($message['entities']) {
            foreach ($message['entities'] as $entities) {
                if ($entities['type'] == 'url' or $entities['type']=='text_link') {
                    $res[] = $entities['url'];
                }
            }
        }

        if($message['caption_entities']) {
            foreach ($message['caption_entities'] as $entities) {
                if ($entities['type'] == 'url' or $entities['type']=='text_link') {

                    $res[] = $entities['url'];
                }
            }
        }
        return $res ? $res : false;
    }
    
    
    function getDomain($url){
        $result = parse_url($url);
        return $result['host'];
    }
    
    function deleteMsg($chat_id,$message_id){
        $this->log_write_moderator('Delete Message');
        $url = self::ENDPOINT. '/deleteMessage?chat_id=' . $chat_id . '&message_id=' . $message_id;
        $result = (file_get_contents($url));
        $this->log_write_moderator($url);
    }
    
    
    
    function telegramLink($url){
        $name = str_replace("?start","",$url);
        $name_arr = explode('=',$name);
        if(count($name_arr)==1){
            return $name;
        }else{
            return $name_arr[0];
        }
    }
    
    
    
    
    function GetURL($text){
        $reg_exUrl ="/\b(?:(?:https?|ftp|http|):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
    // Check if there is a url in the text
        $url_match = preg_match_all($reg_exUrl, $text, $url);
        if($url_match) {
            return $url[0];
            //return array_diff($url,array('https','http','ftp','ftps'));
        }
        return false;
    }
    
    
    
    
    function log_write_moderator($log){
        $this->writeLog('moderator', $log);
        // }
    }
}