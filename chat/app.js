/* socket\server.js */

const express = require('express');
const app = express();

const http = require('http').createServer(app);
const io = require('socket.io')(http);

//JS 파일 경로 지정
app.use(express.static(__dirname));
//html 파일 세팅
app.get('/', (req, res) => {
    cd.res.sendFile(__dirname + '/index.html');
});

//socket io 연결
io.on('connection', (socket) => {
    console.log('a user connected');
    socket.on('chat message', (msg) => {
        io.emit('chat message', msg);
    });
    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});
http.listen(5000, () => {
    console.log('Connected at 5000');
});