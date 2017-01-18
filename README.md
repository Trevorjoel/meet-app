# meet-app
Bugs with meet app
http://alternicom.com/meet-app/
You can login debug  with these users: 

Username: user1 
password: user1
Username: user2
Password: user2

// TO: TWISTEDWEB 123 //
Hi mate, I'm an amateur web dev, been at it 1 year.
I'm putting together a portfolio and including various projects in it. 
Basically I have a few projects incomplete/messy and the closing date for an application for a trainee position is 21 th Feb.
Need help.

The project I would like you to tidy is a simple social-media app that I have thrown together from various tutorials without a framework. My approach has been get it to run and move to the next challenge so this is one of a few, incomplete/ugly projects.

What you might be looking out for and fixing would be variables declared multiple times, input validation and other simple security issues. The possibility of applying simple functions  for some repetitive tasks and anything in PHP and ajax (I'm really not good at JS/AJAX) The delete button is not working on the server but ok with XAMPP localhost I think its an AJAX function. 

What I don't want you to do is mis-represent my abilities, rewrite or go much further on developing the app for me(The app is right at the edge of my abilities I would get it done but the time is the factor). Just want to tidy it up as it is. I will style it with bootstrap.


PHP/SQL/Db

1)
Conversations remain in database until both users delete it but the conversation isn’t revived when  user messages again. How to revive a conversation after only 1 user has deleted it?? 
Suggestion: New column in pm table with deleted time?? if conversation has a new message later than the deleted time then show conversation up again.
Code in question: viewprofile.php Line: 121 (I think).
RECREATE ERROR:
Note: Messages can be seen when user(viewer = logged in user) is viewing their (own profile) and when looking at the user they have messaged(viewed profile). 
Do this:
User1 send message to user2. (all good)
User2 delete message.
Now message has been deleted from user2's profile but user2 can go to view user1 profile and still see their conversation) (ALL good).
User1 send user2 a new message.
The conversation does not revive on touser2's own profile page.
Thought: I added a delete time to the database for messages and I need to add a query to check if the new message is later than the parent message.

JS/Ajax/JQ
1)Message delete button not working.


2)When you are messaging from your own profile http://alternicom.com/meet-app/viewprofile.php 
the ajax function works correctly.

When you go to the same conversation (You can see your conversation on the viewed users profile) on their profile http://alternicom.com/meet-app/viewprofile.php?user_id=18  you need a page refresh, no asynchronous loading. 
Error: Uncaught TypeError: Cannot read property 'innerHTML' of null
File: main.js Line 19 
and viewprofile.php

Recreate error:

Sign in (user1), send a user2 a message. Logout sign in as user2 and reply. Login as user1 go to user2's profile and attempt to send another message... error occurs

3) When you “initiate” conversation (Go to an unmessaged users page, there is a different form, found in template_pm.php) When you post an "initial" message ajax should return the conversation form (the one you see after sending message) asynchronously.
Recreate error (not an error, need to create function):
Sign in, find a user you have not messaged send message.
4)Mark as read does not remove the Message notification (blue speech bubble) asynchronously from the header. Header.php file

Tell me what you think what you might charge. I can be more specific about particular problems, but you get the idea? 
