<h3><?php echo Yii::t('app', 'pages');?></h3>


<ul class="link-list sortable" id="pagelist">
 <?php foreach($this->getPages() as $page): ?>
 <li><span class="handle" style="display:none">&#x2193;&#x2191; drag</span><a href="<?php echo Yii::app()->createUrl('/page/show', array('id'=>$page->id,)); ?>"><?php echo $page->title;?></a></li> 
<?php endforeach; ?>
</ul>
<?php if ($this->type == 0) { ?>
<a href="<?php echo Yii::app()->createUrl('/page/create'); ?>" class="btm-link"><?php echo Yii::t('app', 'add.account.page'); ?></a>
<?php } else {?>
<a href="<?php echo Yii::app()->createUrl('/page/create', array('type'=>1,)); ?>" class="btm-link"><?php echo Yii::t('app', 'add.project.page'); ?></a>
<?php }?>