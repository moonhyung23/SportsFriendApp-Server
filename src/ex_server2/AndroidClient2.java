package ex_server2;

import java.io.IOException;
import java.net.Socket;

public class AndroidClient2 {


    public static void main(String[] args) {
        try {
            Socket socket = null;
            // 소켓 서버에 접속
            socket = new Socket("localhost", 7777);
            System.out.println("서버에 접속 성공!"); // 접속 확인용

            // 서버에서 보낸 메세지 읽는 Thread
            WriterThread t1 = new WriterThread(socket);
            SendThread t2 = new SendThread(socket, 2); // 서버로 메세지 보내는 Thread

            t1.start(); // ListeningThread Start
            t2.start(); // WritingThread Start

        } catch (IOException e) {
            e.printStackTrace(); // 예외처리
        }
    }
}
