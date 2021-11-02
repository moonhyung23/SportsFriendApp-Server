package ex1;

import java.text.SimpleDateFormat;
import java.util.Date;

public class ex_time {
    public static void main(String[] args) {
        Date today = new Date();
        System.out.println(today);

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd hh:mm:ss");
        SimpleDateFormat time = new SimpleDateFormat("hh:mm:ss a");

        System.out.println("Date: " + dateFormat.format(today));
        System.out.println("Time: " + time.format(today));
    }


}
