package org.example.models;

import com.mysql.cj.x.protobuf.MysqlxDatatypes;

import java.sql.*;

public class Project {
    private int id;
    private String name;
    private String description;
    private Date startDate;
    private Date deadline;
    private String status;
    private int managerId;

    public Project() {
    }

    public Project(int id, String name, String description, Date startDate, Date deadline,int managerId, String status) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.startDate = startDate;
        this.deadline = deadline;
        this.status = status;
        this.managerId = managerId;
    }

    public Project(String name, String description, Date startDate, Date deadline,int managerId, String status) {
        this.name = name;
        this.description = description;
        this.startDate = startDate;
        this.deadline = deadline;
        this.status = status;
        this.managerId = managerId;
    }

    public Project(int id, String name, String description, int managerId) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.managerId = managerId;
    }

    public Project(int id) {
        this.id = id;
    }

    public Project(String name) {
        this.name = name;
    }

    @Override
    public String toString() {
        return "Project{" +
                "id=" + id +
                ", name='" + name + '\'' +
                ", description='" + description + '\'' +
                ", startDate=" + startDate +
                ", deadline=" + deadline +
                ", status='" + status + '\'' +
                ", managerId=" + managerId +
                '}';
    }

    //gettery i settery
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public Date getStartDate() {
        return startDate;
    }

    public void setStartDate(Date startDate) {
        this.startDate = startDate;
    }

    public Date getDeadline() {
        return deadline;
    }

    public void setDeadline(Date deadline) {
        this.deadline = deadline;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public int getManagerId() {
        return managerId;
    }

    public void setManagerId(int managerId) {
        this.managerId = managerId;
    }
    public void addProject(Connection con) {
        PreparedStatement ps = null;
        try {
            String query = "INSERT INTO projekty (nazwa, opis, data_rozpoczecia, deadline, status, id_menadzera_projektu) VALUES (?,?,?,?,?,?)";
            ps = con.prepareStatement(query);
            ps.setString(1, this.name);
            ps.setString(2, this.description);
            ps.setDate(3, this.startDate);
            ps.setDate(4, this.deadline);
            ps.setString(5, this.status);
            ps.setInt(6, this.managerId);
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
    public Project getProject(Connection con, int id) {
        PreparedStatement ps = null;
        ResultSet rs = null;
        Project project = null;
        try {
            String query = "SELECT * FROM projekty WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, id);
            rs = ps.executeQuery();
            if (rs.next()) {
                project = new Project(rs.getInt("id"), rs.getString("nazwa"), rs.getString("opis"), rs.getDate("data_rozpoczecia"), rs.getDate("deadline"),rs.getInt("id_menadzera_projektu"), rs.getString("status"));
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
        return project;
    }
    public void updateProject(Connection con, int id, String name, String description, Date startDate, Date deadline, int managerId, String status) {
        PreparedStatement ps = null;
        try {
            String query = "UPDATE projekty SET nazwa = ?, opis = ?,data_rozpoczecia = ?, deadline = ?, id_menadzera_projektu = ?, status = ? WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setString(1, name);
            ps.setString(2, description);
            ps.setDate(3,startDate);
            ps.setDate(4,deadline);
            ps.setInt(5, managerId);
            ps.setString(6, status);
            ps.setInt(7,id);
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
    public void deleteProject(Connection con, int id) {
        PreparedStatement ps = null;
        try {
            String query = "DELETE FROM projekty WHERE id = ?";
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