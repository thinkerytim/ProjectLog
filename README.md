# Proj3ct Log 3
Copyright (C) 2009 - 2014 The Thinkery, LLC. [thethinkery.net](http://thethinkery.net).
 
![alt text](https://github.com/thinkerytim/ProjectLog/blob/PL30/site/assets/images/projectlog_logo.png "Projectlog Three")

## Welcome to Project Log 3

Project Log is a project management component for the Joomla! content management system. 

## PL3 Beta Instructions:
------
**Please make sure to read the `beta` instructions and information here to avoid confusion!**

* Step 1 - Click on the 'Download Zip' button above and save the file on your computer.
* Step 2 - Install zip file via your Joomla 3.x installer
* Step 3 - Follow the link from the installation message to the component configuration and **save** configuration!
* Step 4 - Create a new Project Category from the admin panel->Projectlog->Categories panel
* Step 5 - On installation, a sample project, log, and document are created. Edit the sample project and assign your new category to the sample project.
* Step 6 - Create a menu item to a PL view via your admin menu manager. Any view will do.


## Some Notes:

Project Log 3 has many significant improvements from the Joomla! 1.5 version. Some of these new features include:

### More ACL control
* Using Joomla!'s built in ACL access control levels, administrators can decide who should be able to create, edit, delete or even edit the states of projects, logs, and documents.
* Set the Permissions in the global **component** configuration by clicking on the 'Options' toolbar button in any ProjectLog admin view
* Adjust the basic permissions here. Log and document permissions can also be changed at the project level for more granular control.

### Built in multi-language functionality
* PL3 uses Joomla!'s built in language control to easily create multi-lingual content for all projects, logs, and documents
* Associations - PL3 also supports associations for all projects and logs when enabled. To enable associations, enable the 'system->language filter' plugin via your admin plugins manager
* Filter all content by language and easily switch between languages via the front end

### In Progress Development
Remember, this is only a beta version and is not intended to be used on production sites! It is only for feedback purposes. Please report any
bugs found to info @ thethinkery.net and we'll get them fixed! 

### Beta Version Notes:
* Documentation is not yet written, but it's pretty straight forward if you're comfortable with Joomla!
* We will be working on documentation and tutorial videos soon as well as finishing the front end management.
* Routing still in progress - may have problems with SEF urls enabled 
* Admin project approval functionality not yet build - need to add post save hook to check against settings, set to unapproved, and send admin and email with approval link
* We appreciate feedback!!
