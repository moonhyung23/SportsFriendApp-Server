package ex1;

import ChatingServer.DbManager;

import java.net.ServerSocket;
import java.sql.Connection;

public class ex_dbmanager {

    public static void main(String[] args) {
        Connection con = null;
        ServerSocket serverSocket;
        DbManager dbManager = new DbManager();

        try {
            // * 1.드라이버 로딩
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");
        } catch (ClassNotFoundException e) {
            System.out.println("드라이버 로딩 실패");
        }
        String[] ar_String1 = {"8", "6", "1"};
        //참석자가 같은 방이 있는지 확인하는 메서드
//        dbManager.select_chatRoom_RedunCheck(ar_String1);
//        dbManager.update();
    }
}
