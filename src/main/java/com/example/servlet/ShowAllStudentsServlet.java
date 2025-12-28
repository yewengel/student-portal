package com.example.servlet;

import com.example.model.Student;
import com.example.util.DBUtil;
import jakarta.servlet.RequestDispatcher;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

@WebServlet("/show_all")
public class ShowAllStudentsServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        List<Student> studentList = new ArrayList<>();

        try (Connection conn = DBUtil.getConnection()) {
            // STEP 1: Select id explicitly
            String sql = "SELECT id, name, email, year FROM students";
            try (PreparedStatement stmt = conn.prepareStatement(sql);
                 ResultSet rs = stmt.executeQuery()) {

                // STEP 2: Loop through results and set id, name, email, year
                while (rs.next()) {
                    Student student = new Student();
                    student.setId(rs.getInt("id"));          // include id
                    student.setName(rs.getString("name"));
                    student.setEmail(rs.getString("email"));
                    student.setYear(rs.getInt("year"));
                    studentList.add(student);
                }
            }
        } catch (SQLException e) {
            e.printStackTrace();
            request.setAttribute("errorMessage", "Database error: " + e.getMessage());
        }

        // STEP 3: Pass the student list to JSP
        request.setAttribute("studentList", studentList);
        RequestDispatcher dispatcher = request.getRequestDispatcher("show_students.jsp");
        dispatcher.forward(request, response);
    }
}
