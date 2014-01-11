DROP DATABASE christianland;
CREATE DATABASE christianland;
USE christianland;
CREATE TABLE admin (
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL UNIQUE,
	password VARCHAR(50) NOT NULL,
	forbidden INT NOT NULL,
	email VARCHAR(50) NOT NULL,
	phone VARCHAR(30) 
);
	
/** initial the table **/
INSERT INTO admin(name,password,forbidden,email,phone) VALUES('Bruce','bruce',0,'bruce.v.shung@gmail.com','15071268763');	
INSERT INTO admin(name,password,forbidden,email) VALUES('Shaun','shaun',1,'xiangchao@whut.edu.cn');
/** test the admin table **/	
SELECT * FROM admin;



/** create user table **/
CREATE TABLE cl_user(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL UNIQUE,
	nickname VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
	forbidden INT NOT NULL,
	email VARCHAR(50) NOT NULL,
	coin INT NOT NULL,
	regTime DATETIME NOT NULL, /** registration time **/
	lastActiveTime DATETIME NOT NULL, /** last time he login **/
	age INT,
	gender VARCHAR(10),
	location VARCHAR(100),
	grade VARCHAR(100), /** the grade of the user in the college or university **/
	teacher VARCHAR(100),
	school VARCHAR(100),
	message INT NOT NULL, /** the number of the messages of the user **/ 
	icon VARCHAR(100) NOT NULL /** the url of the icon,if none,set the default one as default.jpg **/ 
);

/** intial the user table **/
INSERT INTO cl_user(name,nickname,password,forbidden,email,coin,regTime,lastActiveTime, age, gender, location, grade,message,icon) VALUES('Bruce','超哥','bruce',0,'xiangchao027@yahoo.de',0,'2014-01-01 01:20:20','2014-01-01 01:20:20',20,'male','武汉市洪山区','Undergraduate',0,'default.jpg');
INSERT INTO cl_user(name,nickname, password,forbidden,email,coin,regTime,lastActiveTime, age, gender, teacher,school,message,icon) VALUES('Shaun','超哥','shaun',0,'bruce.v.shung@gmail.com',0,'2014-01-01 12:20:20','2014-01-01 12:20:20',20,'male','Professor Huang Zhangchan', '江夏一中,武汉理工大学',0,'shaun.jpg');

/** test the user table **/
SELECT * FROM cl_user;

/** create super administrator **/
CREATE TABLE super_admin(
	name VARCHAR(30) NOT NULL,
	password VARCHAR(100) NOT NULL
);
/** intial the super_admin info **/
INSERT INTO super_admin(name,password) VALUES('Bruce','bruce');

/** test the super_admin table **/
SELECT * FROM super_admin;

/** request table of the administration to the super_admin **/
CREATE TABLE req4admin(
	id INT PRIMARY KEY AUTO_INCREMENT,
	admin_id INT NOT NULL UNIQUE,
	mark INT NOT NULL /**if the record is read already,set it 1,else set it 0**/
);
/** for this table , we can't push initial records coz this is
 a relationship sheet which enrich itself only be the admin users'
 request **/

/** create the Friend Relationship Table **/
CREATE TABLE friendship(
	id INT PRIMARY KEY AUTO_INCREMENT,
	u1id INT NOT NULL,
	u2id INT NOT NULL,
	close INT NOT NULL /** 1-10, describe how close the two are. **/
);

/** let's build an example **/
INSERT INTO friendship(u1id,u2id,close) VALUES('Bruce','Shaun',8);

/** test the friendship **/
SELECT * FROM friendship;

/** create reply table **/
CREATE TABLE reply(
	id INT PRIMARY KEY AUTO_INCREMENT,
	si INT NOT NULL,
	ti INT NOT NULL,
	mark INT NOT NULL, /** if 0 not read, 1 read already **/
	time DATETIME NOT NULL,
	display INT NOT NULL, /** if 0 not show, else show **/
	content VARCHAR(1200), /** the content of the reply **/
	thread INT NOT NULL /** the id of the thread which this reply deserves to remain **/
);

/** initial the reply table **/
INSERT INTO reply(si,ti,mark,time,display,content,thread) VALUES(1,2,0,'2014-1-10 10:20:10',1,'Bruce Shaun has been kept in a jail, U guys catch the time to see him for the last view.',10);
/** A TEST ON REPLAY TABLE **/
SELECT * FROM reply;

/** create the thread table **/
CREATE TABLE thread(
	id INT PRIMARY KEY AUTO_INCREMENT,
	topic VARCHAR(1200) NOT NULL,
	display INT NOT NULL, /** 0 not show, 1 show **/
	launcher INT NOT NULL, /** THE USER TO LAUNCH THE THREAD **/
	launchTime DATETIME NOT NULL, 
	type INT NOT NULL, /** 1-6 , each number present a type of the part of the forum **/
	replyCount INT NOT NULL /** how many times the thread has been viewed **/
);

/** initial the thread table **/
INSERT INTO thread(topic,display,launcher,launchTime,type,replyCount) VALUES('Killing',1,2,'2014-1-1 12:20:20',3,0);

/** show the table function condition **/
SELECT * FROM thread;





