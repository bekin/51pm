<?php

class ShortMsg extends CActiveRecord
{
	CONST MSG = 0; 
	CONST COMMENT = 1;

	public $attachment;
	
	private $activityType = Activity::TYPE_CREATE_MESSAGE;
	
	public $projectName;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ShortMsg';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title','length','max'=>100),
			array('createdBy','length','max'=>20),
		);
	}

	public function relations()
	{
		return array(
			'attachments'=>array(self::HAS_MANY, 'Attachment', 'messageId'),
			'poster' => array(self::BELONGS_TO, 'User', 'createdBy'),
		);
	}
	
	protected function beforeSave()	
	{
		if ($this->isNewRecord)
		{
			$this->createdBy = Yii::app()->user->userRecord->userId;
			$this->createdOn = new CDbExpression('NOW()');
		}
				
		$this->companyId = Yii::app()->user->userRecord->companyId;
		
		return CActiveRecord::beforeSave();				
	}
	
	public function getComments($msgId)
	{
		$criteria=array(
			'condition'=>'ShortMsg.msgId='.$msgId,
		);
		
		return $this->with('attachments', 'poster')->findAll($criteria);		
	}
	
	public function saveComment($runValidation=true,$attributes=null) {
		$this->activityType = Activity::TYPE_REPLY_MESSAGE;
		$succeeded = $this->save($runValidation, $attributes);
		if ($succeeded)
		{ 
			$sql = "update ShortMsg set commentCount = commentCount + 1 where id = " . $this->msgId;
			$model=ShortMsg::model();
			$connection = $model->dbConnection;  
			$command=$connection->createCommand($sql);
			$command->execute();	
		}
		return $succeeded; 
	}
	
	public function save($runValidation=true,$attributes=null) 
	{
		$model=ShortMsg::model();
		$succeeded = true;
		$transaction=$model->dbConnection->beginTransaction();
		try
		{
			$succeeded = parent::save($runValidation, $attributes); 
			if ($succeeded)
			{
				$count = 0; 			 
				$attaches = $_FILES['attachment'];
				for ($i = 0; $i < count($attaches); $i++) 
				{
					if (isset($attaches['tmp_name'][$i]) && $attaches['error'][$i] == 0)
					{
						$a = new Attachment;
						$name =  basename( $attaches['name'][$i]);
						$a->projectId = $this->projectId;
						$a->fileName = $name;
						$a->companyId = $this->companyId;
						
						$idx = strrpos ( $name, '.');
						if (idx > 0) 
						{
							$a->title = substr($name, 0, idx);
						}
						else 
						{
							$a->title = $name;
						}
						$contentType = $attaches['type'][$i]; 
						$a->contentType = $contentType;
						$a->contentSize = $attaches['size'][$i];				
						if (strpos($contentType, 'image') === false)
						{
							$a->isImage = 0;
						}
						else 
						{
							$a->isImage = 1;	
						}
						
						$a->createdBy = $this->createdBy;
						$a->createdOn = $this->createdOn; 
						$a->messageId = $this->id;
						
						$dates = getdate();
						
						$file_path = $dates['year']. DIRECTORY_SEPARATOR.
									$dates['mon']. DIRECTORY_SEPARATOR.
									$dates['mday']. DIRECTORY_SEPARATOR.
									$this->projectId; 
						$target_path = 
									//dirname(Yii::app()->basePath).
									Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR. $file_path;
									
						chdir(dirname(Yii::app()->basePath));			
						Utils::recursive_mkdir($target_path);							
						$uuid = Utils::uuid();
						$target_path = $target_path	. DIRECTORY_SEPARATOR.	$uuid; 
						
						$a->location = $file_path . DIRECTORY_SEPARATOR . $uuid;			
						$a->save();
	 					
						move_uploaded_file($attaches['tmp_name'][$i], $target_path);
						
						$count++;
					}
				
				}

				if ($count > 0)
				{
					$sql = 'update ShortMsg set attachmentCount = attachmentCount + '. $count . 
						' where id = ' . ($this->msgId ? $this->msgId : $this->id);
					$connection = $model->dbConnection;  
					$command=$connection->createCommand($sql);
					$command->execute();	
				}
			}
			$this->logActivity();
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
		
		return $succeeded;
						
	}

	protected function logActivity()
	{
		$act = new Activity;
		$act->activityDate = $this->createdOn; 
		$act->activityType = $this->activityType; 
		$act->projectId = $this->projectId;
		$act->userId = $this->createdBy;
		$act->companyId = $this->companyId;
		$desc = array(
			'id'=>$this->id,
			'title'=>$this->title,
			'project'=>$this->projectName,
		);
		$act->activityDesc = serialize($desc);
		$act->save();		
	}

	public function getProjectMessages($projectId)
	{
		$criteria=array(
			'condition'=>'projectId='.$projectId. ' and msgId is null',
			'order'=>'id desc',
		);
		
		return $this->with('poster')->findAll($criteria);
	}
	
	public function getMessages($companyId)
	{
		$criteria=array(
			'condition'=>'ShortMsg.companyId='.$companyId. ' and msgId is null',
			'order'=>'id desc',
		);
		
		return $this->with('poster')->findAll($criteria);
	}
	
	public function deleteMessage($id)
	{
		$model=ShortMsg::model();
		$connection = $model->dbConnection;
		$transaction=$connection->beginTransaction();
		try
		{

$sql = <<< END
select location from Attachment where messageId in 
				(select id from ShortMsg where id = :id or msgId = :msgId)						
END;
		
			$command=$connection->createCommand($sql);
			$command->bindParam(":id", $id, PDO::PARAM_INT);
			$command->bindParam(":msgId", $id, PDO::PARAM_INT);
			$cols = $command->queryColumn();	
			
			foreach ($cols as $loc) {
				unlink(Yii::app()->params['contentRoot'] . DIRECTORY_SEPARATOR. $loc);
			}
			
$sql = <<< END
delete from Attachment where messageId in 
				(select id from ShortMsg where id = :id or msgId = :msgId)						
END;
			
			$command=$connection->createCommand($sql);
			$command->bindParam(":id", $id, PDO::PARAM_INT);
			$command->bindParam(":msgId", $id, PDO::PARAM_INT);
			$command->execute();	
			
			
			$sql = 'delete from ShortMsg where id = :id or msgId = :msgId ';			  
			$command=$connection->createCommand($sql);
			$command->bindParam(":id", $id, PDO::PARAM_INT);
			$command->bindParam(":msgId", $id, PDO::PARAM_INT);
			$command->execute();	
		
			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
		
		return $succeeded;
		
	}
}