var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var bodyParser = require('body-parser');

app.use(bodyParser.urlencoded({
    extended: false
}));
app.use(bodyParser.json());

// Logging Middleware
app.use(function (req, res, next) {
    next();
});

app.get('/', function (req, res) {
    res.json({
        welcome: 'queue api'
    });
});

io.on('connection', function (socket) {

    socket.on('register', function (res) { // ลงทะเบียน, พิมพ์บัตรคิว
        socket.broadcast.emit('register',res);
    });

    socket.on('on-call', function (res) { // เรียกคิว
        socket.broadcast.emit('on-call',res);
    });

    socket.on('on-end', function (res) { // เสร็จสิ้น
        socket.broadcast.emit('on-end',res);
    });

    socket.on('on-hold', function (res) { // พักคิว
        socket.broadcast.emit('on-hold',res);
    });

    socket.on('on-show-display', function (res) { // พักคิว
        socket.broadcast.emit('on-show-display',res);
    });

    socket.on('update-display', function (res) { // พักคิว
        socket.broadcast.emit('update-display',res);
    });

    socket.on('CHECK_DEVICE', function (res) {
        socket.broadcast.emit('CHECK_DEVICE',res);
    });

    socket.on('CHECK_DEVICE_SUCCESS', function (res) {
        socket.broadcast.emit('CHECK_DEVICE_SUCCESS',res);
    });

    socket.on('CARD_REMOVED', function (res) {
        socket.broadcast.emit('CARD_REMOVED',res);
    });

    socket.on('DEVICE_CONNECTED', function (res) {
        socket.broadcast.emit('DEVICE_CONNECTED',res);
    });

    socket.on('DEVICE_DISCONNECTED', function (res) {
        socket.broadcast.emit('DEVICE_DISCONNECTED',res);
    });

    socket.on('READING_FAIL', function (res) {
        socket.broadcast.emit('READING_FAIL',res);
    });

    app.post('/card-inserted', function (req, res) {
        if (!req.body) return res.sendStatus(400)
        socket.broadcast.emit('card-inserted', {
            com_name: req.body.com_name
        });
        res.send('ok');
    });

    // POST method route
    app.post('/print-ticket', function (req, res) {
        if (!req.body) return res.sendStatus(400)

        socket.broadcast.emit('read-smart-card', req.body);
        // var form = new multiparty.Form();

        // form.on('error', function (err) {
        //     console.log('Error parsing form: ' + err.stack);
        // });

        // form.parse(req, function (err, fields, files) {
        //     console.log('fields: %@', fields);
        //     //console.log('files: %@', files);
        //    /*  var profile = fields['EZ1503378440057007100[profile]'];
        //     profile = JSON.parse(profile[0]);
        //     socket.broadcast.emit('read-smart-card', {
        //         profile: profile,
        //         files: files,
        //     }); */
        // });

        res.send('ok');
    });

    socket.on('disconnect', function () {
        //console.log('user disconnected');
    });
});

http.listen(3000, function () {
    console.log('listening on *:3000');
});