<?php
/**
 * RSS2 Feed Template for displaying activities.
 */
$today = time();
$dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';
if (count($activities) > 0)
{
    $last = $activities[0]->activityDate;   
    $today = CDateTimeParser::parse($last, $dateTimeIncomeFormat); 
}
header('Content-Type: application/rss+xml; charset=UTF-8', true);

echo '<?xml version="1.0" encoding="UTF-8"?>'; 
?>

<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/">

<channel>
    <title><?php echo $company->companyName; ?></title>
    <atom:link href="<?php echo CHtml::encode(Utils::self_link()); ?>" rel="self" type="application/rss+xml" />
    <link><?php echo CHtml::encode(Utils::self_link());  ?></link>
    <description><?php echo Yii::t('app', 'rss.activity', array('{company}'=>$company->companyName,)); ?></description>
    <lastBuildDate><?php echo date('D, d M Y H:i:s +0000', $today); ?></lastBuildDate>        
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>
    <?php foreach ($activities as $a): 
    
            $ts = CDateTimeParser::parse($a->activityDate,'yyyy-MM-dd hh:mm:ss' );
                $dokens = getdate($ts);
                $info = unserialize($a->activityDesc);
                $desc = ''; 
                $title = '';
                $link = '';
                $pubdate = '';
                switch ($a->activityType) {
                    case 'create_ticket':
                    case 'update_ticket':
                        $params = array(
                                '{user}'=>$a->user->userName,
                                '{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
                                '{ticket}'=>$info['title'],
                                '{ticketurl}'=> $this->createUrl('/ticket/show', array('id'=>$info['id'], )),
                            ); 
                        $desc = Yii::t('app', ($a->activityType == 'create_ticket') ? 'ticket.created.by' : 'ticket.updated.by', $params); 
                        $title = $info['project'] . ': ' .  $info['title'];
                        $link = $this->createUrl('/ticket/show', array('id'=>$info['id'], )); 
                        break;
                    case 'create_milestone':
                        $params = array(
                                '{user}'=>$a->user->userName,
                                '{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
                                '{title}'=>$info['title'],
                                '{url}'=> $this->createUrl('/milestone/show', array('id'=>$info['id'], )),
                            ); 
                        $desc = Yii::t('app', 'milestone.created.by' , $params); 
                        $title = '[' . Yii::t('app', 'milestone') . ']' .$info['project'] . ': ' .  $info['title'];
                        $link = $this->createUrl('/milestone/show', array('id'=>$info['id'], )); 
                        break;                  

                    case 'create_message':
                        $params = array(
                                '{user}'=>$a->user->userName,
                                '{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
                                '{title}'=>$info['title'],
                                '{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
                            ); 
                        $desc = Yii::t('app', 'message.created.by' , $params); 
                        $title = '[' . Yii::t('app', 'message') . ']' .$info['project'] . ': ' .  $info['title'];
                        $link = $this->createUrl('/message/show', array('id'=>$info['id'], )); 
                        
                        break;  
                    case 'reply_message':
                        $params = array(
                                '{user}'=>$a->user->userName,
                                '{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
                                '{title}'=>$info['title'],
                                '{url}'=> $this->createUrl('/message/show', array('id'=>$info['id'], )),
                            ); 
                        $desc = Yii::t('app', 'message.replied.by' , $params); 
                        $title = '[' . Yii::t('app', 'message') . ']' .$info['project'] . ': ' .  $info['title'];
                        $link = $this->createUrl('/message/show', array('id'=>$info['id'], )); 
                        
                        break;  
                    case 'create_page':
                        $params = array(
                                '{user}'=>$a->user->userName,
                                '{userurl}'=> $this->createUrl('/user/show', array('id'=>$a->userId, )),   
                                '{title}'=>$info['title'],
                                '{url}'=> $this->createUrl('/page/show', array('id'=>$info['id'], )),
                            ); 
                        $desc = Yii::t('app', 'page.created.by' , $params); 
                        $title = '[' . Yii::t('app', 'page') . ']' .$info['project'] . ': ' .  $info['title'];
                        $link = $this->createUrl('/page/show', array('id'=>$info['id'], )); 
                          
                        break;                              
                }
    
    ?>
    <item>
        <title><?php echo $title; ?></title>
        <link><?php echo $link; ?></link>
        <pubDate><?php echo date('D, d M Y H:i:s +0000', $ts); ?></pubDate>
        <dc:creator><?php echo $a->user->userName; ?></dc:creator>
        <guid isPermaLink="false"><?php echo $link; ?></guid>
        <description><![CDATA[<?php echo $desc; ?>]]></description>
    </item>
    <?php endforeach; ?>
</channel>
</rss>
