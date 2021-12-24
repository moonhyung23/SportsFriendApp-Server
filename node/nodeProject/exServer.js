const express = require('express');
const server = express();
const http = require('http').Server(server);
//Socket 서버
const io = require('socket.io')(http);
const port = 5000


server.get('/', function (req, res) {
    res.send('Hello World');
});

//연결이 되면 실행되는 메서드
io.on('connection', (socket) => {
    //연결성공
    console.log(`Socket connected : ${socket.id}`)

    //연결종료
    socket.on('disconnect', () => {
        console.log(`Socket disconnected : ${socket.id}`)
    })
})

//5001번 포트로 들어오는 접속 받기.
http.listen(port, function () {
    console.log('server on! port: ' + port);
});
