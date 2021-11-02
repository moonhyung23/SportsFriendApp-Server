package SocketServer2;

import java.sql.*;

class JavaDB {

    public static void main(String argv[]) {
        Connection con = null;
        Statement stmt = null;
        ResultSet rs = null;
        try {
            // * 1.드라이버 로딩 
            // * 드라이버 인터페이스를 구현한 클래스를 로딩 
            // * mysql, oracle 등 각 벤더사 마다 클래스 이름이 다르다. 
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");

            String url = "jdbc:mysql://3.37.253.243:3306/Sports_Friend_db";
            // * 2. 연결하기
            // * 드라이버 매니저에게 Connection 객체를 달라고 요청한다.
            // * mysql은 "jdbc:mysql://localhost/사용할db이름" 이다.
             // @param  getConnection(url, userName, password);
            // @return Connection
             con = DriverManager.getConnection(url,"root","ansgud12");

            System.out.println("mysql 접속 성공");
            // * 3.쿼리 수행을 위한 StateMent 객체 생성
             stmt = con.createStatement();
            // * 4.SQL 쿼리 작성
            // * 주의사항 
            // * 1)JDBC에서 쿼리를 작성할 때는 세미콜론(;)을 뺴고 작성한다.
            // * 2)SELECT 할 때 * 으로 모든 컬럼을 가져오는 것보다 가져와야할 컬럼을
            // * 직접 명시해주는 것이 좋다.

            // * 5.쿼리 수행
            // * 테이블에서 채팅 작성자의 사용자 정보 조회 
             rs = stmt.executeQuery("SELECT * FROM USERS WHERE user_idx = 6");

            System.out.println("Got result:");

            // * 6.실행결과 출력하기
            // * 레코드의 칼럼은 배열과 달리 0부터 시작하지 않고 1부터 시작한다.
            // * 데이터베이스에서 가져오는 데이터의 타입에 맞게 getString 또는 getInt등을 호출한다.            
            // * 조회한 로우의 개수 만큼 반복
            while(rs.next()) {
                // * 컬럼 번호는 1번부터 시작
                String no= rs.getString(3);
                String tblname  = rs.getString(4);
                System.out.println(" no = " + no);
                System.out.println(" tblname= "+ tblname);
            }
            stmt.close();
            con.close();

        } catch( ClassNotFoundException e){
            System.out.println("드라이버 로딩 실패");
        }
        catch( SQLException e){
            System.out.println("에러 " + e);
        }
        finally{
            try{
                if( con != null && !con.isClosed()){
                    con.close();
                }
                if( stmt != null && !stmt.isClosed()){
                    stmt.close();
                }
                if( rs != null && !rs.isClosed()){
                    rs.close();
                }
            }
            catch( SQLException e){
                e.printStackTrace();
            }
        }
    }

}
