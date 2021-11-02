package ex_server2;

import java.util.ArrayList;

public class RoomManager {
    static ArrayList<Room> List_room = new ArrayList<>();

    static Room CreateRoom(String user_idx_json, String room_idx) {
        //room 객체 생성
        Room room = new Room(user_idx_json, room_idx);
        //리스트에 채팅방을 추가.
        List_room.add(room);
        return room;
    }
}
