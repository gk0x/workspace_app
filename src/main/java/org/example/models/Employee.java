package org.example.models;

import java.sql.*;

public class Employee {
    private int id;
    private String firstName;
    private String lastName;
    private int roleId;

    public Employee() {}

    public Employee(String firstName, String lastName, int roleId) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.roleId = roleId;
    }

    public Employee(int id, String firstName, String lastName, int roleId) {
        this.id = id;
        this.firstName = firstName;
        this.lastName = lastName;
        this.roleId = roleId;
    }

    //gettery i settery
    public int getId() { return id; }
    public void setId(int id) { this.id = id; }
    public String getFirstName() { return firstName; }
    public void setFirstName(String firstName) { this.firstName = firstName; }
    public String getLastName() { return lastName; }
    public void setLastName(String lastName) { this.lastName = lastName; }
    public int getRoleId() { return roleId; }
    public void setRoleId(int roleId) { this.roleId = roleId; }

    @Override
    public String toString() {
        return "Employee{" +
                "id=" + id +
                ", firstName='" + firstName + '\'' +
                ", lastName='" + lastName + '\'' +
                ", roleId=" + roleId +
                '}';
    }

    //metoda dodawania pracownika do bazy danych
    public void addEmployee(Connection con) {
        PreparedStatement ps = null;
        try {
            String query = "INSERT INTO pracownicy (imie, nazwisko, rola_id) VALUES (?,?,?)";
            ps = con.prepareStatement(query);
            ps.setString(1, this.firstName);
            ps.setString(2, this.lastName);
            ps.setInt(3, this.roleId);
            ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            try {
                if(ps != null) {
                    ps.close();
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    //metoda pobierania pracownika z bazy danych
    public Employee getEmployee(Connection con, int id) {
        PreparedStatement ps = null;
        ResultSet rs = null;
        Employee employee = null;
        try {
            String query = "SELECT * FROM pracownicy WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, id);
            rs = ps.executeQuery();
            if(rs.next()) {
                employee = new Employee(rs.getInt("id"), rs.getString("imie"), rs.getString("nazwisko"), rs.getInt("rola_id"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            try {
                if(rs != null) {
                    rs.close();
                }
                if(ps != null) {
                    ps.close();
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
        return employee;
    }
    //metoda aktualizacji pracownika w bazie danych
    public void updateEmployee(Connection con, int id) {
        PreparedStatement ps = null;
        try {
            String query = "UPDATE pracownicy SET imie = ?, nazwisko = ?, rola_id = ? WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setString(1, this.firstName);
            ps.setString(2, this.lastName);
            ps.setInt(3, this.roleId);
            ps.setInt(4, id);
            ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            try {
                if(ps != null) {
                    ps.close();
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

    //metoda usuwania pracownika z bazy danych
    public void deleteEmployee(Connection con, int id) {
        PreparedStatement ps = null;
        try {
            String query = "DELETE FROM pracownicy WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, id);
            ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            try {
                if(ps != null) {
                    ps.close();
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }

}