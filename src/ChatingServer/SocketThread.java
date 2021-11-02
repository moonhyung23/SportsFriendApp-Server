package ChatingServer;


import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.*;
import java.net.Socket;
import java.util.ArrayList;
import java.util.UUID;

/* 채팅 서버 에코 스레드
 *  -소켓에 연결된 유저에게 메세지를 보내준다.
 *  */
public class SocketThread extends Thread {
    Socket socket;
    String user_idx;
    boolean identify_flag = false;
    int status_num = 0;
    String ar_user_idx;
    String room_idx;
    ArrayList<String> List_roomInfor = new ArrayList<>();//불러올데이터리스트를생성한다.
    //1번 -> json 형식 에러
    int jsonError_flag = 0;
    DbManager dbManager;

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

                //채팅 방정보: roominfor =
                //* 채팅 정보 (@)
                // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
                // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
                // 4: 보낸사람(방장) idx
                // 5: 채팅 방 번호
                System.out.println("SocketServer_방정보: " + readValue);
                //Json으로 온 채팅 정보를 List<String>로 변환해주는 메서드
                set_List_roomInfor(List_roomInfor, readValue);

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
                    System.out.println("status_num1 JSON배열:  " + readValue);
                    // 방번호
                    room_idx = UUID.randomUUID().toString();
                    //방정보를 리스트에 입력
                    List_roomInfor.set(5, room_idx);
                    Room room = RoomManager.CreateRoom(List_roomInfor.get(1), room_idx);
                    dbManager.chat_RoomInsert(List_roomInfor);
                    /*클라이언트들에게 메세지 보내기 */
                    //* 채팅 정보 (@)
                    // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
                    // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
                    // 4: 보낸사람(방장) idx
                    // 5: 채팅 방 번호
                    room.broadCast(Send_jsonArray_roomInfor(List_roomInfor));
                }
                //status_num 2번 -> 채팅 보내기
                //  - 입력한 채팅 내용 보내기
                //* 채팅 정보 (@)
                // 0: 채팅 구분 번호 1: 채팅방에 초대된 유저 idx
                // 2: 채팅방에 초대된 유저 닉네임 3: 채팅 내용
                // 4: 보낸사람(방장) idx
                // 5: 채팅 방 번호
                else if (Integer.parseInt(List_roomInfor.get(0)) == 2) {
                    System.out.println("status_num2 JSON배열:  " + readValue);
                    //전체 채팅 방 조회
                    for (int i = 0; i < RoomManager.List_room.size(); i++) {
                        //채팅을 보낸 사용자가 참가한 방을 채팅방 리스트에서 방 번호로 찾는다.
                        if (RoomManager.List_room.get(i).room_idx.equals(List_roomInfor.get(5))) {
                            /*클라이언트들에게 메세지 보내기 */
                            //해당하는 채팅 방에 참여한 모든 사용자들에게 채팅 정보(메세지)보내기.
                            RoomManager.List_room.get(i).broadCast(Send_jsonArray_roomInfor(List_roomInfor));
                        }
                    }
                }
            }
        } catch (Exception e) {
            e.printStackTrace(); // 예외처리
        }
    }

    public void set_List_roomInfor(ArrayList<String> List_roomInfor, String jsonArray) {
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

                //json parsing한 채팅방 정보 리스트에 저장.
                List_roomInfor.add(0, status_num);
                List_roomInfor.add(1, invite_UserIdx);
                List_roomInfor.add(2, invite_UserNick);
                List_roomInfor.add(3, chatContent);
                List_roomInfor.add(4, hostIdx);
                List_roomInfor.add(5, roomIdx);
            }
        } catch (JSONException e) {
            //json 형식 에러
            jsonError_flag = 1;
            System.out.println("JSON 형식에러: " + jsonarray);
        }
    }

    //서버에 채팅 방 정보 리스트 JSONArray로 변환해서  보내기
    String Send_jsonArray_roomInfor(ArrayList<String> List_roomInfor) {
        JSONArray jarray = new JSONArray();
        try {
            JSONObject jobject = new JSONObject();
            jobject.put("status_num", List_roomInfor.get(0));
            jobject.put("invite_UserIdx", List_roomInfor.get(1));
            jobject.put("invite_UserNick", List_roomInfor.get(2));
            jobject.put("chatContent", List_roomInfor.get(3));
            jobject.put("hostIdx", List_roomInfor.get(4));
            jobject.put("roomIdx", List_roomInfor.get(5));
            jarray.put(jobject);
        } catch (JSONException e) {
            System.out.println("서버에서 데이터 보낼 때, JSON 변환 오류");
        }
        return jarray.toString();
    }


   /* public static Connection getCon(String url) {
        Connection con = null;
        try {
            // * 1.드라이버 로딩 
            // * 드라이버 인터페이스를 구현한 클래스를 로딩
            // * mysql, oracle 등 각 벤더사 마다 클래스 이름이 다르다.
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");

            // * 2. 연결하기
            // * 드라이버 매니저에게 Connection 객체를 달라고 요청한다.
            // * mysql은 "jdbc:mysql://localhost/사용할db이름" 이다.
            // @param  getConnection(url, userName, password);
            // @return Connection
            con = DriverManager.getConnection(url, "root", "ansgud12");
            System.out.println("mysql 접속 성공");
        }
        //예외처리
        catch (ClassNotFoundException e) {
            System.out.println("드라이버 로딩 실패");
        } catch (SQLException e) {
            System.out.println("에러 " + e);
        }
        //SQL Connection 리턴
        return con;
    }*/
}