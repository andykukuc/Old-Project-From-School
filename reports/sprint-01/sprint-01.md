# Sprint-01 Report

## Team Number 04
* Robert Bacius, Project Manager
    * Prepared Sprint #1 report
    * Set the teams Sprint #1 Goals
    * Obtain technology resources including LTEM/BLE beacon provider
    * Drove whiteboard sessions to confirm technology architecture and user experience with our team
    * Log team tasks in Trello to hold accountability per team member

* Andy Kukuc, Developer
    * Articulated the user experience in team UX whiteboard session
    * Moved white board notes into wire frames
    * Identified our target users

* Jacob Krupa, Infrastructure and IT
    * Created the database schema
    * Began Vagrant & Packer scripts
    * Developed IT infrastructure to run the Apache database server

* Geldi Omeri, UI/UX Designer
    * Started development on the skeleton web application site (login & site navigation)
    * Created intricate logic for temperature and acceleration constraint notifications
    * Developed development assumptions as we progress in our project in the Developer Report

* John Collins, Jr. Developer
    * Highlighted possible security risks
    * Developed code and security assumptions in the Developer and Security Assumptions Report

## UI/UX Report

From a visual perspective, we plan to have an administrator dashboard with the following views:

* Login Page
    * The login page will have a simple username and password login setup for authorized users
    * Administrator login credentials will have access to the "Beacons" screen

* Map
    * Contains a visual map used to track the organizations trucks movement via GPS (use of MapBox API to display map and truck GPS coordinates)
    * Displays vital data of internal happenings inside trucks delivering food including temperature and acceleration

* Notifications
    * Display fields including full name, email and phone number to dynamically turn on and off users to receive notifications if data constraints are broken
    * The notification page will allow users to set notifications to be sent to their phones or emails whenever a preset threshold has been passed. It will allow for multiple notifications to be set at once. Switches will be utilized to indicate when a notification setting is active.

* Settings
    * Will have basic account management settings such as password reset, change email, change phone number, modify distance units (km vs miles, Celsius vs Fahrenheit, etc.), modify linked accounts (adding and removing).

* Beacons (Admin only view)
    * Only will be visible to administrator accounts. Information regarding the beacons and what trucks they are tied to will be displayed here. This is also where the administrators set the connections between the beacons and the trucks before they begin their routes.

### Color Scheme:
* Primary Color(s) = #404E5A, #4E5D6A
* Secondary Color = #7BED8D


### Font:
* Primary Font = Source Sans Pro

## Developer Report

At the moment, we plan to use the web stack "LAMP" this includes the technology:

* Linux
* Apache
* MySQL
* PHP (with a framework)

Development will be broken up into segments including:

* LTE Beacon Firmware
    * We will use our beacon provider (Estimote) cloud service to create firmware on our beacons using their Javascript Framework
    * This includes rules such as what to do when beacons are in range and when to send GPS, Temperature, Acceleration.

* Back End
    * Back end development includes creating logic for when notifications are sent
    * API ingestion from Estimote beacon data and storage onto our database

* Front End
    * We plan to use basic HTML5 / CSS3 for front end development
    * MapBox API will be used to display our map and ingest Latitude / Longitudes to display trucks on such map
    * Two front end views include User & Admin (see user stories)

## IT Infrastructure Report

The IT infrastructure is being based on and developed on the decision of using the LAMP structure of Linux, Apache, MySQL, and PHP.

### Current set up includes:
* Base Packer script to create an Ubuntu vagrant box
    * This vagrant box is used to host our Apache web server along with MySQL through PHP scripts on the Apache web server
    * The vagrant box itself is currently in host-only mode, so the host computer outside the vagrant box is able to view the website and look through it, however, this will change with time and it will be viewable either through a private network and/or publicly.

* Currently, the web server has a firewall enabled, however, the firewall rules are commented out until the project is nearing the stage of more public access and will require these rules for security.

* The web server, currently, contains basic PHP to demonstrate that the web server can connect to the MySQL database and can query the database and its tables and data
    * The database schema has been created and designed and is constantly being updated for our specifications, however, the database server has a test database to test our IT functionality.

### Future Plans:
* For our future needs, the IT infrastructure will be updated to meet our security requirements and our functional needs. We plan to set up the actual database we need to store the data we will actually be holding based our designed database schema. The Apache server will also start to host more and more of the HTML, PHP, and/or Javascript webpages and scripts that we develop.


## Developer and Security Assumptions

### Future plans for security implementations as we develop include:

* Data Encryption standard, Sha256
    * All of our database systems and account data will be encrypted using sha256

* Login authentication system, Google Oauth2
    * Oauth2 would be an easy to implement login system for our project as it is basically a drop in solution for a login manager.
    * The specific manager that we will use is OKTA, as it is an easy drop in solution that would also enable us to have 2 factor authentication for our logins.

* SSL, HTTPS, and Certificates
    * We need to obtain an SSL certificate in a cost friendly way once we decide on a domain name. This means HTTPS will be a goal towards to middle/end of the project


## User/Admin/Anonymous Story

### Product Summary
By using BLE beacons that connect via LTE, we will place them inside trucks that transport perishable items that must remain inside certain temperature ranges. We can use these beacons to track data such as temperature, acceleration of the truck, and GPS location of the truck. This comes to use for logistical tracking to know when trucks arrive and leave certain locations by using GPS and beacons when trucks arrive in range of destinations.


### User Story
* Users of the web application include logistical tracking company employees, grocery store owners, distribution center employees
* We do NOT allow users to view the "Beacons" screen since this is where we can add the beacons they have purchased into their organization dashboard
* Once users sign in they can view the following dashboard items
    * Map
    * Notifications
    * Settings


### Admin Story
* The ONLY admins are internal employees who add beacons after purchase of the web application fees and beacon costs
* Once admins sign in they can view the following dashboard items
    * Map
    * Notifications
    * Settings
    * Beacons
        * This is where we can add their organization beacons via beacon ID to link such to their account for tracking purposes


### Anonymous Story
* Users MUST have an account to use our SaaS Product


## Atomic Goals for Sprint-02

* Front End
    * Complete the skeleton website based on our finished screens
    * Display our map from MapBox under "Maps" screen

* Back End
    * Get the mySQL server up and running and set up access to it so we can start displaying notifications and beacons being tracked
    * Set up Estimote API and attempt attempt to pull data from live beacons
    * Set up our login manager

* Hardware
    * Receive LTE beacons via mail (2/17)
    * Set up BLE Beacons on our developer account
    * Begin development on LTE Beacon firmware
        * Create custom code to take BLE beacon data and pass to the LTE beacon



