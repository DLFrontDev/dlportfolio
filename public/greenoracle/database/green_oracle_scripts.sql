delete from article where id_article = 4;
delete from comments where id_user = 0;
delete from user where id_user = 0;
delete from tags where tag = 'environment|innovati';
delete from newsletter where email='ther@dummy.com';

INSERT INTO article VALUES
(0, 'Innovation', 'Former Google Maps genius to build the world’s largest plant library', 'Beverly Mitchell', '2.jpg', '3', now());

INSERT INTO article VALUES
(1, 'Environment', 'Dallas is building america’s biggest urban nature parks', 'Beverly Mitchell', '1.jpg', '0', now());

INSERT INTO article VALUES
(2, 'Eco Living', 'Grow an avocado tree from an avocado pit', 'Beverly Mitchell', '1.jpg', '0', now());

INSERT INTO article VALUES
(3, 'Innovation', 'Donald Trump selects Elon Musk to serve as strategic advisor', 'Beverly Mitchell', '1.jpg', '0', now());


INSERT INTO article VALUES
(4, 'Innovation', 'Russian scientists turn nuclear waste into diamond batteries', 'Beverly Mitchell', '1.jpg', '0', now());

select id_article, tags, title, author, header_image, comment_nr, date_created from article where id_article=0;

UPDATE article SET tag = 'Eco Living' where id_article=2;
UPDATE user SET id_user = '0' where id_user=1;

select * from article;

select * from user;

select * from newsletter;

select * from comments;

select * from tags;

select email from newsletter where email='dummy@domain.com';

insert into newsletter values
('dlcrazygamer@gmail.com', 0);

insert into tags values
('environment');
insert into tags values
('innovation');
insert into tags values
('eco living');
insert into tags values
('politics');

insert into user (id_user, username, password, email, date_created) values (1, '1', '1', '1', now());

select * from user;

select * from article;

select * from comments;

select id_comments from comments where id_comments=0 and article_id_article=0;

select id_user from user where username='Tester';

select * from newsletter where id_user=0;

select news_tags from user where id_user=0;

select id_comments from comments where article_id_article=0;

insert into comments values (1, 0, 0, 'Dickbutt', now());

insert into comments values
(0, 1, 0, 'Dickbutt', now());

insert into comments values
(1, 0, 0, 'Comment n2', now());

insert into comments values
(2, 0, 0, 'Comment n3', now());

insert into comments values
(3, 0, 0, 'Comment n4', now());

select  count(c.id_comments)
from user u, comments c, article a
where c.user_id_user = u.id_user and c.article_id_article = a.id_article
and a.id_article = 0;

UPDATE article SET comment_nr = (select  count(c.id_comments)
from user u, comments c, article a
where c.user_id_user = u.id_user and c.article_id_article = a.id_article
and a.id_article = 0) where id_article=0;

select  id_article, text, c.date_created comment_date, id_user, username
from user u, comments c, article a
where c.user_id_user = u.id_user and c.article_id_article = a.id_article
and a.id_article = 0
ORDER BY comment_date desc;

SELECT id_article, title, header_image, date_created FROM article
where tag = "Innovation" AND id_article != 0
ORDER BY RAND()
LIMIT 4;

select  id_article, tag, title, header_image
from article
ORDER BY date_created desc
limit 10;

select  id_article, tag, title, header_image, comment_nr, date_created
from article
ORDER BY date_created desc
limit 10;

select  id_article, tag, title, header_image, comment_nr, date_created
from article
where tag='Innovation'
ORDER BY date_created desc
limit 10 offset 0;

select  id_article
from article
where tag='Innovation';

select  id_article, tag, title, header_image, comment_nr, date_created
													from article
													where title like '%elon musk%';

select id_article, tag, header_image FROM article where tag = "Innovation" AND id_article != "0" ORDER BY RAND() LIMIT 4;
	

select id_article, tag, header_image FROM article where tag = 'Innovation' AND id_article != 0 ORDER BY RAND() LIMIT 4;
