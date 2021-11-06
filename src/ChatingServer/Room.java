package ChatingServer;


import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintWriter;

/* 채팅 방 클래스 * */
public class Room {
    String room_idx;
    OutputStream out;
    PrintWriter writer;

    //채팅방에 초대된 유저의 소켓을 리스트에 추가한다.
    public Room(String invite_user_idx, String room_idx) {
        //리스트<Room>에 추가한 방 객체에 room_idx를 입력
        //채팅 방 번호 -> UUID 키
        this.room_idx = room_idx;
        //채팅방에 초대된 유저의 idx 번호
        String[] ar_invite_user_idx = invite_user_idx.split("\\$");
    }

    /*채팅방에 참여한 모든 사용자에게 채팅을 보냄.*/
    void broadCast(String readValue, String[] ar_invite_user_idx) throws IOException {
        /*채팅 방에 초대된 유저 idx를 찾는다.*/
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
                    System.out.println(readValue);
                }
            }
        }
    }
}
