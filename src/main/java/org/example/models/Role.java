package org.example.models;
import javax.xml.transform.Result;
import java.sql.*;

public class Role {
    private int id;
    private String name;


    public Role() {
    }

    public Role(int id, String name) {
        this.id = id;
        this.name = name;
    }

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

    @Override
    public String toString() {
        return "Role{" +
                "id=" + id +
                ", name='" + name + '\'' +
                '}';
    }

    public void addRole(Connection con){
        PreparedStatement ps = null;
        try {
            String query = "INSERT INTO rola (nazwa) VALUES (?)";
            ps = con.prepareStatement(query);
            ps.setString(1,this.name);
            ps.executeUpdate();
        } catch (SQLException e){
            e.printStackTrace();
        }finally {
            try {
                if (ps!=null){
                    ps.close();
                }
            }catch (SQLException e){
                e.printStackTrace();
            }
        }
    }

    public Role getRole(Connection con,int id){
        PreparedStatement ps = null;
        ResultSet rs = null;
        Role role = null;

        try {
            String query = "SELECT * FROM rola WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1,id);
            rs = ps.executeQuery();
            if (rs.next()){
                role = new Role(rs.getInt("id"), rs.getString("nazwa"));
            }
        }catch (SQLException e){
            e.printStackTrace();
        }finally {
            try {
                if (rs!=null){
                    rs.close();
                }
                if(ps != null) {
                    ps.close();
                }
            }catch (SQLException e){
                e.printStackTrace();
            }
        }return role;
    }
public void updateRole(Connection con, int id, String name){
        PreparedStatement ps = null;
        try {
            String query = "UPDATE rola SET nazwa = ? WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setString(1,name);
            ps.setInt(2,id);
            ps.executeUpdate();
        }catch (SQLException e){
            e.printStackTrace();
        }finally {
            try {
                if(ps != null) {
                    ps.close();
                }
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
}
public void deleteRole(Connection con, int id){
        PreparedStatement ps = null;
        try {
            String query = "DELETE FROM rola WHERE id = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1,id);
            ps.executeUpdate();
        }catch (SQLException e ){
            e.printStackTrace();
        } finally {
            try {
                if (ps!=null){
                    ps.close();
                }
            }catch (SQLException e){
                e.printStackTrace();
            }
        }
}
}
