<?php

/**
 * @package Widgets
 *
 * @file ContactPubsubSubscription.php
 * This file is part of MOVIM.
 *
 * @brief The Group configuration widget
 *
 * @author Ho Christine <nodpounod@gmail.com>
 *
 * @version 1.0
 * @date 24 March 2013
 *
 * Copyright (C)2010 MOVIM project
 *
 * See COPYING for licensing information.
 */

class ContactPubsubSubscription extends WidgetBase
{

    function load()
    {
        $this->registerEvent('groupsubscribedlist', 'onGroupSubscribedList');
        $this->registerEvent('groupsubscribedlisterror', 'onGroupSubscribedListError');
        $this->addjs('contactpubsubsubscription.js');
    }
    
    function prepareList($list) { 
        if(is_array(array_slice($list, 0, 1))){
            $html = '<ul class="list">';
            
            foreach($list as $item){
                $html .= '<li><a href="'.Route::urlize('node', array($item[1], $item[0])).'">'.$item[2].'</a></li>';
            }
            
            $html .= '</ul>';
            return $html;
        } else {
            Notification::appendNotification(t('No public groups found'), 'info');
        }
    }
    
    function onGroupSubscribedList($list) {
        $html = $this->prepareList($list);
        RPC::call('movim_fill', 'publicgroups', $html); 
    }
    
    function onGroupSubscribedListError($error)
    {
        //Notification::appendNotification($error, 'error');
        RPC::call('hidePubsubSubscription');
    }
    
    function ajaxGetGroupSubscribedList($to){
        $r = new moxl\PubsubSubscriptionListGetFriends();
        $r->setTo($to)->request();
    }
    
    function build()
    {
        ?>
        <div class="tabelem" title="<?php echo t('Public groups'); ?>" id="groupsubscribedlistfromfriend">
            <div class="protect red" title="<?php echo getFlagTitle('red'); ?>"></div>
            <h1><?php echo t('Public groups'); ?></h1>
            <script type="text/javascript">
                <?php echo $this->genCallAjax('ajaxGetGroupSubscribedList', "'".$_GET['f']."'"); ?>
            </script>
            <div id="publicgroups" class="paddedtop"></div>
        </div>
        <?php
    }
}

?>
