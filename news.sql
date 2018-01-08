#新闻测试表
create table news(
news_id int unsigned  not null primary key auto_increment,
Title varchar(255),
Description varchar(255),
PicUrl  varchar(255),
Url    varchar(255)
);
insert into  news (Title,Description,PicUrl,Url) values  ('test1','test1测试内容1',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test2','test1测试内容2',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test3','test1测试内容3',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test4','test1测试内容4',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');
insert into  news (Title,Description,PicUrl,Url) values  ('test5','test1测试内容5',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test6','test1测试内容6',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test7','test1测试内容7',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');

insert into  news (Title,Description,PicUrl,Url) values  ('test8','test1测试内容8',
'http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0',
'http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd');


#获取客户信息
create table clientuser(
openid char(100) primary key,
nickname  varchar(50),
sex  varchar(10),
city  varchar(50),
province  varchar(100),
headimgurl  varchar(255),
utime  int,
status int
);
#消息列表
create table message(
msg_id int primary key auto_increment,
openid char(100),
msg varchar(255),
who int default 0,
utime int,
mtype varchar(30)
);

#用户列表
drop table if exists wuser;
create table wuser(
	openid char(100) not null primary key,
	groupid int not null default 0,
	subscribe int not null default 0,
	nickname CHAR(50) not null  DEFAULT '',
	sex int not null  default 0,
	city char(50) not null default '',
	country char(50) NOT NULL DEFAULT '',
	province char(50) NOT NULL DEFAULT '',
	headimgurl char(255) not null default '',
	subscribe_time int not null default 0
);

#媒体列表
drop table if exists media;
create table media(
	id int not null auto_increment primary key,
	filename char(50) not null default '',
	rtype char(10) not null default '',
	media_id char(200) not null default '',
	created_at int not null default 0
);