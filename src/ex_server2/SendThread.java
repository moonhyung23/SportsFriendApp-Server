package ex_server2;

import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.Scanner;

public class SendThread extends Thread {

    Socket socket = null;
    Scanner scan = new Scanner(System.in); // 채팅용 Scanner
    int user_idx;
    boolean identify;
    int number = 0;
    String roominfor = "";
    String room1 = "";
    String room2 = "";
    String chat = "";
    String status_num = "";
    String roomIdx = "";


    public SendThread(Socket socket, int user_idx) { // 생성자
        // 받아온 Socket Parameter를 해당 클래스 Socket에 넣기
        this.socket = socket;
        this.user_idx = user_idx;
    }

    public void run() {
        try {
            // OutputStream - 클라이언트에서 Server로 메세지 발송
            // socket의 OutputStream 정보를 OutputStream out에 넣은 뒤
            OutputStream out = socket.getOutputStream();
            // PrintWriter에 위 OutputStream을 담아 사용
            PrintWriter writer = new PrintWriter(out, true);

            while (true) { // 무한반복

                //처음 한번만 실행
                if (!identify) {
                    writer.println(user_idx); // 입력한 메세지 발송
                    System.out.println("서버에 보낸 나의 idx: " + user_idx);
                    identify = true;
                    continue;
                }
                System.out.println("1.채팅 방 생성    2.채팅 보내기");
                System.out.println("번호를 선택해 주세요");
                number = scan.nextInt();
                //1. 채팅 방 생성
                if (number == 1) {
                    System.out.println("1. 2번,3번    2. 3번,4번  ");
                    System.out.println("초대할 사람의 인덱스 번호를 선택하세요");
                    number = scan.nextInt();
                    //status_num 1번 -> 채팅 방 생성
                    status_num = "1";
                    String user_idx = "";
                    String room_name = "";
                    String first_chat = "";
//                    String[] ar_roomInfor = new String[3];
                    //1 -> 2번, 3번 초대
                    if (number == 1) {
                        //채팅방에 초대한 유저 idx번호
                        user_idx = "1$2$3";
                        System.out.println("첫 채팅을 입력해주세요");
                        scan.nextLine();
                        first_chat = scan.nextLine();
                        System.out.println("첫채팅:" + first_chat);
                        room_name = "1번 채팅방 생성";
                        room1 = room_name;
                        System.out.println(room_name + "완료");
                        roomIdx = "1";
                    }
                    //2 -> 3번, 4번 초대
                    else if (number == 2) {
                        //채팅방에 초대한 유저 idx번호
                        user_idx = "1$3$4";
                        System.out.println("첫 채팅을 입력해주세요");
                        scan.nextLine();
                        first_chat = scan.nextLine();
                        room_name = "2번 채팅방 생성";
                        room2 = room_name;
                        System.out.println(room_name + "완료");
                        roomIdx = "2";
                    }
               /*     ar_roomInfor[0] = room_name;
                    ar_roomInfor[1] = first_chat;
                    ar_roomInfor[2] = room_name;*/
                    roominfor = status_num + "@" + user_idx + "@" + room_name + "@" + first_chat + "@" + roomIdx;
                    writer.println(roominfor); // 입력한 메세지 발송
                }
                //2 -> 채팅 보내기
                else if (number == 2) {
                    // status_num 2 -> 채팅 보내기
                    status_num = "2";
                    System.out.println("1. 1번채팅방" + "  2. 2번채팅방");
                    System.out.println("채팅을 보낼 채팅방을 선택해주세요");
                    number = scan.nextInt();
                    if (number == 1) {
                        roomIdx = "1";
                        System.out.println("채팅을 입력해주세요");
                        scan.nextLine();
                        chat = scan.nextLine();
                    } else if (number == 2) {
                        roomIdx = "2";
                        System.out.println("채팅을 입력해주세요");
                        scan.nextLine();
                        chat = scan.nextLine();
                    }

                    roominfor = status_num + "@" + chat + "@" + roomIdx;
                    writer.println(roominfor); // 입력한 메세지 발송

                }


            }
        } catch (Exception e) {
            e.printStackTrace(); // 예외처리
        }


    }
}
