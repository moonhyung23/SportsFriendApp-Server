package ChatingServer;


import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.*;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/* 채팅 서버 에코 스레드
 *  -소켓에 연결된 유저에게 메세지를 보내준다.
 *  */
//testtttdd
public class SocketThread extends Thread {
    Socket socket;
    String user_idx;
    //현재 입장 중인 채팅 방의 번호
    String enter_room_uuid = "";
    boolean identify_flag = false;
    int status_num = 0;
    String ar_user_idx;
    //채팅방, 채팅정보, 나가기정보, 초대정보  리스트
    ArrayList<String> List_roomInfor = new ArrayList<>();//불러올데이터리스트를생성한다.
    //채팅 번호(uuid)정보 리스트
    ArrayList<String> List_chat_uuid = new ArrayList<>();//
    //1번 -> json 형식 에러
    int jsonError_flag = 0;
    DbManager dbManager;
    OutputStream out;
    PrintWriter writer;
    String newInvite_Idx;


    public SocketThread(Socket socket, DbManager dbManager) {
        this.socket = socket; // 유저 socket을 할당
        this.dbManager = dbManager;
    }

    // Thread 에서 start() 메소드 사용 시 자동으로 해당 메소드 시작 (Thread별로 개별적 수행)
    @Override
    public void run() {
        try {

            // 연결 확인용
            System.out.println("서버 : " + socket.getInetAddress()
                    + " IP의 클라이언트와 연결되었습니다");
            // InputStream - 클라이언트에서 보낸 메세지 읽기
            InputStream input = socket.getInputStream();
            BufferedReader reader = new BufferedReader(new InputStreamReader(input));

            // OutputStream - 서버에서 클라이언트로 메세지 보내기
            OutputStream out = socket.getOutputStream();
            PrintWriter writer = new PrintWriter(out, true);

            String readValue; // Client에서 보낸 값 저장

            //클라이언트가 보낸 메세지를 기다린다.
            //-클라이언트에서 보낸 메세지가 있는 경우
            while ((readValue = reader.readLine()) != null) {
                //처음 한 번만 실행 소켓에 연결된 사용자의 idx 번호를 변수에 저장하기 위해 실행
                if (!identify_flag) {
                    //소켓에 연결된 클라이언트의 인덱스번호 변수에 저장
                    user_idx = readValue;
                    //소켓에 연결된 클라이언트 리스트에 추가.
                    System.out.println("연결된 사용자 idx번호: " + readValue);
                    //처음 한 번만 실행하기 위해서 true
                    identify_flag = true;
                    continue;
                }
                System.out.println("List_변환전 JSON정보 : " + readValue);
                //Json으로 온 채팅 정보를 List<String>로 변환해주는 메서드
                // - JSON -> List
                Set_List_roomInfor(List_roomInfor, readValue);
                //채팅 방 정보
                //1번 -> JSON 형식 에러인 경우
                if (jsonError_flag == 1) {
                    //0번 -> 원상태로 복구
                    jsonError_flag = 0;
                    continue;
                }

                //status_num 1번 -> 처음 방 생성을 하고 채팅 입력한 경우
                // - 채팅 방 정보 보내기
                // - 입력한 채팅 내용 보내기
                if (Integer.parseInt(List_roomInfor.get(0)) == 1) {
                    //추가한 채팅의 idx 번호
                    // -추가하자마자 바로 갖고오기
                    String last_chat_idx = "";
                    //채팅 db테이블에  추가하고 반환받은 값
                    String returnValue = "";
                    //초대 정보
                    String inviteInfor = "";

                    //현재 입장한 채팅 방 번호(uuid) 저장
                    enter_room_uuid = List_roomInfor.get(5);

                    //현재 채팅방에 입장 중인 유저 소켓의 개수를 구한다.
                    int con_SocketCnt = get_Connect_SocketUserCnt(List_roomInfor.get(5));
                    //채팅 읽은 사람 수
                    // -현재 읽은 사람 수  - 현재 소켓에 연결된 유저(채팅방 입장)
                    // -본인이 제외되어서 -1을 했음.
                    String rp_Cnt = String.valueOf(Integer.parseInt(List_roomInfor.get(13)) - (con_SocketCnt - 1));
                    if (rp_Cnt.equals("0")) {
                        rp_Cnt = "";
                    }
                    //채팅 읽은 사람 수 리스트에 저장
                    List_roomInfor.set(13, rp_Cnt);

                    //채팅 방 정보 DB테이블에 저장
                    dbManager.Insert_chatRoom(List_roomInfor);
                    // 채팅정보 Db 테이블에 저장
                    // viewType 1번 -> 채팅, 날짜, 초대정보
                    returnValue = dbManager.Insert_ChatInfor(List_roomInfor, 1, 1);
                    String[] ar_return = returnValue.split("\\$");
                    //초대정보
                    inviteInfor = ar_return[0];
                    //마지막으로 추가한 인덱스 번호
                    last_chat_idx = ar_return[1];

                    // -추가하자마자 바로 갖고온  idx(pk)  것 리스트에 저장
                    List_roomInfor.set(14, String.valueOf(last_chat_idx));
                    //채팅방에 초대된 사람의 idx 번호 배열
                    String[] ar_invite_user_idx = List_roomInfor.get(1).split("\\$");
                    /*채팅방에 초대된 사용자에게 채팅방 정보 + 채팅 보내기.*/
                    //채팅방 정보 리스트를 Json배열로 변환해서 전달한다.
                    broadCast(Send_jsonArray_roomInfor(List_roomInfor, inviteInfor), ar_invite_user_idx);
                }
                //status_num 2번 -> 채팅 보내기
                //  - 입력한 채팅 내용 보내기
                else if (Integer.parseInt(List_roomInfor.get(0)) == 2 || Integer.parseInt(List_roomInfor.get(0)) == 7) {
                    String last_chat_idx = "";
                    String returnValue = "";
                    String inviteInfor = "";
                    //현재 채팅방에 입장 중인 유저 소켓의 개수를 구한다.
                    int con_SocketCnt = get_Connect_SocketUserCnt(List_roomInfor.get(5));
                    //채팅 읽은 사람 수
                    // -현재 읽은 사람 수  - 현재 소켓에 연결된 유저(채팅방 입장)
                    // -본인이 제외되어서 -1을 했음.
                    String rp_Cnt = String.valueOf(Integer.parseInt(List_roomInfor.get(13)) - (con_SocketCnt - 1));
                    if (rp_Cnt.equals("0")) {
                        rp_Cnt = "";
                    }
                    System.out.println("List_roomInfor.get(13):" + List_roomInfor.get(13));
                    System.out.println("con_SocketCnt:" + String.valueOf(con_SocketCnt));
                    System.out.println("rp_Cnt:" + rp_Cnt);
                    //채팅 읽은 사람 수 리스트에 저장
                    List_roomInfor.set(13, rp_Cnt);

                    // viewType 2번 -> 채팅
                    // 1번 -초대정보
                    // 2번 -db 테이블에 마지막으로 추가한 idx 번호
                    // 채팅정보 Db 테이블에 저장
                    if (List_roomInfor.get(0).equals("2")) {
                        //viewType 2번 -> 채팅
                        //초대하지 않은 경우 -> "초대정보 없음 반환"
                        returnValue = dbManager.Insert_ChatInfor(List_roomInfor, 2, 2);
                    } else if (List_roomInfor.get(0).equals("7")) {
                        //viewType 4번 -> 이미지
                        returnValue = dbManager.Insert_ChatInfor(List_roomInfor, 4, 2);
                    }
                    /*AAAAA*/
                    // -최근 채팅 보낸 날짜 수정
                    dbManager.update_chatRoom_editDate(List_roomInfor);
                    String[] ar_return = returnValue.split("\\$");
                    //초대정보
                    inviteInfor = ar_return[0];
                    //마지막으로 추가한 채팅의 인덱스 번호
                    last_chat_idx = ar_return[1];

                    //현재 DB에 추가한 채팅의 마지막 인덱스번호
                    List_roomInfor.set(14, String.valueOf(last_chat_idx));

                    //방번호(5)를 이용해서 DB에서 채팅방의 참여한 유저의 idx 번호를 조회한다.
                    String[] invite_idx = dbManager.select_Invite_idx(List_roomInfor.get(5)).split("\\$");
                    //채팅방에 참여한 사람에게 채팅 보내주기.
                    broadCast(Send_jsonArray_roomInfor(List_roomInfor, inviteInfor), invite_idx);
                }

                //status_num -> 3번 채팅 방 나가기 (완전히 나가기)
                else if (Integer.parseInt(List_roomInfor.get(0)) == 3) {
                    //입장한 채팅 방 번호 초기화
                    enter_room_uuid = "";

                    //채팅방에 참여한 사람의 idx 번호 배열
                    String[] ar_invite_user_idx = List_roomInfor.get(1).split("\\$");
                    //채팅방에 참여한 유저 idx 배열
                    List<String> list_attend_user_idx = new ArrayList(Arrays.asList(List_roomInfor.get(1).split("\\$")));
                    String result = dbManager.query_ChatRoomExit(list_attend_user_idx, List_roomInfor);

                    if (result.equals("수정성공")) {
                        //채팅방 나간 사람 정보
                        String exit_infor = List_roomInfor.get(2) + "님이 채팅방에서 나갔습니다.";
                        //채팅방 나간 사람 정보 DB에 저장
                        dbManager.Insert_exit_inviteInfor(List_roomInfor, exit_infor);

                        //채팅방에 참여한 사람에게 메세지 전달(나간 사람제외)
                        broadCast(Send_jsonArray_roomInfor(List_roomInfor, exit_infor), ar_invite_user_idx);
                    } else if (result.equals("삭제성공")) {
                        broadCast(Send_jsonArray_roomInfor(List_roomInfor, ""), ar_invite_user_idx);
                    }
                }

                //status_num -> 4번 채팅 방 초대
                else if (Integer.parseInt(List_roomInfor.get(0)) == 4) {
                    //채팅방에 참여한 유저 idx 모음
                    //새로 초대한 유저 idx 번호 모음
                    newInvite_Idx = List_roomInfor.get(2).substring(0, List_roomInfor.get(2).length() - 1);
                    //List_roomInfor.get(1): 새로초대한닉네임 + 이전에초대한 닉네임
                    // -채팅방 제목 반환
                    // -채팅방제목 -> 참석한 유저 닉네임
                    String roomTitle = dbManager.select_nickname(List_roomInfor.get(1), 1);
                    // -새로 초대한 유저 닉네임 반환
                    String newInvite_nick = dbManager.select_nickname(newInvite_Idx, 1);
                    // 채팅방 정보 수정
                    // -채팅방 초대 정보를 받아온다.
                    String invite_infor = dbManager.update_chatRoom(
                            List_roomInfor.get(1),
                            roomTitle,
                            List_roomInfor.get(5),
                            List_roomInfor.get(8),
                            newInvite_nick);

                    //채팅 방 날짜 수정
                    dbManager.update_chatRoom_editDate(List_roomInfor);
                    //초대정보 채팅내역에 저장
                    dbManager.Insert_exit_inviteInfor(List_roomInfor, invite_infor);
                    //새로 초대한 유저 닉네임 입력
                    List_roomInfor.set(2, newInvite_nick);
                    //채팅방 제목 수정
                    //- 새로 초대한 사람닉네임 까지 제목에 추가
                    List_roomInfor.set(9, roomTitle);
                    //채팅방에 참여한 유저 idx 배열
                    String[] ar_invite_idx = List_roomInfor.get(1).split("\\$");

                    //Send_jsonArray() 에서 초대정보를 리스트에 추가 후 JsonArray로 변환
                    //broadCast()에서 JsonArray 채팅방에 참여한 유저에게 전송
                    broadCast(Send_jsonArray_roomInfor(List_roomInfor, invite_infor), ar_invite_idx);
                }
                //status_num -> 5번 채팅 방 입장
                else if (Integer.parseInt(List_roomInfor.get(0)) == 5) {
                    int update_flag = 0;
                    int j = 0;
                    //현재 입장한 채팅 방 번호(uuid) 저장
                    enter_room_uuid = List_roomInfor.get(5);

                    int con_SocketCnt = get_Connect_SocketUserCnt(List_roomInfor.get(5));
                    System.out.println("con_SocketCnt:" + con_SocketCnt);
                    //1번 -> 업데이트 (새로운 채팅 읽음)
                    //2번 -> 업데이트 X  (이미 읽은 채팅)
                    update_flag = dbManager.update_chat_rp(List_roomInfor);
                    //새로운 채팅을 읽었을 때만 읽었다고 채팅방에 참여한 유저에게 전송
                    if (update_flag == 1) {
                        //채팅방에 참여한 유저 idx 배열
                        String[] ar_invite_idx_tmp = List_roomInfor.get(1).split("\\$");
                        String[] ar_invite_idx = new String[ar_invite_idx_tmp.length - 1];
                        //배열에서 나의 idx만 제외하기
                        for (String s : ar_invite_idx_tmp) {
                            //나의 idx 번호가 아닌 경우
                            if (!s.equals(List_roomInfor.get(4))) {
                                //다른 유저의 idx 번호 추가
                                ar_invite_idx[j] = s;
                                //인덱스 값 1증가
                                ++j;
                            }
                        }
                        //클라이언트에 채팅 읽었다고 신호 보내기
                        broadCast(Send_jsonArray_roomInfor(List_roomInfor, ""), ar_invite_idx);
                    }
                }

                //status_num -> 6번 채팅 방 나가기(완전히 X)
                else if (Integer.parseInt(List_roomInfor.get(0)) == 6) {
                    //현재 입장한 채팅 방 번호(uuid) 초기화
                    enter_room_uuid = "";
                }

                //status_num -> 8번 화상통화 신청
                else if (Integer.parseInt(List_roomInfor.get(0)) == 8) {
                    //마지막으로 추가한 채팅인덱스번호(PK)
                    String last_chat_idx = "";
                    //채팅 정보 저장 시 반환되는 문자열
                    //"초대정보$채팅인덱스 번호"
                    String returnValue = "";
                    //초대정보
                    String inviteInfor = "";
                    //현재 채팅방에 입장 중인 유저 소켓의 개수를 구한다.
                    int con_SocketCnt = get_Connect_SocketUserCnt(List_roomInfor.get(5));
                    //채팅 읽지 않은 사람 수
                    // -현재 읽은 사람 수  - 현재 소켓에 연결된 유저(채팅방 입장)
                    // -본인이 제외되어서 -1을 했음.
                    String rp_Cnt = String.valueOf(Integer.parseInt(List_roomInfor.get(13)) - (con_SocketCnt - 1));
                    if (rp_Cnt.equals("0")) {
                        rp_Cnt = "";
                    }
                    System.out.println("305번줄_status_num(8) 채팅방의 입장중인 유저 명 수: " + String.valueOf(con_SocketCnt));
                    System.out.println("306번줄_status_num(8) 채팅읽지 않은 사람 수: " + rp_Cnt);
                    //채팅 읽은 사람 수 리스트에 저장
                    List_roomInfor.set(13, rp_Cnt);
                    //DB에 채팅 정보 저장
                    returnValue = dbManager.Insert_ChatInfor(List_roomInfor, 2, 2);
                    // -최근 채팅 보낸 날짜 DB에서 수정
                    dbManager.update_chatRoom_editDate(List_roomInfor);
                    String[] ar_return = returnValue.split("\\$");
                    //초대정보
                    inviteInfor = ar_return[0];
                    //마지막으로 추가한 채팅의 인덱스 번호
                    last_chat_idx = ar_return[1];
                    //현재 DB에 추가한 채팅의 마지막 인덱스번호
                    List_roomInfor.set(14, String.valueOf(last_chat_idx));
                    //방번호(5)를 이용해서 DB에서 채팅방의 참여한 유저의 idx 번호를 조회한다.
                    String[] invite_idx = dbManager.select_Invite_idx(List_roomInfor.get(5)).split("\\$");
                    //채팅방에 참여한 사람에게 채팅 보내주기.
                    broadCast(Send_jsonArray_roomInfor(List_roomInfor, inviteInfor), invite_idx);
                }
            }
        } catch (Exception e) {

        }
    }

    public int get_Connect_SocketUserCnt(String enter_room_uuid) {
        //소켓에 접속중인 유저의 수
        int con_count = 0;
        for (int i = 0; i < MainServer.List_ConSocket.size(); i++) {
            SocketThread th_socket = MainServer.List_ConSocket.get(i);
            System.out.println("th_socket.enter_room_uuid: " + th_socket.enter_room_uuid);
            System.out.println("enter_room_uuid: " + enter_room_uuid);
            //채팅방에 참여한 유저의 인원 수를 구한다.
            if (th_socket.enter_room_uuid.equals(enter_room_uuid)) {
                //채팅 방 번호가 있을 때만
                con_count += 1;
            }
        }
        System.out.println("con_count:  " + con_count + "명");
        return con_count;
    }


    //클라이언트에서 보낸 Json데이터 -> 리스트로 변환해주는 메서드
    public void Set_List_roomInfor(ArrayList<String> List_roomInfor, String jsonArray) {
        String jsonarray = jsonArray;
        try {
            //초기화
            List_roomInfor.clear();
            /*쉐어드에 데이터가 저장되어 있지 않을 경우에 대한 에러 예방 */
            JSONArray jarray = new JSONArray(jsonarray);//Json 배열에 쉐어드에서 불러온 JSON배열을 담는다.
            for (int i = 0; i < jarray.length(); i++) {//Json 배열에 담긴 데이터의 갯수만큼 반복
                //Json배열에 담긴 json객체를 갖고온 후 json객체에 저장된 변수 값을 String변수에 담는다.
                JSONObject jobject = jarray.getJSONObject(i);
                //상태번호  1번 -> 초대   2번 -> 채팅
                String status_num = String.valueOf(jobject.getInt("status_num")); //상태번호  1번 -> 초대 2번 -> 채팅
                String invite_UserIdx = jobject.getString("invite_UserIdx"); //초대된 유저 idx
                String invite_UserNick = jobject.getString("invite_UserNick"); //초대된 유저 닉네임
                String chatContent = jobject.getString("chatContent"); //채팅 내용
                String hostIdx = jobject.getString("hostIdx"); //방장 인덱스
                String roomIdx = jobject.getString("roomIdx"); //채팅 방 번호
                String chatIdx = jobject.getString("chatIdx"); //채팅번호
                String chat_created_date = jobject.getString("chat_created_date"); //채팅 생성 날짜
                String chat_sendNickname = jobject.getString("chat_sendNickname"); //채팅 보낸 사람 닉네임
                String chatRoom_name = jobject.getString("chatRoom_name"); //채팅 방 제목
                String chat_profileImgUrl = jobject.getString("chat_profileImgUrl"); //채팅 보낸 사람 프로필 이미지
                String chat_viewType = String.valueOf(jobject.getInt("chat_viewType")); //채팅 뷰타입
                String invite_Infor = jobject.getString("invite_Infor"); //채팅 정보
                String chat_rp_cnt = jobject.getString("chat_rp_cnt"); //채팅 읽은 사람 수
                String chat_id = jobject.getString("chat_id"); //채팅 읽은 사람 수
                String chatRoom_imgUrl = jobject.getString("chatRoom_imgUrl"); //채팅 읽은 사람 수
                String callee_nickname = jobject.getString("callee_nickname");
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
                // -1번 -> 채팅, 날짜, 초대정보
                // -2번 -> 채팅
                // -3번 -> 초대정보
                // 12: 초대정보
                //채팅 뷰타입 번호
                // 13: 채팅 읽은 사람 수
                // 14: 채팅 idx 번호
                // 15: 채팅방 이미지 url
                // 16: 영상통화 요청을 받는 유저의 닉네임

                //json parsing한 채팅방 정보 리스트에 저장.
                List_roomInfor.add(0, status_num);
                List_roomInfor.add(1, invite_UserIdx);
                List_roomInfor.add(2, invite_UserNick);
                List_roomInfor.add(3, chatContent);
                List_roomInfor.add(4, hostIdx);
                List_roomInfor.add(5, roomIdx);
                List_roomInfor.add(6, chatIdx);
                List_roomInfor.add(7, chat_created_date);
                List_roomInfor.add(8, chat_sendNickname);
                List_roomInfor.add(9, chatRoom_name);
                List_roomInfor.add(10, chat_profileImgUrl);
                List_roomInfor.add(11, chat_viewType);
                List_roomInfor.add(12, invite_Infor);
                List_roomInfor.add(13, chat_rp_cnt);
                List_roomInfor.add(14, chat_id);
                List_roomInfor.add(15, chatRoom_imgUrl);
                List_roomInfor.add(16, callee_nickname);

            }
        } catch (JSONException e) {
            //json 형식 에러
            jsonError_flag = 1;
            System.out.println("Set_List JSON 형식에러: " + jsonarray);
        }
    }

    /*채팅방에 참여한 모든 사용자에게 채팅을 보냄.*/
    void broadCast(String readValue, String[] ar_invite_user_idx) throws IOException {
        /*채팅 방에 초대된 유저 idx를 찾는다.*/
        // - ar_invite_user_idx -> 채팅방에 초대된 유저 idx
        // - List<SocketConnect>에서 for문을 사용해서 찾는다.
        //소켓에 연결된 유저 리스트 검색
        for (int i = 0; i < MainServer.List_ConSocket.size(); i++) {
            //채팅방에 초대된 유저 idx 배열 검색
            for (String ar_InviteUserIdx : ar_invite_user_idx) {
                //소켓에 연결된 유저 idx == 채팅방에 초대된 유저 idx 비교
                if (MainServer.List_ConSocket.get(i).user_idx.equals(ar_InviteUserIdx)) {
                    //채팅방의 참가한 사람의 socket 객체
                    out = MainServer.List_ConSocket.get(i).socket.getOutputStream();
                    writer = new PrintWriter(out, true);
                    //소켓에 연결된 클라이언트에게 메세지 발송
                    writer.println(readValue);
                    System.out.println("broadCast: " + readValue);
                }
            }
        }
    }

    //서버에 채팅 방 정보 리스트 JSONArray로 변환해서  보내기
    String Send_jsonArray_roomInfor(ArrayList<String> List_roomInfor, String inviteInfor) {
        JSONArray jarray = new JSONArray();
        try {
            JSONObject jobject = new JSONObject();
            jobject.put("status_num", List_roomInfor.get(0)); //채팅 방 생성, 채팅 보내기 구분번호
            jobject.put("invite_UserIdx", List_roomInfor.get(1)); //초대된 사람 idx
            jobject.put("invite_UserNick", List_roomInfor.get(2)); //초대된 사람 닉네임
            jobject.put("chatContent", List_roomInfor.get(3)); //채팅내용
            jobject.put("hostIdx", List_roomInfor.get(4)); //채팅방 방장 idx
            jobject.put("roomIdx", List_roomInfor.get(5)); //채팅 방 idx
            jobject.put("chatIdx", List_roomInfor.get(6)); //채팅 idx
            jobject.put("chat_created_date", List_roomInfor.get(7)); //채팅 보낸 날짜
            jobject.put("chat_sendNickname", List_roomInfor.get(8)); //채팅 보낸 사람 닉네임
            jobject.put("chatRoom_name", List_roomInfor.get(9)); //채팅 보낸 사람 닉네임
            jobject.put("chat_profileImgUrl", List_roomInfor.get(10)); //채팅 보낸 사람 닉네임
            jobject.put("chat_viewType", List_roomInfor.get(11)); //채팅 뷰타입
            jobject.put("invite_Infor", inviteInfor); //초대정보
            jobject.put("chat_rp_cnt", List_roomInfor.get(13)); //채팅 읽은 사람 수
            jobject.put("chat_id", List_roomInfor.get(14)); //채팅 idx 번호
            jobject.put("chatRoom_imgUrl", List_roomInfor.get(15)); //채팅방 이미지 url
            jobject.put("callee_nickname", List_roomInfor.get(16));  //영상통화 요청을 받는 유저의 닉네임
            jarray.put(jobject);
        } catch (JSONException e) {
            System.out.println("서버에서 데이터 보낼 때, JSON 변환 오류");
        }
        return jarray.toString();
    }
}