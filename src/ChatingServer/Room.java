package ChatingServer;


import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.util.ArrayList;

/* 채팅 방 클래스 * */
public class Room {
    ArrayList<SocketThread> List_user;
    String room_idx;
    OutputStream out;
    PrintWriter writer;

    //채팅방에 초대된 유저의 소켓을 리스트에 추가한다.
    public Room(String user_idx, String room_idx) {
        //리스트<Room>에 추가한 방 객체에 room_idx를 입력
        //채팅 방 번호 -> UUID 키
        this.room_idx = room_idx;
        //채팅방에 초대된 유저의 idx 번호
        String[] ar_invite_user_idx = user_idx.split("\\$");

        /*채팅 방에 초대된 유저 idx를 찾는다.*/
        //채팅방에 초대된 사용자 idx 배열 <==> 소켓에 연결된 사용자 idx (리스트)
        //채팅 방에 참여한 유저의 객체를 담고 있는 리스트
        List_user = new ArrayList<SocketThread>();
        for (int i = 0; i < MainServer.List_ConSocket.size(); i++) {
            for (String ar_InviteUserIdx : ar_invite_user_idx) {
                if (MainServer.List_ConSocket.get(i).user_idx.equals(ar_InviteUserIdx)) {
                    //List_user에 소켓에 연결된 방에 참여한 객체를 입력
                    //방에 참여한 사람의 객체를 List에 저장
                    List_user.add(MainServer.List_ConSocket.get(i));
                }
            }
        }
    }

    /*채팅방에 참여한 모든 사용자에게 채팅을 보냄.*/
    void broadCast(String readValue) throws IOException {
        //List_user -> 방에 참여한 유저의 모음
        for (SocketThread clientSocket : List_user) {
            out = clientSocket.socket.getOutputStream();
            writer = new PrintWriter(out, true);
            //소켓에 연결된 클라이언트에게 메세지 발송
            writer.println(readValue);
            System.out.println(readValue);
        }
    }
}
