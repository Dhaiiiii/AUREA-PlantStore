Project Setup Guide

Follow these steps to run the project on your local machine.

1. Install XAMPP

Make sure you have XAMPP installed on your computer.

Start the following services:

Apache

MySQL

2. Place the Project in htdocs

Move the project folder to the XAMPP htdocs directory.

Example:

C:\xampp\htdocs\AUREA-PlantStore

3. Import the Database

Open MySQL Workbench and do the following:

Go to Server → Data Import

Select Import from Self-Contained File

Choose the file:

aurea_store.sql

Click Start Import

This will create the database and tables automatically.

4. Check Database Connection

Open the file:

db.php

Make sure the connection settings match your MySQL configuration.

Example:

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "aurea_store";

If your MySQL has a password, update the $password field.

5. Run the Project

Open your browser and go to:

http://localhost/AUREA-PlantStore

The website should now run locally.
