package com.example.servlet;

import com.example.util.DBUtil;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.SQLException;

@WebServlet("/register")
public class RegisterServlet extends HttpServlet {

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String yearStr = request.getParameter("year");

        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        if (name == null || email == null || yearStr == null || name.isEmpty() || email.isEmpty() || yearStr.isEmpty()) {
            out.println("<h3>Error: All fields are required!</h3>");
            out.println("<a href='index.html'>Go Back</a>");
            return;
        }

        int year;
        try {
            year = Integer.parseInt(yearStr);
        } catch (NumberFormatException e) {
            out.println("<h3>Error: Year must be a valid number!</h3>");
            out.println("<a href='index.html'>Go Back</a>");
            return;
        }

        try (Connection conn = DBUtil.getConnection()) {
            String sql = "INSERT INTO students (name, email, year) VALUES (?, ?, ?)";
            try (PreparedStatement stmt = conn.prepareStatement(sql)) {
                stmt.setString(1, name);
                stmt.setString(2, email);
                stmt.setInt(3, year);
                
                int rowsInserted = stmt.executeUpdate();
                if (rowsInserted > 0) {
                    // Redirect to show all students on success
                    response.sendRedirect("show_all");
                } else {
                    out.println("<h3>Error: Failed to register student.</h3>");
                    out.println("<a href='index.html'>Go Back</a>");
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
            out.println("<h3>Error: Database Error: " + e.getMessage() + "</h3>");
            out.println("<a href='index.html'>Go Back</a>");
        }
    }
}
