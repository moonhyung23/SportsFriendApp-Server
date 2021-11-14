package ChatingServer;

import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
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
            // 11: 뷰타입번호
            // 12: 초대정보
            //채팅 뷰타입 번호
            // 1번 -> 채팅, 날짜, 초대정보
            // 2번 -> 채팅
            // 3번 -> 초대정보
            //서버에 채팅 방 정보(JSONArray) 보내기
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
    public String Insert_ChatInfor(ArrayList<String> List_chat, int viewType) {
        //채팅방에 초대된 닉네임 (본인제외)
        StringBuilder inviteNick = new StringBuilder();
        String inviteNickLast = "";
        try {
            //* 채팅 정보 (@)
            // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
            // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
            // 4: 보낸사람(방장) idx
            // 5: 채팅  방 idx 번호
            // 6: 채팅 idx 번호
            // 7: 채팅 보낸 날짜(시간)
            // 8: 채팅 보낸 사람 닉네임
            // 9: 채팅 방 제목
            // 10: 프로필 사진
            // 11: 뷰타입번호
            //채팅 뷰타입 번호
            // 1번 -> 채팅, 날짜, 초대정보
            // 2번 -> 채팅
            // 3번 -> 초대정보
            //서버에 채팅 방 정보(JSONArray) 보내기
            //12: 초대정보
            //Mysql 연결
            Connection();
            /*채팅 방 초대정보 설정 */
            //채팅방에 초대된 유저 닉네임 배열
            String[] ar_inviteNick = List_chat.get(2).split("\\$");
            //배열에서 찾기
            for (String s : ar_inviteNick) {
                //나의 닉네임과 일치하지 않는 경우
                if (!s.equals(List_chat.get(8))) {
                    //일치하지 않는 닉네임 변수에 저장
                    inviteNick.append(s).append(", ");
                }
            }
            //초대정보에 들어갈 문자열
            inviteNickLast = List_chat.get(8) + "님이 " + inviteNick.substring(0, inviteNick.length() - 2) + "님을 초대했습니다.";
            String sql = "INSERT INTO Chat  (" +
                    "chat_user_idx, " +
                    "chat_content," +
                    "chat_uuid, " +
                    "chat_created_date, " +
                    "chat_room_uuid, " +
                    "viewType, " +
                    "invite_Infor, " +
                    "status_idx)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            //채팅 방에 초대된 유저 idx
            pstmt = con.prepareStatement(sql);
            pstmt.setInt(1, Integer.parseInt(List_chat.get(4))); //채팅 작성자 인덱스번호
            pstmt.setString(2, List_chat.get(3)); //채팅 내용
            pstmt.setString(3, List_chat.get(6)); //채팅 번호
            pstmt.setString(4, List_chat.get(7)); //채팅 보낸 날짜(시간)
            pstmt.setString(5, List_chat.get(5)); //채팅 방 번호
            pstmt.setInt(6, viewType); //채팅 뷰타입  번호
            pstmt.setString(7, inviteNickLast); //초대정보
            pstmt.setInt(8, 2); //채팅 읽음 표시

            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 저장성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        //초대정보 반환
        return inviteNickLast;
    }

    //초대 정보 추가
    public void Insert_inviteInfor(ArrayList<String> List_chat, String invite_infor) {
        try {

            Connection();
            System.out.println("insert_invite_infor: " + invite_infor);
            String sql = "INSERT INTO Chat  (" +
                    "chat_user_idx, " +
                    "chat_content," +
                    "chat_uuid, " +
                    "chat_created_date, " +
                    "chat_room_uuid, " +
                    "viewType, " +
                    "invite_Infor, " +
                    "status_idx)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            //채팅 방에 초대된 유저 idx
            pstmt = con.prepareStatement(sql);
            pstmt.setInt(1, Integer.parseInt(List_chat.get(4))); //채팅 작성자 인덱스번호
            pstmt.setString(2, List_chat.get(3)); //채팅 내용
            pstmt.setString(3, List_chat.get(6)); //채팅 번호
            pstmt.setString(4, List_chat.get(7)); //채팅 보낸 날짜(시간)
            pstmt.setString(5, List_chat.get(5)); //채팅 방 번호
            pstmt.setInt(6, 3); //채팅 뷰타입  번호
            pstmt.setString(7, invite_infor); //초대정보
            pstmt.setInt(8, 2); //채팅 읽음 표시

            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("초대 정보 저장성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }


    //채팅 정보 추가

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
            StringBuilder sb = new StringBuilder();

            String sql = sb.append("UPDATE test1 SET ")
                    .append("c2 = 'aa', ")
                    .append("c4 = 'bb' ")
                    .append("WHERE c1 = 1").toString();

            pstmt = con.prepareStatement(sql);
            pstmt.executeUpdate();
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    //채팅 방 정보 수정
    public String update_chatRoom(String attend_idx,
                                  String room_title,
                                  String roomIdx,
                                  String hostNick,
                                  String newInviteNick) {
        //host를 제외한 채팅방에 초대된 유저 닉네임
        String invite_Infor = "";
        String newInviteNick_last = "";
        try {
            Connection();
            //채팅방 참여 인원수를 구하기 위해서 생성
            String[] ar_attend_idx = attend_idx.split("\\$");

            StringBuilder sb = new StringBuilder();
            //수정할 정보
            // -참석자 idx
            // -방제목
            String sql = sb.append("UPDATE ChatRoom SET ")
                    .append("attend_idx = '").append(attend_idx).append("', ") //채팅방 참여자 idx
                    .append("room_person_cnt = ").append(ar_attend_idx.length).append(", ") //인원 수
                    .append("room_title = '").append(room_title).append("'") //제목
                    .append("WHERE room_uuid = '").append(roomIdx).append("'").toString(); //방번호
            System.out.println("update_chatRoom SQL: " + sql);
            pstmt = con.prepareStatement(sql);
            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 수정성공!");
                /*채팅방 초대정보 작성*/

                newInviteNick_last = newInviteNick.replace("$", ", ");
                //마지막 문자열(", ") 제거해서 초대정보 작성
                invite_Infor = hostNick + "님이 "
                        + newInviteNick_last
                        + "님을 초대했습니다";
                System.out.println("invite_Infor: " + invite_Infor);
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return invite_Infor;
    }

    //채팅 방 참석자 중복 체크
    public String select_chatRoom_RedunCheck(String[] ar_invite_idx) {
        String readValue = "방생성가능";
        try {
            Connection();
            //모든 채팅방의 참여자 idx번호 조회
            String sql = "SELECT attend_idx, room_uuid FROM ChatRoom";
            //참여자 idx 번호 로우 조회
            rs = stmt.executeQuery(sql);
            //로우의 개수만큼 반복
            while (rs.next()) {
                String[] ar_attend_idx = rs.getString(1).split("\\$");

                //두 배열이 서로 같은 경우
                // -참석자가 중복된 방이 있는 경우
                if (Arrays.equals(parseIntArray(ar_invite_idx), parseIntArray(ar_attend_idx))) {
                    //중복된 방의 번호 변수에 저장
                    readValue = rs.getString(2);
                }

            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return readValue;
    }

    //채팅 방 참여자 정보 조회
    public String select_Invite_idx(String room_idx) {
        try {
            String attend_idx = "";
            Connection();
            StringBuilder sb = new StringBuilder();
            //채팅 방 번호가 같은 행을 채팅방 정보 테이블에서 조회
            String sql = sb.append("SELECT * FROM ChatRoom WHERE room_uuid = '").append(room_idx).append("'").toString();
            System.out.println("채팅방정보 조회 쿼리 sql: " + sql);
            rs = stmt.executeQuery(sql);
            while (rs.next()) {
                attend_idx = rs.getString(1);
            }
            closeConnection();
            return attend_idx;
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return "방정보 조회 에러";
    }

    //채팅방 제목을 반환하는 메서드
    // -채팅방 제목 -> 참여한 유저 닉네임
    public String select_nickname(String chatRoomUserIdx) {
        try {
            //채팅방
            StringBuilder sb_roomTitle = new StringBuilder();
            String select_idx = "";
            Connection();
            //$로 되어있는 문자열을  ", "로 변환
            //채팅방 제목 예시 -> 닉네임1, 닉네임2, 닉네임3
            select_idx = chatRoomUserIdx.replace("$", ", ");
            StringBuilder sb = new StringBuilder();
            //채팅 방 번호가 같은 행을 채팅방 정보 테이블에서 조회
            String sql = sb.append("SELECT user_nickname FROM USERS WHERE user_idx IN ")
                    .append("(")
                    .append(select_idx)
                    .append(")").toString();
            System.out.println("채팅방정보 조회 쿼리 sql: " + sql);
            rs = stmt.executeQuery(sql);
            while (rs.next()) {
                sb_roomTitle.append(rs.getString(1)).append("$");
            }
            closeConnection();
            //마지막 문자열 ("$")제거
            System.out.println("리턴할 방제목:  " + sb_roomTitle.substring(0, sb_roomTitle.length() - 1));
            return sb_roomTitle.substring(0, sb_roomTitle.length() - 1);
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return "방정보 조회 에러";
    }


    //채팅방을 나갈 채팅방을 삭제하는 메서드
    public void delete_chatRoom(ArrayList<String> List_exitInfor) {
        //db 연결
        try {
            StringBuilder sb = new StringBuilder();
            //db 연결
            Connection();
         /*   String sql = "DELETE FROM ChatRoom WHERE room_uuid = " + room_idx;
            pstmt = con.prepareStatement(sql);
            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 수정성공!");
            }
            closeConnection();*/

        } catch (SQLException e) {

        }
    }

    //채팅방을 나갈 떄 나간사람의 닉네임, 인덱스 정보를 테이블에서 지우는 메서드
    public void update_chatRoom_exit(ArrayList<String> List_exitInfor) {
        try {
            StringBuilder sb = new StringBuilder();
            Connection();
                /*    //db 연결
    //수정할 정보
            // -참석자 idx
            // -방제목
            String sql = sb.append("UPDATE ChatRoom SET ")
                    .append("attend_idx = '").append(attend_idx).append("', ") //채팅방 참여자 idx
                    .append("room_person_cnt = ").append(ar_attend_idx.length).append(", ") //인원 수
                    .append("room_title = '").append(room_title).append("'") //제목
                    .append("WHERE room_uuid = '").append(roomIdx).append("'").toString(); //방번호
            System.out.println("update_chatRoom SQL: " + sql);
            pstmt = con.prepareStatement(sql);
            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 수정성공!");
            }
            closeConnection();*/


        } catch (SQLException e) {

        }
    }

    //채팅방 나가기 정보 쿼리
    public String query_ChatRoomExit(int chatRoomCnt, ArrayList<String> List_exitInfor) {
        //db 연결
        //채팅 방 인원 수가 1명인 경우
        if (chatRoomCnt == 1) {
            //1.삭제
            //채팅 방 삭제
            //채팅 방 채팅내역 삭제
            delete_chatRoom(List_exitInfor);
        }
        //채팅 방 인원 수가 1명이 아닌 경우
        // -2명 이상
        else {
            //2.업데이트
            //나가는 유저 idx 지우기
            //나가는 유저 닉네임 지우기
            update_chatRoom_exit(List_exitInfor);
        }

        return "";
    }


    public String getDate() {
        Date today = new Date();
        TimeZone tz = TimeZone.getTimeZone("Asia/Seoul");
        SimpleDateFormat df = new SimpleDateFormat("yyyy/MM/dd hh:mm:ss");
        df.setTimeZone(tz);
        return df.format(today);
    }

    //String 배열을 Int 배열로 바꾼 후 오름차순 정렬로 변환
    public int[] parseIntArray(String[] ar_string) {
        //String 배열을 Int 배열로 바꾼 후 오름차순 정렬로 변환
        int[] ar_Int = new int[ar_string.length];
        for (int i = 0; i < ar_string.length; i++) {
            ar_Int[i] = Integer.parseInt(ar_string[i]);
        }
        //오름차순 정렬
        Arrays.sort(ar_Int);
        return ar_Int;
    }


}

