package testServer;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

import SocketServer.SocketServer;

public class Server {
    RoomManager roomManager = new RoomManager();


    public static void main(String[] args) {
        ServerSocket serverSocket = null;
        try {
            // 소켓 포트 설정용
            int socketPort = 7777; 
            // 서버 소켓 만들기 
            serverSocket = new ServerSocket(socketPort); 
            // 서버 오픈 확인용
            System.out.println("socket : " + socketPort + "으로 서버가 열렸습니다");

            // 소켓 서버가 종료될 때까지 무한루프
            while (true) {
                // 서버에 클라이언트 접속 시
                Socket socketUser = serverSocket.accept(); 
                // Thread 안에 클라이언트와 연결된 소켓을 생성자로 전달. 
                Thread thd = new SocketServer(socketUser);
                thd.start(); // Thread 시작
            }

        } catch (IOException e) {
            e.printStackTrace(); // 예외처리
        }
    }
}
