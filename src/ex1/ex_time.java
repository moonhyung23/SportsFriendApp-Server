package ex1;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.TimeZone;

public class ex_time {
    public static void main(String[] args) {
        Date today = new Date();
        System.out.println(today);

        TimeZone tz = TimeZone.getTimeZone("Asia/Seoul");
        SimpleDateFormat df = new SimpleDateFormat("yyyy/MM/dd kk:mm:ss");
        SimpleDateFormat time = new SimpleDateFormat("hh:mm:ss a");
        df.setTimeZone(tz);
        System.out.println("Date: " + df.format(today));
        System.out.println("Time: " + time.format(today));
    }

}
