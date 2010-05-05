<h3>Projects</h3>
 <ul class="stacked wbadges" id="projects">
 <?php foreach($this->getProjects() as $project): ?> 
 <li class="proj"><a href="<?php echo Yii::app()->createUrl('/project/show', array('id'=>$project->id,)); ?>"><span class="badge"><?php echo $project->tickets; ?></span><?php echo $project->projectName;?></a></li> 
<?php endforeach; ?>
</ul>