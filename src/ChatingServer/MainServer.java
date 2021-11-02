package ChatingServer;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.sql.Connection;
import java.util.ArrayList;

/* - 클라이언트의 소켓 연결을 기다림
 * - 클라이언트의 소켓 연결 시 SocketThread스레드 실행
 * */
public class MainServer {
    //소켓에 연결된 사용자의 정보를 담은 리스트
    static ArrayList<SocketThread> List_ConSocket = new ArrayList<>();

    public static void main(String[] args) {
        Connection con = null;
        ServerSocket serverSocket;
        DbManager dbManager = null;
        try {
            // * 1.드라이버 로딩
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");
            dbManager = new DbManager();
        } catch (ClassNotFoundException e) {
            System.out.println("드라이버 로딩 실패");
        }

        try {
            // 소켓 포트
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
                System.out.println("접속한 클라이언트 정보 : " + "[" + socketUser.getInetAddress().getHostName() + "]");
                // Thread 안에 클라이언트와 연결된 소켓을 생성자로 전달.
                SocketThread clientSocket = new SocketThread(socketUser, dbManager);
                //소켓이 연결된 클라이언트를 리스트에 담는다
                List_ConSocket.add(clientSocket);
                //클라이언트의 메세지를 받는 스레드 시작.
                clientSocket.start();
            }

        } catch (IOException e) {
            e.printStackTrace(); // 예외처리
        }
    }
}
