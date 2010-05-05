<?php 	header("Content-type: application/vnd.ms-excel" ); 
       	header("Content-Disposition: attachment; filename=\"tickets.xls\"");
	    header("Cache-control: private"); 
?>

<table>
  <tr>
    <th>#</th>
    <th><?php echo Yii::t('app', 'ticket.status'); ?></th>
    <th><?php echo Yii::t('app', 'ticket.title'); ?></th>
    <th><?php echo Yii::t('app', 'owner'); ?></th>
    <th><?php echo Yii::t('app', 'duedate'); ?></th>
    <th><?php echo Yii::t('app', 'created.on'); ?></th>
  </tr>
  
  <?php foreach ($results as $r): ?>
  <tr>
    <td><?php echo $r->displayOrder; ?></td>
    <td><?php echo $r->ticketStatus; ?></td>
    <td><?php echo $r->title; ?></td>
    <td><?php echo $r->ownerinfo->userName; ?></td>
    <td><?php echo $r->duedate; ?></td>
    <td><?php echo $r->createdOn; ?></td>
  </tr>
  <?php endforeach;?>
  
</table>