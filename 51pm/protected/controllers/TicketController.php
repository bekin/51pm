<?php

class TicketController extends _BaseController
{
	const PAGE_SIZE=20;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	public $menutype = 'project';
	
	public $activemenu = 'tickets';	
	
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'edit', 'bulk', 'list', 'show', 'search', 'display','csv', 'watch',),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'expression'=>$this->isAdmin(),				
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionShow()
	{
		$ticket = $this->loadTicket();
		$histories = TicketHistory::model()->getHistories($ticket->id);
		$this->pageTitle = $ticket->title; 
		$this->render('show',array('model'=>$ticket, 'histories'=>$histories, ));
	}
	
	/**
	* Display ticket by display order
	*/
	public function actionDisplay() 
	{
		$ticket = Ticket::model()->getTicketByDisplayOrder(Yii::app()->session['currentProject']->id, $_GET['n']);
		$histories = TicketHistory::model()->getHistories($ticket->id);
		$this->pageTitle = $ticket->title;  
		$this->render('show',array('model'=>$ticket, 'histories'=>$histories, ));		
	}

	public function actionCreate()
	{
		$this->pageTitle = Yii::t('app', 'ticket.create'); 
		$model=new Ticket;
		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			if (isset($_POST['tags']))
			{
				$tags = $_POST['tags']; 
				$model->tags = implode(',', $tags);
			}
			if (isset($_POST['notifyall']))
			{
				$model->notifications = '_all_';
			}
			else if (isset($_POST['notify']))
			{
				$model->notifications = implode(',', $_POST['notify']); 
			}
			
			$model->projectId = Yii::app()->session['currentProject']->id;
			$model->projectName =  Yii::app()->session['currentProject']->projectName;
			
			$model->ticketDesc = str_replace("\n",'<br>',$model->ticketDesc);
			
			if($model->save())
			{
				EmailManager::setEmail(array('type'=>'ticket', 'action'=>'create','id'=>$model->id));
				$this->redirect(array('show','id'=>$model->id));
			}
		}
		$this->render('create',array('model'=>$model));
	}

	public function actionUpdate()
	{
		if(isset($_POST['Ticket']))
		{
			$incoming = new Ticket;
			$incoming->attributes=$_POST['Ticket'];
			if (isset($_POST['tags']))
			{
				$tags = $_POST['tags']; 
				$incoming->tags = implode(',', $tags);
			}
			if (isset($_POST['notifyall']))
			{
				$incoming->notifications = '_all_';
			}
			else if (isset($_POST['notify']))
			{
				$incoming->notifications = implode(',', $_POST['notify']); 
			}
			
			$incoming->ticketDesc = str_replace("\n",'<br>',$incoming->ticketDesc);
			$model = $this->loadTicket($_POST['id']);
			$model->projectName =  Yii::app()->session['currentProject']->projectName;
			if($model->updateTicket($incoming))
			{
				EmailManager::setEmail(array('type'=>'ticket', 'action'=>'update', 'history'=>$model->currentHistory,'id'=>$model->id,));
				$this->redirect(array('show','id'=>$model->id));
			}
		}
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			Ticket::model()->deleteTicket($_POST['id']);
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionSearch()
	{
		$this->renderPartial('search');
	}

	public function actionBulk() 
	{
		Ticket::bulkUpdate();
		Growl::setMessageKey('ticket.bulk.updated'); 
		$this->actionList(); 		
	}

	public function actionCsv()
	{
		$paging = new ResultPage(); 		
		$paging->itemCount = $_POST['count'];
		$conditions = unserialize($_POST['conds']);
		$paging->pageSize= 1000;
		if (isset($_POST['sort']))
		{
			$paging->sort = $_POST['sort']; 	
		}
		else 
		{
			$paging->sort = 'id';
		}
		$paging=Ticket::model()->getTickets(false, $conditions, $paging);

		$this->renderPartial('csv', array('results'=>$paging->results, ));		
	}
	
	public function actionWatch() 
	{
		if(Yii::app()->request->isPostRequest)
		{
			Ticket::model()->toggleWatch($_POST['id'], Yii::app()->user->userRecord->userId); 
		}
	}
	
	public function actionList()
	{
	
		if (isset($_GET['project']))
		{
			$project = Project::model()->findbyPk($_GET['project']);	
			Yii::app()->session['currentProject'] = $project;
		}
	
		$this->pageTitle = Yii::t('app', 'tickets');
		$needCount = true;
		$conditions = array();
		$paging = new ResultPage(); 		
		if (isset($_POST['page'])) 
		{
			$paging->currentPage = $_POST['page'];
		}
		if (isset($_POST['count']))
		{
			$paging->itemCount = $_POST['count'];
			$needCount=false;
		}
		if (isset($_POST['sort']))
		{
			$paging->sort = $_POST['sort']; 	
		}
		else 
		{
			$paging->sort = 'id';
		}
		if (isset($_POST['ascdesc']))
		{
			$paging->ascdesc = $_POST['ascdesc']; 	
		}
		else 
		{
			$paging->ascdesc = 'desc';
		}
		
		// get conditions
		if (isset($_GET['tag']))
		{
			$conditions['tag'] = $_GET['tag'];
		}
		else if (isset($_POST['conds'])) 
		{
			// this is paging request
			$conditions = unserialize($_POST['conds']);
		}
		else if (isset($_GET['s']))
		{
			// deal with shortcuts
			$s = $_GET['s'];
			$conditions['s'] = $s;
			$conditions['projectId'] = Yii::app()->session['currentProject']->id;
			if ($s == 'today') 
			{
				$conditions['createdOn'] = 'today';	
			}
			else if ($s == 'my') 
			{
				$conditions['owner'] = Yii::app()->user->userRecord->userId;	
				$s = Yii::app()->session['currentProject']->getOpenStatuses();
				$conditions['ticketStatus'] = Utils::quoted($s);
			}
			else if ($s == 'mywatch') 
			{
				$conditions['watcher'] = Yii::app()->user->userRecord->userId;	
			}
			else if ($s == 'reportedbyme') 
			{
				$conditions['createdBy'] = Yii::app()->user->userRecord->userId;	
			}
			else if ($s == 'open') 
			{
				$s = Yii::app()->session['currentProject']->getOpenStatuses();
				$conditions['ticketStatus'] = Utils::quoted($s);				
			}
			else if ($s == 'overdue') 
			{
				$s = Yii::app()->session['currentProject']->getOpenStatuses();
				$conditions['ticketStatus'] = Utils::quoted($s);				
				$conditions['overdue'] = 'over'; 
			}
			else if ($s == 'close') 
			{
				$s = Yii::app()->session['currentProject']->getCloseStatuses();
				$conditions['ticketStatus'] = Utils::quoted($s);				
			}
		}
		else {
			if (isset($_POST['owner']) && !empty($_POST['owner']))
			{
				$conditions['owner'] = $_POST['owner'];
			}
			if (isset($_POST['milestoneId']) && !empty($_POST['milestoneId']))
			{
				$conditions['milestoneId'] = $_POST['milestoneId'];
			}
			if (isset($_POST['ticketStatus']) && !empty($_POST['ticketStatus']))
			{
				$conditions['ticketStatus'] = array('\'' . $_POST['ticketStatus'] . '\'');
			}
			if (isset($_POST['ticketPriority']) && !empty($_POST['ticketPriority']))
			{
				$conditions['ticketPriority'] = array('\'' . $_POST['ticketPriority'] . '\'');
			}
			if (isset($_POST['duedate']) && !empty($_POST['duedate']))
			{
				$conditions['duedate'] =  $_POST['duedate'] ;
			}
			if (isset($_POST['ticketType']) && !empty($_POST['ticketType']))
			{
				$conditions['ticketType'] =  $_POST['ticketType'];
			}
			if (isset($_POST['title']))
			{
				$t = trim($_POST['title']);
				if (!empty($t)) 
				{
					$conditions['title'] =  $_POST['title'];
				}
			}			
			if (isset($_POST['tags']))
			{
				$t = trim($_POST['tags']);
				if (!empty($t)) 
				{
					$conditions['tags'] =  $_POST['tags'];
				}
			}			
			
			if (count($conditions) == 0) 
			{
			     // default to my open tickets
			    $conditions['my'] = true; 
				$conditions['owner'] = Yii::app()->user->userRecord->userId;
				$s = Yii::app()->session['currentProject']->getOpenStatuses();
				$conditions['ticketStatus'] = Utils::quoted($s);
			}
	
			// set project
			if (isset($_GET['project']))
			{
				$conditions['projectId'] = $_GET['project'];	
			}
			else if (isset($_POST['project']))
			{
				$conditions['projectId'] = $_POST['project'];	
			}
			else 
			{
				$conditions['projectId'] = Yii::app()->session['currentProject']->id;
			}
		}
		
		$paging->pageSize=self::PAGE_SIZE;
		
		$paging=Ticket::model()->getTickets($needCount, $conditions, $paging);

		$this->render('list',array(
			'paging'=>$paging,
			'conditions'=>$conditions,
			'desc'=>$this->niceDesc($conditions),
		));
	}

	private function niceDesc($c)
	{
		if (isset($c['tag']))
		{
			return Yii::t('app', 'tag') . ': ' . $c['tag']; 							
		}
		
		if (isset($c['s']))
		{
			if ($c['s'] == 'today') return  Yii::t('app', 'ticket.today'); 
			if ($c['s'] == 'mywatch') return Yii::t('app', 'ticket.mywatch'); 
			if ($c['s'] == 'my') return Yii::t('app', 'ticket.my'); 
			if ($c['s'] == 'reportedbyme') return Yii::t('app', 'ticket.reported.by.me'); 
			if ($c['s'] == 'overdue') return Yii::t('app', 'ticket.overdued'); 
			if ($c['s'] == 'open') return Yii::t('app', 'ticket.opened'); 
			if ($c['s'] == 'close') return Yii::t('app', 'ticket.closed'); 
		
			return '';
		}
		
		if (isset($c['my']))
		{
		     return Yii::t('app', 'ticket.my'); 
		}
		
		$desc = array();
		
		if (isset($c['owner']))
		{
			$ownerinfo = User::model()->findbyPk($c['owner']);
			if (!empty($ownerinfo))
			{
				$desc[] = Yii::t('app', 'owner') . ': ' . $ownerinfo->userName;
			} 			
		}
		if (isset($c['milestoneId']) && !empty($c['milestoneId']))
		{
			$milestoneinfo = Milestone::model()->findbyPk($c['milestoneId']);
			if (!empty($milestoneinfo))
			{
			     $desc[] = Yii::t('app', 'milestone') . ': ' . $milestoneinfo->title;
			}
		}
		if (isset($c['ticketStatus']))
		{
            $desc[] = Yii::t('app', 'ticket.status') . ': ' . $c['ticketStatus'][0];
		}
		if (isset($c['ticketPriority']))
		{
            $desc[] = Yii::t('app', 'priority') . ': ' . $c['ticketPriority'][0];			
		}
		if (isset($c['duedate']) )
		{
            $desc[] = Yii::t('app', 'duedate') . ': ' . $c['duedate']; 
		}
		if (isset($c['ticketType']))
		{			
			$desc[] = Yii::t('app', 'ticket.type') . ': ' . $c['ticketType']; 
		}
		if (isset($c['title']))
		{
            $desc[] = Yii::t('app', 'ticket.title') . ': ' . $c['title']; 			
		}			
		if (isset($c['tags']))
		{			
			$desc[] = Yii::t('app', 'tags') . ': ' . $c['tags']; 
		}			
		
		if (count($desc) > 0)
			return  Yii::t('app', 'search.criteria') . ' => ' . implode('; ', $desc);
		return ''; 
	}

	public function actionEdit()
	{
		$model = $this->loadTicket();
		$this->pageTitle = $model->title; 
		
		if(Yii::app()->request->isPostRequest)
		{			
			$model->updateTicketDesc(str_replace("\n",'<br>', $_POST['ticketDesc']));
			
			$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('edit',array('model'=>$model));		
	}

	public function loadTicket($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=Ticket::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

}
