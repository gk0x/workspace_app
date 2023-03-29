package org.example.models;

import java.sql.*;
import java.time.Duration;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.LocalTime;
import java.time.temporal.ChronoUnit;

public class TimeWork {
    private int id;
    private int employeeId;
    private LocalDateTime loginTime;
    private LocalDateTime logoutTime;
    private int projectId;
    private LocalTime workingTime;

    public TimeWork() {
    }

    public TimeWork(int id, int employeeId, LocalDateTime loginTime, LocalDateTime logoutTime, int projectId, LocalTime workingTime) {
        this.id = id;
        this.employeeId = employeeId;
        this.loginTime = loginTime;
        this.logoutTime = logoutTime;
        this.projectId = projectId;
        this.workingTime = workingTime;
    }




    public void CreateTimeWork(Connection con, int employeeId, LocalDateTime loginTime, LocalDateTime logoutTime, int projectId) {
        try {
            String sql = "INSERT INTO time_work (id_pracownika, data_zalogowania, data_wylogowania, id_projektu) VALUES (?, ?, ?, ?)";
            PreparedStatement ps = con.prepareStatement(sql);
            ps.setInt(1, employeeId);
            ps.setTimestamp(2, Timestamp.valueOf(loginTime));
            ps.setTimestamp(3, Timestamp.valueOf(logoutTime));
            ps.setInt(4, projectId);
            ps.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            try {
                con.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }




     public void insertDailyTimeWork(Connection con, int employeeId, LocalDate date, LocalTime loginTime, LocalTime logoutTime, int projectId) {
            PreparedStatement ps = null;
            try {
                String query = "INSERT INTO czas_pracy (id_pracownika, data_zalogowania, data_wylogowania, id_projektu, czas) VALUES (?,?,?,?,?)";
                ps = con.prepareStatement(query);
                ps.setInt(1, employeeId);
                ps.setDate(2, Date.valueOf(date));
                ps.setTime(3, Time.valueOf(loginTime));
                ps.setTime(4, Time.valueOf(logoutTime));
                ps.setInt(5, projectId);

                // obliczanie czasu pracy dla danego dnia
                long workTime = Duration.between(loginTime, logoutTime).toMinutes();
                ps.setLong(6, workTime);

                ps.executeUpdate();
            } catch (SQLException e) {
                e.printStackTrace();
            } finally {
                try {
                    if (ps != null) {
                        ps.close();
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                }
            }
        }

        public void insertMonthlyTimeWork(Connection con, int employeeId, LocalDate date, int projectId) {
            PreparedStatement ps = null;
            try {
                String query = "SELECT SUM(czas) FROM czas_pracy WHERE id_pracownika = ? AND MONTH(data_zalogowania) = MONTH(?) AND id_projektu = ?";
                ps = con.prepareStatement(query);
                ps.setInt(1, employeeId);
                ps.setDate(2, Date.valueOf(date));
                ps.setInt(3, projectId);
                ResultSet rs = ps.executeQuery();
                if (rs.next()) {
                    int monthlyTime = rs.getInt(1);
                    // wstawienie sumy czasu pracy dla danego miesiąca do tabeli
                    // ...
                }
            } catch (SQLException e) {
                e.printStackTrace();
            } finally {
                try {
                    if (ps != null) {
                        ps.close();
                    }
                } catch (SQLException e) {
                    e.printStackTrace();
                }
            }
        }
    public void insertTotalTimeWorkForProject(Connection con, int employeeId, int projectId) {
        PreparedStatement ps = null;
        try {
            String query = "SELECT SUM(czas) FROM czas_pracy WHERE id_pracownika = ? AND id_projektu = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, employeeId);
            ps.setInt(2, projectId);
            ResultSet rs = ps.executeQuery();
            if(rs.next()) {
                int totalTime = rs.getInt(1);
                System.out.println("Łączny czas pracy pracownika o ID " + employeeId + " dla projektu o ID " + projectId + " wynosi " + totalTime + " godzin.");
            }
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
    public LocalTime getDailyTimeWork(Connection con, int employeeId, LocalDate date) {
        LocalTime dailyWorkTime = LocalTime.of(0, 0); // initialize to zero
        try {
            String query = "SELECT SUM(czas) FROM czas_pracy WHERE id_pracownika = ? AND data_zalogowania >= ? AND data_zalogowania < ?";
            PreparedStatement ps = con.prepareStatement(query);
            ps.setInt(1, employeeId);
            ps.setDate(2, Date.valueOf(date));
            ps.setDate(3, Date.valueOf(date.plusDays(1)));
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                int timeInMinutes = rs.getInt(1);
                dailyWorkTime = LocalTime.ofSecondOfDay(timeInMinutes * 60);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return dailyWorkTime;
    }
    public LocalTime getMonthlyTimeWork(Connection con, int employeeId, int month, int year) {
        PreparedStatement ps = null;
        LocalTime totalMonthlyWorkTime = null;
        try {
            String query = "SELECT SUM(czas) FROM czas_pracy WHERE id_pracownika = ? AND MONTH(data_zalogowania) = ? AND YEAR(data_zalogowania) = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, employeeId);
            ps.setInt(2, month);
            ps.setInt(3, year);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                long minutes = rs.getLong("SUM(czas)");
                totalMonthlyWorkTime = LocalTime.ofSecondOfDay(minutes * 60);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return totalMonthlyWorkTime;
    }

    public LocalTime getTotalTimeWorkForProject(Connection con, int projectId) {
        PreparedStatement ps = null;
        LocalTime totalProjectWorkTime = null;
        try {
            String query = "SELECT SUM(czas) FROM czas_pracy WHERE id_projektu = ?";
            ps = con.prepareStatement(query);
            ps.setInt(1, projectId);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                long minutes = rs.getLong("SUM(czas)");
                totalProjectWorkTime = LocalTime.ofSecondOfDay(minutes * 60);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return totalProjectWorkTime;
    }

}










