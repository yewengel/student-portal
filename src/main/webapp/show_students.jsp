<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib uri="jakarta.tags.core" prefix="c" %>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>All Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>Registered Students</h2>

    <c:if test="${not empty errorMessage}">
        <p style="color:red; text-align:center;">${errorMessage}</p>
    </c:if>

    <table border="1">
        <thead>
        <tr>
            <th>ID</th>       <!-- Added ID -->
            <th>Name</th>
            <th>Email</th>
            <th>Year</th>
        </tr>
        </thead>
        <tbody>
        <c:forEach var="student" items="${studentList}">
            <tr>
                <td>${student.id}</td>          <!-- Display id -->
                <td>${student.name}</td>
                <td>${student.email}</td>
                <td>${student.year}</td>
            </tr>
        </c:forEach>
        <c:if test="${empty studentList}">
            <tr>
                <td colspan="4" style="text-align:center;">No students registered yet.</td>
            </tr>
        </c:if>
        </tbody>
    </table>

    <div class="back-link">
        <a href="index.html">Register New Student</a>
    </div>
</div>
</body>

</html>
