package ChatingServer;

public class Chat {
    String status_num = "";
    String chat_uuid = "";
    String chat_rp_cnt = "";

    //채팅 정보  채팅 방 입장시 클라이언트에 전달하는 정보
    public Chat(String status_num, String chat_uuid, String chat_rp_cnt) {
        this.status_num = status_num;
        this.chat_uuid = chat_uuid;
        this.chat_rp_cnt = chat_rp_cnt;
    }
}
