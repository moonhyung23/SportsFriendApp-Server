package ex1;

public class DataClass {
    int status_num = 0;
    String chat_uuid = "";
    int rp_cnt = 0;

    public DataClass(int status_num, String chat_uuid, int rp_cnt) {
        this.status_num = status_num;
        this.chat_uuid = chat_uuid;
        this.rp_cnt = rp_cnt;
    }
}
