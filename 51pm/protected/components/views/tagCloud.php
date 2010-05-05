<div class="sblock">
  <h3><?php echo Yii::t('app', 'tags');?></h3>
  <div class="taglist">
  	<?php foreach ($tags as $tag=>$c): if (!empty($tag)) { ?>    
      <a href="<?php echo Yii::app()->createUrl('/ticket/list', array('tag'=>$tag));?>"><?php echo $tag . '(' . $c . ')' ; ?></a>
    <?php } endforeach;?>  
  </div>  
</div>