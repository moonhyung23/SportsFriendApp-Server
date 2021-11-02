package ChatingServer;


import java.util.ArrayList;

/* 채팅 방 추가, 수정, 삭제, 조회 */
public class RoomManager {
    static ArrayList<Room> List_room = new ArrayList<>();
    DbManager dbManager;

    /* 채팅 방을 생성하는 메서드*/
    static Room CreateRoom(String user_idx_json, String room_idx) {
        //room 객체 생성
        Room room = new Room(user_idx_json, room_idx);
        //리스트에 채팅방을 추가.
        List_room.add(room);
        return room;
    }
}
