import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;


// 채팅 서버 소켓통신용  코드
public class SocketServer extends Thread {


    ClientSocket clientSocket = null;
    static ArrayList<ClientSocket> list_chat = new ArrayList<ClientSocket>();  // 유저 확인용

    Socket socket = null;
    boolean identify_flag = false;

    public SocketServer(Socket socket) {
        this.socket = socket; // 유저 socket을 할당
    }

    // Thread 에서 start() 메소드 사용 시 자동으로 해당 메소드 시작 (Thread별로 개별적 수행)
    public void run() {
        try {
            // 연결 확인용
            System.out.println("서버 : " + socket.getInetAddress()
                    + " IP의 클라이언트와 연결되었습니다");

            // InputStream - 클라이언트에서 보낸 메세지 읽기
            InputStream input = socket.getInputStream();
            BufferedReader reader = new BufferedReader(new InputStreamReader(input));

            // OutputStream - 서버에서 클라이언트로 메세지 보내기
            OutputStream out = socket.getOutputStream();
            PrintWriter writer = new PrintWriter(out, true);

            String readValue; // Client에서 보낸 값 저장

            // 클라이언트가 메세지 입력시마다 수행
            while ((readValue = reader.readLine()) != null) {
                if (!identify_flag) {
                    String idx = readValue;
                    list_chat.add(new ClientSocket(socket, idx)); // 유저를 list에 추가
                    identify_flag = true;
                    System.out.println("idx: " + readValue + "와 연결되었습니다.");
                    continue;
                }

                System.out.println(readValue);
                // list 안에 클라이언트 정보가 담겨있음
                for (int i = 0; i < list_chat.size(); i++) {
                    //소켓으로 연결된 모든 클라이언트에게 서버가 응답을 한다.
                    out = list_chat.get(i).clientSocket.getOutputStream();
                    writer = new PrintWriter(out, true);
                    // 클라이언트에게 메세지 발송
                    writer.println(readValue);
                }
            }
        } catch (Exception e) {
            e.printStackTrace(); // 예외처리
        }
    }

    public static void main(String[] args) {

        String url = "jdbc:mysql://3.37.253.243:3306/Sports_Friend_db";
        Statement stmt = null;
        Connection con = null;
        ResultSet rs = null;
        ServerSocket serverSocket = null;

       

        /* //Mysql db 연동
        try {
            // * mysql 연결
            con = getCon(url);
            // * 3.쿼리 수행을 위한 StateMent 객체 생성
            stmt = con.createStatement();
            // * 4.쿼리 수행
            // * 테이블에서 채팅 작성자의 사용자 정보 조회 
            rs = stmt.executeQuery("SELECT * FROM USERS WHERE user_idx = 6");

            while(rs.next()) {
                // * 컬럼 번호는 1번부터 시작
                String no= rs.getString(3);
                String tblname  = rs.getString(4);
                System.out.println(" no = " + no);
                System.out.println(" tblname= "+ tblname);
            }
            stmt.close();
            con.close();

        } catch (SQLException e) {
            System.out.println("에러 " + e);
        } */

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


    public static Connection getCon(String url) {
        Connection con = null;
        try {
            // * 1.드라이버 로딩 
            // * 드라이버 인터페이스를 구현한 클래스를 로딩
            // * mysql, oracle 등 각 벤더사 마다 클래스 이름이 다르다.
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("jdbc 드라이버 로딩 성공");

            // * 2. 연결하기
            // * 드라이버 매니저에게 Connection 객체를 달라고 요청한다.
            // * mysql은 "jdbc:mysql://localhost/사용할db이름" 이다.
            // @param  getConnection(url, userName, password);
            // @return Connection
            con = DriverManager.getConnection(url, "root", "ansgud12");
            System.out.println("mysql 접속 성공");
        }
        //예외처리
        catch (ClassNotFoundException e) {
            System.out.println("드라이버 로딩 실패");
        } catch (SQLException e) {
            System.out.println("에러 " + e);
        }
        //SQL Connection 리턴
        return con;
    }
}