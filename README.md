N-GATE 🇳🇵

N-GATE (Nepal Government Access & Trusted Exchange) is a web-based e-governance service request and tracking system developed as a B.Sc. CSIT semester project.

The idea behind N-GATE is simple: citizens can access government services, submit service requests, and track their progress from one place. Government agencies can review and process those requests, while administrators can manage users, agencies, services, and other system information.

✨ Features

👤 Citizen registration and login

🔐 Role-based authentication and access control

🏛️ Browse available government services

📝 Submit service requests

📌 Track request status and history

🏢 Agency-side request management

🔄 Update request statuses

📋 Maintain an audit history of status changes

👨‍💼 Admin dashboard for managing users, agencies, and services

🗃️ CRUD Operations

CRUD operations are used throughout the system to manage application data:

Create — Add users, agencies, services, and service requests

Read — View users, services, agencies, and requests

Update — Update user, service, agency, and request information where applicable

Delete — Remove records where applicable

🛠️ Tech Stack

PHP — Backend

MySQL — Database

HTML & CSS — Frontend

JavaScript — Client-side functionality

Apache / XAMPP — Local development environment

Git & GitHub — Version control

👥 User Roles
👤 Citizen

Citizens can:

Register and log in

View available government services

Submit service requests

View submitted requests

Track request status and history

🏢 Government Agency

Agency users can:

Log in to the system

View requests related to their services

Review request details

Process requests

Update request statuses

View request history

👨‍💼 Administrator

Administrators can:

Access the admin dashboard

Manage users

Manage government agencies

Manage government services

View system information and request statistics

🔄 Request Flow
Citizen
   ↓
Select Service
   ↓
Submit Request
   ↓
Agency Reviews Request
   ↓
Status Updated
   ↓
Audit Log
   ↓
Citizen Tracks Progress


A request can move through statuses such as:

Pending → Processing → Approved → Completed
                    ↘ Rejected

🔐 Security

The project includes basic security mechanisms such as:

Password hashing

Session-based authentication

Role-based access control

Request authorization

Input validation

Prepared SQL statements

Request status audit logging

🎓 About the Project

N-GATE was developed as a B.Sc. CSIT semester project to gain practical experience in web application development, database design, authentication, authorization, CRUD operations, and e-governance concepts.

The project demonstrates how a centralized system can be used to organize government service requests and provide citizens with better visibility into the processing of their requests.

Note: N-GATE is an academic prototype and is not an official Government of Nepal platform. The government agencies and services included in the project are used for demonstration purposes only.

⭐ Thanks for checking out N-GATE!
