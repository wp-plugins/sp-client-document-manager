=== Plugin Name ===
Contributors: smartypants
Donate link: http://smartypantsplugins.com/donate/
Tags: distribution of documents,project management, document manager,document organization, file uploader, customer file manager, customer files,
Requires at least: 2.0.2
Tested up to: 3.4.2
Stable tag: 1.3.6

SP CLIENT DOCUMENT MANAGER ALLOWS FOR SIMPLIFIED CUSTOMIZATION. Customize, import, organize, and share one or multiple documents all with one-click technology.

== Description ==

A sophisticated document manager the plug-in provides assurances that the user has complete control over the flow of information. Businesses utilizing the SP CDM are able to maintain document organization, manage client documents and accounts, control individual documents, and select specific distribution of documents all in an easy to manage process. 
This new SP plug-in also demonstrates how quickly a business can take hold of their interactions with clients, vendors, and all in between.  With a straight-forward layout, access to template modifications and easy to manage features; clients can add and modify projects.


We also now offer a premium version; please check out our website for more information: 
http://smartypantsplugins.com/sp-client-document-manager/

Now works with WordPress Multi Site!

**[Click here to try out a demo](http://smartypantsplugins.com/client-upload-demo/ "Click here to try out a demo")**

Login with:  user: test   password: test

**Client Side Features**

* Clients upload files on their own personal page
* Clients can create or add to existing projects
* Ability to choose multiple files
* Delete uploaded documents
* Automatically zip multiple files
* User login with help from "Theme my login"
* Ability to translate plugin to multiple languages using the .po files.
* Search by file name
* Ability to allow deleting and renaming of projects

**Administrator Side Features**

* Complete control on who can access specific files
* Notification via email when a client uploads a file
* Add files and documents to client page and projects
* Download file archive of a user
* Custom Naming of files
* Create multiple upload locations
* 50 latest uploads on main plugin page
* Force downloads of file 
* Delete confirmation with custom notification
* Thank you confirmation with custom notification
* Add staff, supplier's, vendors, sub-contractors or partner's so you can distribute the files to other people
* Attach file or send the file as a link
* Projects allow you or the users to create projects to store files in
* Allow the user to create projects
* Ability to add files to any user
* Download all the files of a project in a single zip file

**Premium Features**


* Add custom fields to your client upload form! Sort the fields, view them in the file view page or in admin
* Search by tags
* Categories allow an admin to designate categories for the user to select, for example a print company could use categories as statuses (Mockup, Draft and Final)
* Custom Email Notifications
* Full Support through email or Skype
* Auto deletion of files based on a time you set.
* Thumbnail view mode for a windows explorer type look and feel.
* Automaticly create thumbnails of pdfs and psds (must have imagemagick installed on server)
* File versioning system, don't lose old versions 
* Addon packs available for more features!
* For more information check out the plugin page http://smartypantsplugins.com/sp-client-document-manager/

**Current Languages**

* English
* French
* German
* Italian

**Demo**

[youtube http://www.youtube.com/watch?v=cEhzmhx9jt8]


== Installation ==

* Upload the plugin to your plugins folder
* Activate the plugin
* Install "Theme my login" plugin for seamless user experience
* Create a new page and enter the shortcode [sp-client-document-manager]  
* Go to the plugin admin page and click settings to configure the plugin  (VERY IMPORTANT!)  
* If you're using the premium version please upload the zip archive in the settings area. 

== Frequently Asked Questions ==

= How come I'm getting a 404 error? =

This could be one of two reasons, either you did not install theme my login or you're running wordpress in a directory in which you can go to settings and set the directory for wordpress.

= Why am I just getting a spinning circle and no content on my uploader? = 

This is usually because you are using a theme that converts new lines into paragraphs. To fix this wrap the short code in raw tags. Example: [raw][sp-client-document-manager] [/raw]
== Screenshots ==

1. This is the client view
2. This is the file view which also shows the premium revision system
3. This is the admin page view
4. Admin file uploader to upload a file for a user
5. Settings page
6. Form builder to add custom forms (premium)
7. Group manager to allow multiple user manage the same files (premium)
8. Project editor
9. Upload a file

== Changelog ==

= 1.0.0 =
* Created first version
	
= 1.0.2 =
* Database bug fix
* Small zip error
	
= 1.0.3 =
* Error with file tree fixed.
* There was an error with wordpress in a folder, now in the settings you have to set the directory if you have wordpress in a folder.
	
= 1.0.4 =
* increased the upload size for php to 1000mb
	
= 1.0.7 =
* Fixed a few bugs and added auto file deletion to the premium version. Now you can set how many days a file should exist in the system.

= 1.0.9 =
* Update to enable localization. Please translate the language files and get them back to us so other users can use them!

= 1.1.1 =
* Projects now come with free version! Create projects and folders for your files
* Premium users can now view thumbnails and
* Thumbnails created from psds and pdfs

= 1.1.2 =
* Premium users can now add fields to the upload form, add textboxes,selectboxes or textareas!

= 1.1.3 =
* Force download works better with mime types!

= 1.1.4 =
* Admin now has ability to add files to any user
* Added German Translation

= 1.1.7 =
* Removed additional project function that was causing conflicts with the free version
* Added the ability to give the plugin a company name for emails for both free and premium.
* Added the ability for admins to form groups which allows group members to share all  files and projects! Premium only

= 1.1.8 =
* Works with WordPress multsite

= 1.1.8 =
* Fixed include issue with some versions of php

= 1.2.0 =
* Fixed an issue with wordpress running in its own directory. Files link properly now!

= 1.2.1 =
* Added the ability to search for files
* Fixed a few bugs
* added the ability for premium users to use tags (download latest premium version in client area)

= 1.2.2 =
* Fixed a javascript error that was causing conflicts with other plugins
* Removed the filetree jquery plugin for a more homebrewed file view system.
* Fixed a function include error
* Added the search feature to search files!

= 1.2.3 =
* Fixed issue with uploading

= 1.2.5 =
*Fixes a major issue with the admin wordpress uploader

= 1.2.6 =
* Added ability to set admin and user emails with custom template tags! Check out the settings area

= 1.2.7 = 
* Addded the ability to delete projects
* Added the ability to edit project names
* when adding projects it now uses ajax to eliminate page refresh
* Bug fixed with multi site
* Bug fixed with admin email
* Bug fixed with User email
* increased by eliminating some scripts that are not being used anymore

= 1.2.8 = 
* Fixed a bug with multi user
* Fixed a bug involving blank download links in the email
* Add more features for groups (premium)
* Only file/project owners can delete their own files by default, the admin has the option to over-ride this functionality and allow all users of a group to modify /files/projects

= 1.2.9 = 
* Major updates seperating premium and giving its own plugin, this will reduce errors when updating the free version!
* Added sorting for file list view

= 1.3.1 =
*Fixed errors with UTF-8
*Fixed ie9 compatibility

= 1.3.2 =
*Added ability to make projects mandatory so users.
*The form now remembers what the user chose last for a project and keeps that project selected.

= 1.3.5 =
* You can now turn off the ability for users to upload files
* Fixed an issue with projects that was not moving client files when changing ownership of a project

== Upgrade Notice ==

= 1.0.5 =
Major fixes to bugs that were found during our initial release

= 1.0.7 =
Not a major issue to upgrade unless you're a premium user.

= 1.0.8 =
Added localization for multiple languages

= 1.1.0 =
Bug fixes, added thumbnail mode for premium users.

= 1.1.1 =
Projects now come with free version! Create projects and folders for your files


= 1.2.5 = 
Major updates, new uploader and fixes to admin upload functions

= 1.2.6 =
* Added ability to set admin and user emails with custom template tags! Check out the settings area

= 1.2.9 = 
* There is a new procedure for premium, premium users please check email. You must update free plugin, download the new premium plugin and add it through the wordpress plugin manager. This will reduce errors when upgrading in the future.

= 1.3.5 =
* You can now turn off the ability for users to upload files
* Fixed an issue with projects that was not moving client files when changing ownership of a project

= 1.3.6 =
* Fixed download.php header problem