const express = require('express');
const app = express();

const server = require('http').Server(app);
const io = require('socket.io')(server);

app.use(express.static('/htdocs/chat/'));
console.log(__filename);

server.listen(3000, () => {
    console.log('connect 5000');
});
app.get('/', (req, res) => {
    res.sendFile(__dirname + 'index.html');
});

// NameSpace 1 번
const namespace1 = io.of('/namespace1');
//connection을 받으면, news 이벤트에 hello 객체를 담아 보낸다
namespace1.on('connection', (socket) => {
    namespace1.emit('news', { hello: 'Someone connected at namespace1' });
});

//NameSpace 2 번
const namespace2 = io.of('/namespace2');
// connection을 받으면,news 이벤트에 hello 객체를 담아 보낸다
namespace2.on('connection', (socket) => {
    namespace2.emit('news', { hello: 'Someone connected at Namespace2' });
});