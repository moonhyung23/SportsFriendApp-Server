package testServer;
import java.net.Socket;
import java.util.ArrayList;

public class User {
    Socket socket;
    Thread thread;
    public User(Socket socket, Thread thread) {
        this.socket = socket; // 유저 socket을 할당
        this.thread = thread;
    }

    


}