package ex1;

import java.util.Arrays;

public class ex_arrayRedudancy {

    public static void main(String[] args) {

        String[] ar_1 = {"3", "2", "1",};
        String[] ar_2 = {"1", "2", "3"};
        //배열 1 비교
        int[] arr2 = new int[ar_1.length];
        for (int i = 0; i < ar_1.length; i++) {
            arr2[i] = Integer.parseInt(ar_1[i]);
        }

        //배열 2 비교
        int[] arr3 = new int[ar_2.length];
        for (int i = 0; i < ar_2.length; i++) {
            arr3[i] = Integer.parseInt(ar_2[i]);
        }
        Arrays.sort(arr2);

        System.out.println("결과2: " + Arrays.equals(arr2, arr3));
        System.out.println("결과3: " + arr2.equals(arr3));

    }
}
