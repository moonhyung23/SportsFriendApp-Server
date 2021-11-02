package JDBC;

import java.sql.*;

public class SqlTest {
    Connection con = null;
    Statement stmt = null;
    PreparedStatement pstmt = null;
    ResultSet rs = null;
    String tableName;


    public SqlTest(String tableName) {
        this.tableName = tableName;
    }

    public static void main(String[] args) {
        Connection con = null;
        Statement stmt = null;
        ResultSet rs = null;

        try {
            // * 1.드라이버 로딩
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");
        } catch (ClassNotFoundException e) {
            System.out.println("드라이버 로딩 실패");
        }

        SqlTest sqlTest = new SqlTest("test1");
        sqlTest.insert();
    }

    public void Connection() throws SQLException {
        String url = "jdbc:mysql://3.37.253.243:3306/Sports_Friend_db";
        // * 2. 연결하기
        // * 드라이버 매니저에게 Connection 객체를 달라고 요청한다.
        // * mysql은 "jdbc:mysql://localhost/사용할db이름" 이다.
        con = DriverManager.getConnection(url, "root", "ansgud12");
        System.out.println("mysql 접속 성공");
        // * 3.쿼리 수행을 위한 StateMent 객체 생성
        stmt = con.createStatement();
    }

    //연결 끊기.
    public void closeConnection() {
        try {
            //자원 반환
            if (rs != null && !rs.isClosed()) {
                rs.close();
            }
            if (stmt != null && !stmt.isClosed()) {
                stmt.close();
            }
            if (con != null && !con.isClosed()) {
                con.close();
            }
            if (pstmt != null && !pstmt.isClosed()) {
                pstmt.close();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    // 삽입
    public void insert() {
        try {
            Connection();
            String sql = "INSERT INTO " + tableName + " (c1, c2, c4) VALUES (?, ?, ?)";
            System.out.println("sql: " + sql);
            pstmt = con.prepareStatement(sql);
            pstmt.setInt(1, 5);
            pstmt.setInt(2, 6);
            pstmt.setInt(3, 7);

            int insert = pstmt.executeUpdate();

            System.out.println("입력 데이터 개수: " + insert);

            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    // 삭제
    public void delete() {
        try {
            Connection();

            String sql = "DELETE FROM " + tableName + " WHERE c1 = 1";
            pstmt = con.prepareStatement(sql);
            pstmt.executeUpdate();
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }


    }

    // 수정
    public void update() {
        try {
            Connection();
            String sql = "UPDATE " + tableName + " set c1 = 5 WHERE c1 = 1";
            pstmt = con.prepareStatement(sql);
            pstmt.executeUpdate();
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    // 조회
    public void select() {
        try {
            Connection();
            String sql = "SELECT * FROM " + tableName + " WHERE c1 = 2";
            rs = stmt.executeQuery(sql);
            while (rs.next()) {
                System.out.println("1: " + rs.getInt(1));
                System.out.println("2: " + rs.getInt(2));
            }

            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }




    /*// 검색
    public void select(int id) {
        StringBuilder sb = new StringBuilder();
        String sql = sb.append("select * from " + table + " where")
                .append(" id = ")
                .append(id)
                .append(";").toString();
        try {
            ResultSet rs = stmt.executeQuery(sql);
            System.out.print("id");
            System.out.print("\t");
            System.out.print("name");
            System.out.print("\t");
            System.out.print("grade");
            System.out.print("\n");
            System.out.println("────────────────────────");
            while (rs.next()) {
                System.out.print(rs.getInt("id"));
                System.out.print("\t");
                System.out.print(rs.getString("name"));
                System.out.print("\t");
                System.out.print(rs.getString("grade"));
                System.out.print("\n");
            }
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }*/
}

