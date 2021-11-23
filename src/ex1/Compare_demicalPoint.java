package ex1;

import java.math.BigDecimal;
import java.text.DecimalFormat;

public class Compare_demicalPoint {
    public static void main(String[] args) {
        String date = "2021/11/23 12:44:37";

        String year = date.substring(0, 4);
        DecimalFormat form = new DecimalFormat("#.###");
        float month_day = (float) (Integer.parseInt(date.substring(5, 10).replace("/", "")) / 10000.0);

        float time = Float.parseFloat(date.substring(11, date.length()).replace(":", ""));
        float time2 = (float) (time / 1000000.0);

        BigDecimal year_d = new BigDecimal(year);
        BigDecimal month_d = new BigDecimal(String.valueOf(month_day));
        BigDecimal time_d = new BigDecimal(String.valueOf(time2));
        /*      float a = year + month_day +
        Double value = 36.58030044836733;
        DecimalFormat form = new DecimalFormat("#.####");
        System.out.println(form.format(value));    // -> 36.5803 출력됨*/

        double add1 = Float.parseFloat(year + month_day);
        System.out.println("year: " + year);
        System.out.println("year_d: " + year_d.add(month_d));
        System.out.println("time_d: " + time_d);


        float a = 2021.1123124437f;
        float b = 2021.1123124438f;
        float c = 2021.1123124439f;
        float d = 2021.1123124440f;
        float e = 2021.1123124441f;
/*
        if (a > b) {
            System.out.println("a가 더큼 a의 값: " + a);
        } else {

            System.out.println("b가 더큼 b의 값: " + b);
        }*/

    }
}
