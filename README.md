# Student Registration App

A simple Jakarta Servlet web application for registering and viewing students, built with Maven.

## Prerequisites
- Java JDK 11+
- Apache Maven
- Apache Tomcat 10+ (or any Jakarta EE 10 compatible server)
- MySQL Server

## Database Setup
1. Open your MySQL client (Workbench, Command Line, etc.).
2. Run the script located in `src/main/resources/schema.sql`:
   ```sql
   source src/main/resources/schema.sql;
   ```
3. Update database credentials in `src/main/java/com/example/util/DBUtil.java` if your password is not `password`.

## Building the Project
Navigate to the project root directory and run:

```bash
mvn package
```

This will generate `studentApp.war` in the `target/` directory.

## Deploying
1. Copy `studentApp.war` to the `webapps/` folder of your Tomcat installation.
2. Start Tomcat.
3. Access the application at: `http://localhost:8080/StudentApp/`

## Project Structure
- `src/main/java`: Servlet, Model, and Utility classes.
- `src/main/webapp`: HTML, JSP, and web.xml.
- `src/main/resources`: SQL schema script.
