package ex1;

import java.util.ArrayList;
import java.util.HashMap;

public class Ex_hashMap {

    public static void main(String[] args) {
        DataClass dataClass;
        String value = "";
        ArrayList<String> List_chatidx = new ArrayList<>();
        List_chatidx.add("Ri1");
        List_chatidx.add("Ri2");
        List_chatidx.add("Ri3");
        List_chatidx.add("dsadsad");

        HashMap<String, DataClass> hash_chat_idx = new HashMap<>();
        hash_chat_idx.put("Ri1", new DataClass(5, "1", 1));
        hash_chat_idx.put("Ri3", new DataClass(5, "3", 3));
        hash_chat_idx.put("Ri4", new DataClass(5, "4", 4));
        hash_chat_idx.put("Ri2", new DataClass(5, "2", 2));
        hash_chat_idx.put("Ri5", new DataClass(5, "5", 5));

        for (String list_chatidx : List_chatidx) {
            int i = 0;

            dataClass = hash_chat_idx.get(list_chatidx);
            if (dataClass == null) {
                System.out.println("데이터없음");
                continue;
            }
            value = String.valueOf(dataClass.rp_cnt);

            System.out.println(i + " " + value);
            ++i;
        }
    }
}
