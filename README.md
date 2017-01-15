# organaizer


Features:
--------------------------------------------------------------------
 - multiple users can use this application
 - user authentication
 - add/edit/delete contact with validation
 - view contacts with pagination (5 contacts per page)
 - shows four public photos from Flikr based on contact interest
 - when user has few interest gets set of photos using random interest

Technical Features:
--------------------------------------------------------------------
DATABASE
 - innoDB model
 - Foreign key integrity
 - Used View to combined data from different tables
 - Used function to get password hash

BACKEND
 - Implemented MVC model
 - mysqli extension to work with database
 - All requests to flickr cached
 - Use different configuration for different environment
 - Prepared statements
 - Database transaction
 - Protection against CSRF attack in add/edit/delete forms

FRONTEND
 - uses JQuery and Blueprint CSS framework
 - uses AJAX to get flickr photos.
