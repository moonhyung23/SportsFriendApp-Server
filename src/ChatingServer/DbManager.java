package ChatingServer;

import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.TimeZone;

public class DbManager {
    Connection con = null;
    Statement stmt = null;
    PreparedStatement pstmt = null;
    ResultSet rs = null;


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

    // 채팅 방 추가
    public void Insert_chatRoom(ArrayList<String> List_chat) {
        try {
            // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
            // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
            // 4: 보낸사람(방장) idx
            // 5: 채팅  방 idx 번호
            // 6: 채팅 idx 번호
            // 7: 채팅 보낸 날짜(시간)
            // 8: 채팅 보낸 사람 닉네임
            // 9: 채팅 방 제목
            // 10: 프로필 사진
            Connection();
            String sql = "INSERT INTO ChatRoom  (" +
                    "attend_idx, " +
                    "room_title," +
                    "room_person_cnt, " +
                    "room_created_date, " +
                    "room_chat_time, " +
                    "room_host_idx, " +
                    "room_uuid)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?)";
            System.out.println("채팅방 추가 sql: " + sql);
            String[] ar_personCnt = List_chat.get(1).split("\\$");
            pstmt = con.prepareStatement(sql);
            pstmt.setString(1, List_chat.get(1)); //채팅 방 참여자 인덱스 번호
            pstmt.setString(2, List_chat.get(9)); //채팅 방 제목
            pstmt.setInt(3, ar_personCnt.length); //채팅 방 인원 수
            pstmt.setString(4, List_chat.get(7)); //채팅 방 만든 날짜(시간)
            pstmt.setString(5, ""); //최근에 채팅 보낸 시간
            pstmt.setInt(6, Integer.parseInt(List_chat.get(4))); //방장 인덱스 번호
            pstmt.setString(7, List_chat.get(5)); //방 번호

            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 방 정보 저장성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    //채팅 정보 추가
    public void Insert_ChatInfor(ArrayList<String> List_chat) {
        try {
            //* 채팅 정보 (@)
            // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
            // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
            // 4: 보낸사람(방장) idx
            // 5: 채팅 방 번호
            // 6: 채팅 번호
            // 7: 채팅 보낸 날짜(시간)
            // 8: 채팅 보낸 사람 닉네임
            // 9: 채팅 방 제목
            // 10: 프로필 사진
            Connection();
            String sql = "INSERT INTO Chat  (" +
                    "chat_user_idx, " +
                    "chat_content," +
                    "chat_uuid, " +
                    "chat_created_date, " +
                    "chat_room_uuid, " +
                    "status_idx)" +
                    " VALUES (?, ?, ?, ?, ?, ?)";

            //채팅 방에 초대된 유저 idx
            pstmt = con.prepareStatement(sql);
            pstmt.setInt(1, Integer.parseInt(List_chat.get(4))); //채팅 작성자 인덱스번호
            pstmt.setString(2, List_chat.get(3)); //채팅 내용
            pstmt.setString(3, List_chat.get(6)); //채팅 번호
            pstmt.setString(4, List_chat.get(7)); //채팅 보낸 날짜(시간)
            pstmt.setString(5, List_chat.get(5)); //채팅 방 번호
            pstmt.setInt(6, 2); //채팅 읽음 표시

            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 저장성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }


    // 삭제
    public void delete(String room_idx) {
        try {
            Connection();
            String sql = "DELETE FROM ChatRoom WHERE room_uuid = " + room_idx;
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
            String sql = "UPDATE ChatRoom set c1 = 5 WHERE c1 = 1";
            pstmt = con.prepareStatement(sql);
            pstmt.executeUpdate();
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    //채팅 방 중복 체크
    public void select_chatRoom_RedunCheck(String room_uuid) {
        try {
            Connection();
            String sql = "SELECT invite_idx FROM ChatRoom WHERE room_uuid = " + room_uuid;
            rs = stmt.executeQuery(sql);
            String attend_idx = "";
            while (rs.next()) {
                attend_idx = rs.getString(1);
            }
            String[] ar_attend_idx = attend_idx.split("\\$");
            /*String[] ar_invite_idx = invite_idx.split("\\$");
            if(Arrays.equals(ar_attend_idx, )){

            }*/
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    //채팅 방 참여자 정보 조회
    public String select_Invite_idx(String room_idx) {
        try {
            String invite_idx = "";
            Connection();
            StringBuilder sb = new StringBuilder();
            //채팅 방 번호가 같은 행을 채팅방 정보 테이블에서 조회
            String sql = sb.append("SELECT * FROM ChatRoom WHERE room_uuid = '").append(room_idx).append("'").toString();
            System.out.println("sql: " + sql);
            rs = stmt.executeQuery(sql);
            while (rs.next()) {
                invite_idx = rs.getString(1);
            }
            closeConnection();
            return invite_idx;
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return "방정보 조회 에러";
    }

    public String getDate() {
        Date today = new Date();
        TimeZone tz = TimeZone.getTimeZone("Asia/Seoul");
        SimpleDateFormat df = new SimpleDateFormat("yyyy/MM/dd hh:mm:ss");
        df.setTimeZone(tz);
        return df.format(today);
    }

//    public


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

