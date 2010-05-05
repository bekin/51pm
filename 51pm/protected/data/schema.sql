create table if not exists Company (
	id int unsigned auto_increment primary key,
	version integer not null default 0,
	companyName varchar(100) not null, 
	companyDesc text, 
	siteUrl varchar(100),
	externalWebsite varchar(100), 	
	companyCode varchar(40), 
	email varchar(30),
	phone varchar(20), 
	address varchar(100), 
	city varchar(30), 
	state varchar(30),
	zip varchar(10),
	contact varchar(30),
	
  createdBy  int unsigned,
  modifiedBy  int unsigned,
  modifiedOn datetime,
  createdOn datetime,
  KEY companyName (companyName)
); 

create table if not exists Project (
	id int unsigned auto_increment primary key,
	version integer not null  default 0,
	projectName varchar(100) not null, 
	projectDesc text, 
	projectType varchar(50), 
	
	dfltAssigned varchar(100),
	dlftMilestone integer default 0, 
	ticketStatuses text,
	ticketTypes text, 
	openStates text, 
	closeStates text,
	generateActivity tinyint(1) default 1,
	tickets integer default 0, 
	openTickets integer default 0,  
	
	projectLocale varchar(10), 

  createdBy  int unsigned,
  modifiedBy  int unsigned,
  modifiedOn datetime,
  createdOn datetime,
	
	
	companyId int unsigned not null, 
	KEY projectName (projectName),
	FOREIGN KEY (companyId) REFERENCES Company(id)
); 

create table if not exists User (
	userId int unsigned auto_increment primary key,  
	userName varchar(100) not null, 
	roles varchar(200), 
	version integer not null  default 0,
	tagline varchar(100), 
	password varchar(40) not null, 
	avatar varchar(200), 
	jobTitle varchar(100),
	email varchar(100) unique not null, 
	emailPreference varchar(100), 
	website varchar(100),
	active tinyint(1) default 1,	
	lastLogin datetime, 
	lastIp varchar(20), 
	uuid varchar(50),

	facebook varchar(50),
	twitter varchar(50),
	openid varchar(50),

	createdBy int unsigned,
	modifiedBy int unsigned,
	modifiedOn datetime,
	createdOn datetime,

	companyId int unsigned not null,
	KEY userName (userName), 
	FOREIGN KEY (companyId) REFERENCES Company(id)
); 
 

create table if not exists ProjectCommittee (
	projectId int unsigned not null, 
	userId int unsigned  not null, 
	role varchar(100), 
	joinOn datetime, 
	
	companyId int unsigned not null, 
	primary key (projectId, userId),
	FOREIGN KEY (companyId) REFERENCES Company(id),
	FOREIGN KEY (projectId) REFERENCES Project(id),
	FOREIGN KEY (userId) REFERENCES User(userId)
); 

create table if not exists Milestone (
	id int unsigned auto_increment primary key,
	projectId int unsigned,

	title varchar(100) not null, 
	duedate datetime,
	milestoneDesc text, 
	
  createdBy  int unsigned,
  modifiedBy  int unsigned,
  modifiedOn datetime,
  createdOn datetime,
	companyId int unsigned, 
	FOREIGN KEY (companyId) REFERENCES Company(id),
  FOREIGN KEY (projectId) REFERENCES Project(id)				
);

create table if not exists Ticket (
	id int unsigned auto_increment primary key,
	version integer not null  default 0,
	projectId int unsigned not null, 
	title varchar(100) not null, 
	ticketDesc text not null, 
	ticketStatus varchar(20) not null, 
	ticketPriority varchar(20) not null, 
	owner int unsigned default 0,

	milestoneId integer,
	
	ticketType varchar(40),		
	duedate datetime,
	est varchar(20),
	
	displayOrder integer default 0,
	
	activityCount integer default 0,
	commentCount integer default 0,
	attachmentCount integer default 0,
	viewCount integer default 0,
	watcherCount integer default 0,
	tags text,
	notifications text,

  	createdBy  int unsigned,
  	modifiedBy  int unsigned,
  	modifiedOn datetime,
  	createdOn datetime,

	companyId int unsigned not null, 
	KEY title (title),
	KEY milestoneId (milestoneId),
	KEY ticketStatus (ticketStatus),
	KEY ticketPriority (ticketPriority),
	KEY owner (owner),
	KEY duedate (duedate),
	KEY createdBy (createdBy),
	KEY createdOn (createdOn),
	FOREIGN KEY (projectId) REFERENCES Project(id),
	FOREIGN KEY (companyId) REFERENCES Company(id)
);


create table if not exists TicketHistory (
	id int unsigned auto_increment primary key,
	historyDate datetime not null,
	historyType varchar(20), 
	historyDesc text, 
	comments text, 
	userId int unsigned,
	projectId int unsigned not null, 
	ticketId int unsigned not null, 
	companyId int unsigned not null, 
	FOREIGN KEY (ticketId) REFERENCES Ticket(id),
	FOREIGN KEY (projectId) REFERENCES Project(id),
	FOREIGN KEY (userId) REFERENCES User(userId),
	FOREIGN KEY (companyId) REFERENCES Company(id)
);

create table if not exists Activity (
	id int unsigned auto_increment primary key,
	activityDate datetime not null, 
	activityDesc text not null,
	activityType varchar(20), 
	ticketId integer, 
	projectId integer,
	userId int unsigned not null, 
	
	companyId int unsigned not null, 
	FOREIGN KEY (companyId) REFERENCES Company(id),
	KEY ticketId (ticketId),
	KEY projectId (projectId),
	KEY userId (userId),
	KEY activityDate (activityDate)	
);

create table if not exists TicketTag (
	ticketId int unsigned, 
	projectId int unsigned,
	tag varchar(40),
	companyId int unsigned, 
	FOREIGN KEY (companyId) REFERENCES Company(id),
	FOREIGN KEY (ticketId) REFERENCES Ticket(id),
	FOREIGN KEY (projectId) REFERENCES Project(id),
	primary key (ticketId, tag)
);

create table if not exists TicketNotification (
	ticketId int unsigned, 
	projectId int unsigned,
	userId int unsigned,
	primary key (ticketId, userId), 
  companyId int unsigned, 
  FOREIGN KEY (companyId) REFERENCES Company(id),
	FOREIGN KEY (userId) REFERENCES User(userId), 
	FOREIGN KEY (ticketId) REFERENCES Ticket(id)
			
);


create table if not exists Attachment (
	id int unsigned auto_increment primary key,
	ticketId integer, 
	ticketHistoryId integer default 0,
	projectId integer,
	messageId integer,

	fileName varchar(100) not null, 
	title varchar(100) not null, 
	contentType varchar(50), 
	contentSize integer,
	
	isImage tinyint(1) default 0 not null, 
	location varchar(200), 

  createdBy  int unsigned,
  modifiedBy  int unsigned,
  modifiedOn datetime,
  createdOn datetime, 
  companyId int unsigned, 
  	KEY ticketId (ticketId),
  	KEY ticketHistoryId (ticketHistoryId),
  	KEY projectId (projectId),
  	KEY messageId (messageId),
	FOREIGN KEY (companyId) REFERENCES Company(id)
); 

create table if not exists ShortMsg (
	id int unsigned auto_increment primary key,
	title varchar(100),
	msg text, 
	msgType integer default 0, 
	msgId int unsigned,
	projectId int unsigned,
	companyId int unsigned,

	commentCount integer default 0, 
	attachmentCount integer default 0,

  	createdBy  int unsigned,
  	createdOn datetime,				

	FOREIGN KEY (projectId) REFERENCES Project(id),
	FOREIGN KEY (companyId) REFERENCES Company(id)
); 
	
create table if not exists Page (
	id int unsigned auto_increment primary key,
	title varchar(100),
	pageContent text, 
	displayOrder integer default 0,
	
	pageType integer default 0, 
	projectId int unsigned default 0,
	
  	createdBy  int unsigned,
  	modifiedBy  int unsigned,
  	modifiedOn datetime,
  	createdOn datetime,
  	
	companyId int unsigned not null, 
	FOREIGN KEY (companyId) REFERENCES Company(id),
	KEY projectId (projectId)

);

create table if not exists Invite (
	id int unsigned auto_increment primary key,
	email varchar(50),
	subject varchar(100),
	uuid varchar(100),
	message text, 

  	invitedOn datetime,
  	
	companyId int unsigned not null, 
	FOREIGN KEY (companyId) REFERENCES Company(id),
	KEY uuid (uuid)
);

insert into  Company values (
	1, 
	0, 
	'51PM Test Company',
	'Greatest Software Company on the Plannet', 
	'http://www.51pmapp.com',
	'http://www.51pmapp.com', 	
	'51pm',
	'{ADMIN_EMAIL}',
	'1800-51pmapp', 
	'1 Pmapp Test Drive', 
	'City', 
	'ST',
	'88888',
	'Lucky Me',
	0,
  	0,
  	'{TIME_STAMP}',
  	'{TIME_STAMP}'
); 

insert into  User values (
	1,  
	'Superadmin', 
	'ROLE_ADMIN,ROLE_SITE_ADMIN', 
	0,
	'Be the best!', 
	'055bc5109fefc2354c8ec28f52e6a9e9',  -- lovepm88 
	null, 
	'System Administrator',
	'{ADMIN_EMAIL}', 
	null, 
	null,
	1,	
	null, 
	null, 
	null,

	null,
	null,
	null,

	0,
	0,
	'{TIME_STAMP}',
	'{TIME_STAMP}',

	1 
); 
