package ChatingServer;

import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.*;

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

    //채팅 방 날짜 수정
    public void update_chatRoom_editDate(ArrayList<String> List_room_infor) {
        try {
            Connection();
            StringBuilder sb = new StringBuilder();
            String sql = sb.append("UPDATE ChatRoom SET ")
                    //날짜 수정
                    .append("room_edit_date = '").append(List_room_infor.get(7)).append("' ")
                    //조건: 채팅 방 번호
                    .append("WHERE room_uuid = '").append(List_room_infor.get(5)).append("'")
                    .toString();
            pstmt = con.prepareStatement(sql);
            int update = pstmt.executeUpdate();
            if (update != 0) {
                System.out.println("채팅방 수정날짜 갱신성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            System.out.println("채팅방 수정날짜 갱신 실패");

        }
    }

    // 채팅 방 추가
    public void Insert_chatRoom(ArrayList<String> List_chat) {
        try {
            //* 채팅방 List 정보 (@)
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
            // -1번 -> 채팅, 날짜, 초대정보
            // -2번 -> 채팅
            // -3번 -> 초대정보
            // 13: 채팅 읽은 사람 수
            // 14: 채팅 idx 번호
            // 15: 채팅방 이미지 url

            //서버에 채팅 방 정보(JSONArray) 보내기

//            List_chat.get(7)
            Connection();
            String sql = "INSERT INTO ChatRoom  (" +
                    "attend_idx, " +
                    "room_title," +
                    "room_person_cnt, " +
                    "room_created_date, " +
                    "room_chat_time, " +
                    "room_host_idx, " +
                    "room_uuid," +
                    "room_img_url," +
                    "room_edit_date)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            pstmt.setString(8, List_chat.get(15)); //채팅 방 이미지 url
            pstmt.setString(9, List_chat.get(7)); //채팅 방 만든 날짜(시간)
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
    //type 1번 -> 채팅방 + 채팅 추가
    //type 2번 -> 채팅 추가
    public String Insert_ChatInfor(ArrayList<String> List_chat, int viewType, int type) {
        //채팅방에 초대된 닉네임 (본인제외)
        StringBuilder inviteNick = new StringBuilder();
        String inviteNickLast = "";
        String last_chat_idx = "";
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
            // 4번 -> 이미지
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
                    "rp_cnt)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            //채팅 방에 초대된 유저 idx
            pstmt = con.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);
            pstmt.setInt(1, Integer.parseInt(List_chat.get(4))); //채팅 작성자 인덱스번호
            pstmt.setString(2, List_chat.get(3)); //채팅 내용
            pstmt.setString(3, List_chat.get(6)); //채팅 번호
            pstmt.setString(4, List_chat.get(7)); //채팅 보낸 날짜(시간)
            pstmt.setString(5, List_chat.get(5)); //채팅 방 번호
            pstmt.setInt(6, viewType); //채팅 뷰타입  번호
            pstmt.setString(7, inviteNickLast); //초대정보
            //형변환 오류 예외처리
            if (List_chat.get(13).equals("")) {
                pstmt.setInt(8, 0); //채팅 읽은 사람 수
            } else {
                pstmt.setInt(8, Integer.parseInt(List_chat.get(13))); //채팅 읽은 사람 수
            }

            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 저장성공!");
                rs = pstmt.getGeneratedKeys();
                rs.next();
                last_chat_idx = String.valueOf(rs.getInt(1));
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        //1번 -> 초대정보
        //2번 -> 추가한 채팅의 인덱스 번호
        if (type == 2) {
            inviteNickLast = "초대정보없음";
        }
        //초대한 유저 닉네임 + "$" + 마지막으로 추가한 채팅의 인덱스 번호
        return inviteNickLast + "$" + last_chat_idx;
    }

    //초대 정보 추가, 나간 사람 정보 추가
    //exit_and_invite_infor -> 채팅방 나간사람, 초대한 사람 정보
    public void Insert_exit_inviteInfor(ArrayList<String> List_chat, String exit_and_invite_infor) {
        try {
            if (List_chat.get(13).equals("")) {
                List_chat.set(13, "0");
            }
            Connection();
            System.out.println("insert_invite_infor: " + exit_and_invite_infor);
            String sql = "INSERT INTO Chat  (" +
                    "chat_user_idx, " +
                    "chat_content," +
                    "chat_uuid, " +
                    "chat_created_date, " +
                    "chat_room_uuid, " +
                    "viewType, " +
                    "invite_Infor, " +
                    "rp_cnt)" +
                    " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            //채팅 방에 초대된 유저 idx
            pstmt = con.prepareStatement(sql);
            pstmt.setInt(1, Integer.parseInt(List_chat.get(4))); //채팅 작성자 인덱스번호
            pstmt.setString(2, ""); //채팅 내용
            pstmt.setString(3, List_chat.get(6)); //채팅 번호
            pstmt.setString(4, List_chat.get(7)); //채팅 보낸 날짜(시간)
            pstmt.setString(5, List_chat.get(5)); //채팅 방 번호
            pstmt.setInt(6, 3); //채팅 뷰타입  번호
            pstmt.setString(7, exit_and_invite_infor); //초대정보
            pstmt.setInt(8, Integer.parseInt(List_chat.get(13))); //채팅 읽음 사람 수 표시

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
            int update = pstmt.executeUpdate();

            if (update != 0) {
                System.out.println("수정성공!");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    //채팅 읽은 사람 수정
    // -마지막으로 읽은 채팅의 인덱스보다 큰 채팅의 읽은 사람 수를 -1한다.
    public int update_chat_rp(ArrayList<String> List_roomInfor) {
        String sql = "";
        pstmt = null;
        //1번 -> 업데이트
        //2번 -> 업데이트X
        int update_flag = 0;
        try {
            StringBuilder sb = new StringBuilder();


            //초대된 채팅방에 처음 입장해서 채팅을 읽은 경우
            if (List_roomInfor.get(6).equals("null")) {
                sql = sb.append("UPDATE Chat SET ")
                        .append("rp_cnt = rp_cnt -1 ") //채팅 읽은 사람 수
                        //조건 : 해당 채팅이 있는 채팅방
                        .append("WHERE chat_room_uuid = '")
                        .append(List_roomInfor.get(5))
                        .append("'").toString();
            }
            //이미 초대된 방에 입장해서 채팅을 읽은 경우
            else {
                sql = sb.append("UPDATE Chat SET ")
                        .append("rp_cnt = rp_cnt -1 ") //채팅 읽은 사람 수
                        //조건 1 : 마지막으로 읽은 채팅 번호보다 큰 경우
                        .append(" WHERE chat_room_uuid = '").append(List_roomInfor.get(5)).append("' ")
                        //조건 2: 해당 채팅이 있는 채팅방
                        .append("AND chat_id > ").append(List_roomInfor.get(6)).toString();

            }
            System.out.println("update_chatRoom SQL: " + sql);
            Connection();
            int update1 = stmt.executeUpdate(sql);
            if (update1 != 0) {
                //1번 -> 업데이트
                update_flag = 1;
                System.out.println("채팅 읽은 사람 수 수정");
            } else {
                //2번 -> 업데이트 x
                update_flag = 2;
                System.out.println("채팅 다 읽음.");
            }
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }


        return update_flag;
    }

    //채팅 방 정보 수정
    //-채팅방에 유저를 초대해서 채팅방 테이블을 수정해줌.
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
    // -flag_close
    // 1번 -> 종료, 2번 -> 종료X
    // 같은 DBmanager클래스 안에서 메서드 사용 시 db close가 먼저 되서 다른 db작업이
    // 동작하지 않는 문제를 해결하기 위해 만듬
    public String select_nickname(String chatRoomUserIdx, int flag_close) {
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
            //1번일 때만 연결종료
            if (flag_close == 1) {
                closeConnection();
            }
            System.out.println("리턴할 방제목:  " + sb_roomTitle.substring(0, sb_roomTitle.length() - 1));
            //마지막 문자열 ("$")제거
            return sb_roomTitle.substring(0, sb_roomTitle.length() - 1);
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        return "방정보 조회 에러";
    }


    public int select_autoIncrement() {
        int auto_increment = 0;
        try {
            //채팅방
            StringBuilder sb_roomTitle = new StringBuilder();
            Connection();
            StringBuilder sb = new StringBuilder();
            String sql = sb.append("SELECT LAST_INSERT_ID()").toString();
            rs = stmt.executeQuery(sql);
            while (rs.next()) {
                auto_increment = rs.getInt(1);
            }

            //마지막 문자열 ("$")제거
            closeConnection();
        } catch (SQLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        System.out.println("LAST_INSERT_ID:  " + auto_increment);
        return auto_increment;
    }


    //채팅방을 삭제하는 메서드
    // -본인이 채팅방을 나간 경우
    public String delete_chatRoom(ArrayList<String> List_exitInfor) {
        String result = "";
        //db 연결
        try {
            StringBuilder sb_sql = new StringBuilder();
            //db 연결
            Connection();


            sb_sql.append("DELETE FROM a, b USING ChatRoom AS a ")
                    .append("INNER JOIN Chat AS b ")
                    .append("ON a.room_uuid = b.chat_room_uuid ")
                    .append("WHERE a.room_uuid = ")
                    .append("'").append(List_exitInfor.get(5)).append("'");
            pstmt = con.prepareStatement(sb_sql.toString());
            System.out.println("delete_chatRoom_sb_sql: " + sb_sql);
            int insert = pstmt.executeUpdate();
            if (insert != 0) {
                result = "삭제성공";
                System.out.println("채팅방, 채팅 삭제 성공!");
                List_exitInfor.set(3, result); //db 작업 성공

            }
            closeConnection();
        } catch (SQLException e) {

        }
        return result;
    }

    //채팅방을 나갈 떄 나간사람의 닉네임, 인덱스 정보를 테이블에서 지우는 메서드
    //-다른 사람이 내가 참여한 채팅방에 나간 경우 사용
    public String update_chatRoom_exit(ArrayList<String> List_exitInfor, List<String> List_attend_idx) {
        String result = "";
        try {
            StringBuilder sb = new StringBuilder();
            StringBuilder sb_attend_idx = new StringBuilder();
            Connection();
            //채팅방 참여자 리스트 조회
            for (int i = 0; i < List_attend_idx.size(); i++) {
                //채팅 방 참여자 리스트에서 나의  idx를 찾는다.
                if (List_attend_idx.get(i).equals(List_exitInfor.get(4))) {
                    //채팅 방 나가는 사람 idx 참여자 리스트에서 지우기
                    List_attend_idx.remove(i);
                }
            }

            //채팅방 참여자 idx 번호 조회(나간사람 제외)
            for (String list_attend_idx : List_attend_idx) {
                //채팅방에 나간 사람을 제외한 채팅방 참여자의 idx 번호에 각각 "$" 추가
                sb_attend_idx.append(list_attend_idx).append("$");
            }

            //채팅방 참여자 idx 번호
            String attend_idx_last = sb_attend_idx.substring(0, sb_attend_idx.length() - 1);


            //db 연결
            //수정할 정보
            // -참석자 idx
            // -방제목
            // -인원 수
            String sql = sb.append("UPDATE ChatRoom SET ")
                    .append("attend_idx = '").append(attend_idx_last).append("', ") //채팅방 참여자 idx
                    .append("room_person_cnt = ").append(List_attend_idx.size()).append(", ") //채팅 방 참여 인원 수
                    .append("room_title = '").append(select_nickname(attend_idx_last, 2)).append("' ") //제목
                    .append("WHERE room_uuid = '").append(List_exitInfor.get(5)).append("'").toString(); //조건 방번호
            System.out.println("update_chatRoom SQL: " + sql);
            pstmt = con.prepareStatement(sql);
            int insert = pstmt.executeUpdate();
            if (insert == 1) {
                System.out.println("채팅 정보 수정성공!");
                result = "수정성공";
                List_exitInfor.set(1, attend_idx_last); //채팅 방 참여자 idx 수정
                List_exitInfor.set(3, result); //db 작업 성공
                List_exitInfor.set(9, select_nickname(attend_idx_last, 2)); //채팅방 방 제목 수정
            }
            closeConnection();


        } catch (SQLException e) {

        }
        return result;
    }

    //채팅방 나가기 정보 쿼리
    public String query_ChatRoomExit(List<String> List_attend_idx, ArrayList<String> List_exitInfor) {
        String result = "";
        //db 연결
        //채팅 방 인원 수가 1명인 경우
        if (List_attend_idx.size() == 1) {
            //1.삭제
            //채팅 방 삭제
            //채팅 방 채팅내역 삭제
            result = delete_chatRoom(List_exitInfor);
        }
        //채팅 방 인원 수가 1명이 아닌 경우
        // -2명 이상
        else {
            //2.업데이트
            //나가는 유저 idx 지우기
            //나가는 유저 닉네임 지우기
            result = update_chatRoom_exit(List_exitInfor, List_attend_idx);
        }


        return result;
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

