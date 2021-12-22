/*
var express = require('express');

var server = express();

server.get('/', function (req, res) {

    res.send('Hello World!');

});

server.listen(5001, function () {
    console.log('Example server listening on port 5001!');
});*/
var express = require('express');
var server = express();
var http = require('http').Server(server);
var io = require('socket.io')(http);

server.get('/',function(req, res){
    res.sendFile(__dirname + '/client.html');
});

var count=1;
io.on('connection', function(socket){
    console.log('user connected: ', socket.id);
    var name = "user" + count++;
    io.to(socket.id).emit('change name',name);

    socket.on('disconnect', function(){
        console.log('user disconnected: ', socket.id);
    });

    socket.on('send message', function(name,text){
        var msg = name + ' : ' + text;
        console.log(msg);
        io.emit('receive message!', msg);
    });
});

http.listen(5001, function(){
    console.log('server on!');
});