# meet-app
Bugs with meet app
http://alternicom.com/meet-app/
You can login debug  with these users: 
Username: Trevor 
password: Garrity
Username: 12345
Password: 12345
Username: Jenny Pass: jenny

PHP/SQL/Db
1) When one user deletes conversation it still shows on the profile of the user who didn’t delete it.
Conversations remain in database until both users delete it but the conversation isn’t revived when non deleted user messages again. How to revive a conversation??
Code in question: viewprofile.php Line: 121

JS/Ajax/JQ
1) Needs an expand and retract function on the message boxes to just show the last few messages. // COMPLETED //

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

