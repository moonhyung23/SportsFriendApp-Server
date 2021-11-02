package ex_server2;

import java.io.*;
import java.net.Socket;
import java.util.Arrays;

public class SocketThread extends Thread {
    Socket socket;
    String user_idx;
    boolean identify_flag = false;
    int status_num = 0;
    String ar_user_idx;

    public SocketThread(Socket socket) {
        this.socket = socket;
    }

    @Override
    // Thread 에서 start() 메소드 사용 시 자동으로 해당 메소드 시작 (Thread별로 개별적 수행)
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

                System.out.println("방정보: " + readValue);
                String ar_roomInfor[] = readValue.split("@");
                //roominfor = status_num + "@" + user_idx + "@" + room_name + "@" + first_chat + "@" + roomIdx;
                System.out.println("방정보 배열:" + Arrays.toString(ar_roomInfor));
                //1번 -> 처음 방 생성을 하고 채팅 입력한 경우
                // - 채팅 방 정보 보내기
                //  - 입력한 채팅 내용 보내기
                if (Integer.parseInt(ar_roomInfor[0]) == 1) {
                    //방 생성
                    //방에 초대된 사람의 idx 번호들(JSON), 방번호
//                    UUID.randomUUID().toString()
                    Room room = RoomManager.CreateRoom(ar_roomInfor[1], ar_roomInfor[4]);
                    //채팅을 보낸 사용자가 참가한 방을 찾는다.
                    /*클라이언트들에게 메세지 보내기 */
                    //채팅방에 참여한 모든 사용자에게 메세지를 보낸다.
                    room.broadCast(readValue);
                }
                //2번 -> 채팅 보내기
                //  - 입력한 채팅 내용 보내기
                // roominfor = status_num + "@" + chat + "@" + roomIdx;


                else if (Integer.parseInt(ar_roomInfor[0]) == 2) {
                    for (int i = 0; i < RoomManager.List_room.size(); i++) {
                        System.out.println("방번호목록:  " + i + RoomManager.List_room.get(i).room_idx);
                    }
                    //전체 채팅 방 조회
                    for (int i = 0; i < RoomManager.List_room.size(); i++) {
                        //채팅을 보낸 사용자가 참가한 방을 찾는다.
                        if (RoomManager.List_room.get(i).room_idx.equals(ar_roomInfor[2])) {
                            /*클라이언트들에게 메세지 보내기 */
                            //채팅방에 참여한 모든 사용자에게 메세지를 보낸다.
                            RoomManager.List_room.get(i).broadCast(ar_roomInfor[1]);
                        }
                    }
                }

            }
        } catch (Exception e) {
            e.printStackTrace(); // 예외처리
        }
    }
}
