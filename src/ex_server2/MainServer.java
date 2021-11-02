package ex_server2;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;

public class MainServer {
    //소켓에 연결된 사용자의 정보를 담은 리스트
    static ArrayList<SocketThread> List_ConSocket = new ArrayList<>();

    public static void main(String[] args) {
        ServerSocket serverSocket = null;

        try {
            // 소켓 포트 설정용
            int socketPort = 7777;
            // 서버 소켓 만들기
            serverSocket = new ServerSocket(socketPort);
            // 서버 오픈 확인용
            System.out.println("socket : " + socketPort + "으로 서버가 열렸습니다");

            //클라이언트 연결 신호를 기다린다.
            while (true) {
                // 클라이언트가 서버에 소켓 연결 후
                // 서버에서 소켓(클라이언트와 연결된 소켓) 생성
                Socket socketUser = serverSocket.accept();
                // Thread 안에 클라이언트와 연결된 소켓을 생성자로 전달.
                SocketThread clientSocket = new SocketThread(socketUser);
                //소켓이 연결된 클라이언트를 리스트에 담는다
                List_ConSocket.add(clientSocket);
                //클라이언트의 메세지를 받는 스레드 시작.
                clientSocket.start();
            }

        } catch (
                IOException e) {
            e.printStackTrace(); // 예외처리
        }
    }
}
